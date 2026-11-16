<?php

require_once __DIR__ . '/../init.php';

require_once __DIR__ . '/EventRequests.php';

class EventController {
    public function __construct(
        private EventRequests $requests
    ) {
    }

    public function store(array $postData, ?array $imageFile, int $userId): array {
        $title = $postData["title"] ?? '';
        $description = $postData["description"] ?? '';
        $tagline = $postData["tagline"] ?? '';
        $address = $postData["address"] ?? '';
        $city = $postData["city"] ?? '';
        $room = $postData["room"] ?? '';
        $date_time = $postData["date_time"] ?? '';

        // Validation
        $imageUpload = IMAGE_UPLOAD_ENABLED ? ($imageFile ?? null) : null;
        $errors = $this->validateEvent(
            $title,
            $description,
            $tagline,
            $address,
            $city,
            $room,
            $date_time,
            $imageUpload
        );

        $imagePath = null;
        if (empty($errors) && $imageUpload !== null && $imageUpload['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->saveEventImage($imageUpload);
            if ($imagePath === null) {
                $errors[] = "Impossible d'enregistrer l'image.";
            }
        }

        if (empty($errors)) {
            $formattedDate = (new DateTime($date_time))->format('Y-m-d H:i:s');

            $newEventId = $this->requests->create(
                $title,
                $description,
                $tagline,
                $address,
                $city,
                $room ?: null,
                $formattedDate,
                $imagePath,
                $userId
            );

            if ($newEventId !== null) {
                header('Location: ./view.php?id=' . $newEventId);
                exit;
            }

            $errors[] = "Une erreur est survenue lors de l'ajout de l'évènement.";
        }

        return [
            'errors' => $errors,
            'inputs' => compact('title', 'description', 'tagline', 'address', 'city', 'room', 'date_time')
        ];
    }

    private function validateEvent(
        string $title,
        string $description,
        string $tagline,
        string $address,
        string $city,
        string $room,
        string $date_time,
        ?array $imageFile = null,
    ): array {
        $errors = [];

        if ($title === '') {
            $errors[] = "Le titre est obligatoire.";
        } elseif (mb_strlen($title) > 255) {
            $errors[] = "Le titre ne peut pas faire plus de 255 caractères.";
        }

        if ($description === '') {
            $errors[] = "La description est obligatoire.";
        } elseif (mb_strlen($description) > 500) {
            $errors[] = "La description ne peut pas faire plus de 500 caractères.";
        }

        if ($tagline === '') {
            $errors[] = "La phrase d'accroche est obligatoire.";
        } elseif (mb_strlen($tagline) > 80) {
            $errors[] = "La phrase d'accroche ne peut pas faire plus de 80 caractères.";
        }

        if ($address === '') {
            $errors[] = "L'adresse est obligatoire.";
        } elseif (mb_strlen($address) > 60) {
            $errors[] = "L'adresse ne peut pas faire plus de 60 caractères.";
        }

        if ($city === '') {
            $errors[] = "La ville est obligatoire.";
        } elseif (mb_strlen($city) > 40) {
            $errors[] = "La ville ne peut pas faire plus de 40 caractères.";
        }

        if (mb_strlen($room) > 20) {
            $errors[] = "La salle ne peut pas faire plus de 20 caractères.";
        }


        $date = DateTime::createFromFormat('Y-m-d\TH:i', $date_time);
        if ($date_time === '') {
            $errors[] = "La date et l'heure sont obligatoires.";
        } elseif ($date === false || $date->format('Y-m-d\TH:i') !== $date_time) {
            $errors[] = "La date et l'heure ne sont pas valides.";
        }

        if ($imageFile !== null && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($imageFile['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "Le téléversement de l'image a échoué.";
            } elseif ($imageFile['size'] > MAX_IMAGE_SIZE) {
                $errors[] = "L'image ne peut pas dépasser 2 Mo.";
            } elseif (!isset(ALLOWED_IMAGE_TYPES[mime_content_type($imageFile['tmp_name'])])) {
                $errors[] = "L'image doit être au format JPEG, PNG ou WebP.";
            }
        }

        return $errors;
    }

    private function saveEventImage(array $file): ?string {
        $extension = ALLOWED_IMAGE_TYPES[mime_content_type($file['tmp_name'])]; // Stocke l'extension
        $fileName  = bin2hex(random_bytes(8)) . '.' . $extension; // convertit la nomenclature en 16 caractères hexadécimaux
        $dir       = __DIR__ . '/../public/uploads/events';       // à adapter à votre arborescence

        if (!is_dir($dir) && !mkdir($dir, 0755, true)) { // Si le dossier n'existe pas, on tente de le créer
            return null;
        }
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $fileName)) { // Déplacement du fichier de son emplacement temporaire vers sa destination définitive
            return null;
        }

        return '/uploads/events/' . $fileName; // retourne le chemin web
    }
}
