<?php

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/Event.php';
require_once __DIR__ . '/EventRequests.php';

class EventController
{
    private EventRequests $eventRequests;

    public function __construct(EventRequests $eventRequests)
    {
        $this->eventRequests = $eventRequests;
    }

    public function index(): array
    {
        return $this->eventRequests->getAllEvents();
    }

    public function upcoming(): array
    {
        return $this->eventRequests->getUpcomingEvents();
    }

    public function show(int $id): ?Event
    {
        return $this->eventRequests->getEventById($id);
    }

    public function create(array $postData, ?array $imageFile, int $userId): int
    {
        $title = trim($postData['title'] ?? '');
        $description = trim($postData['description'] ?? '');
        $tagline = trim($postData['tagline'] ?? '');
        $address = trim($postData['address'] ?? '');
        $city = trim($postData['city'] ?? '');
        $room = trim($postData['room'] ?? '');
        $dateTimeStr = trim($postData['date_time'] ?? '');

        $imageUpload = (defined('IMAGE_UPLOAD_ENABLED') && IMAGE_UPLOAD_ENABLED) ? $imageFile : null;

        // Validation (lève une InvalidArgumentException en cas d'erreur, comme dans ParticipantController)
        $this->validateEvent(
            $title,
            $description,
            $tagline,
            $address,
            $city,
            $room,
            $dateTimeStr,
            $imageUpload
        );

        $imagePath = null;
        if ($imageUpload !== null && $imageUpload['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->saveEventImage($imageUpload);
            if ($imagePath === null) {
                throw new Exception("Impossible d'enregistrer l'image.");
            }
        }

        // Instanciation du modèle Event (comme new Participant($email))
        $event = new Event(
            $title,
            $description,
            $tagline,
            $address,
            $city,
            $room !== '' ? $room : null,
            new DateTime($dateTimeStr),
            $imagePath,
            $userId
        );

        $newEventId = $this->eventRequests->create($event);

        if ($newEventId === null) {
            throw new Exception("Une erreur est survenue lors de l'ajout de l'évènement.");
        }

        return $newEventId;
    }

    private function validateEvent(
        string $title,
        string $description,
        string $tagline,
        string $address,
        string $city,
        string $room,
        string $dateTimeStr,
        ?array $imageFile = null
    ): void {
        if ($title === '') {
            throw new InvalidArgumentException("Le titre est obligatoire.");
        }
        if (mb_strlen($title) > 255) {
            throw new InvalidArgumentException("Le titre ne peut pas faire plus de 255 caractères.");
        }

        if ($description === '') {
            throw new InvalidArgumentException("La description est obligatoire.");
        }
        if (mb_strlen($description) > 500) {
            throw new InvalidArgumentException("La description ne peut pas faire plus de 500 caractères.");
        }

        if ($tagline === '') {
            throw new InvalidArgumentException("La phrase d'accroche est obligatoire.");
        }
        if (mb_strlen($tagline) > 80) {
            throw new InvalidArgumentException("La phrase d'accroche ne peut pas faire plus de 80 caractères.");
        }

        if ($address === '') {
            throw new InvalidArgumentException("L'adresse est obligatoire.");
        }
        if (mb_strlen($address) > 60) {
            throw new InvalidArgumentException("L'adresse ne peut pas faire plus de 60 caractères.");
        }

        if ($city === '') {
            throw new InvalidArgumentException("La ville est obligatoire.");
        }
        if (mb_strlen($city) > 40) {
            throw new InvalidArgumentException("La ville ne peut pas faire plus de 40 caractères.");
        }

        if (mb_strlen($room) > 20) {
            throw new InvalidArgumentException("La salle ne peut pas faire plus de 20 caractères.");
        }

        if ($dateTimeStr === '') {
            throw new InvalidArgumentException("La date et l'heure sont obligatoires.");
        }
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $dateTimeStr);
        if ($date === false || $date->format('Y-m-d\TH:i') !== $dateTimeStr) {
            throw new InvalidArgumentException("La date et l'heure ne sont pas valides.");
        }

        // Validation sécurisée de l'image
        if ($imageFile !== null && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
            if (!isset($imageFile['tmp_name']) || !is_uploaded_file($imageFile['tmp_name'])) {
                throw new InvalidArgumentException("Fichier téléversé invalide.");
            }
            if ($imageFile['error'] !== UPLOAD_ERR_OK) {
                throw new InvalidArgumentException("Le téléversement de l'image a échoué.");
            }

            $realSize = filesize($imageFile['tmp_name']);
            if ($realSize === false || $realSize <= 0 || $realSize > MAX_IMAGE_SIZE) {
                throw new InvalidArgumentException("L'image est vide ou dépasse la taille maximale autorisée.");
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($imageFile['tmp_name']);
            if ($mimeType === false || !isset(ALLOWED_IMAGE_TYPES[$mimeType])) {
                throw new InvalidArgumentException("L'image doit être au format JPEG, PNG ou WebP.");
            }

            if (@getimagesize($imageFile['tmp_name']) === false) {
                throw new InvalidArgumentException("Le fichier fourni n'est pas une image valide.");
            }
        }
    }

    private function saveEventImage(array $file): ?string
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!isset(ALLOWED_IMAGE_TYPES[$mimeType])) {
            return null;
        }

        // Extension déterminée uniquement par notre liste blanche côté serveur
        $extension = ALLOWED_IMAGE_TYPES[$mimeType];
        // Nom aléatoire cryptographiquement sûr (aucune donnée utilisateur conservée)
        $fileName  = bin2hex(random_bytes(16)) . '.' . $extension;
        $dir       = __DIR__ . '/../../public/uploads/events';

        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            return null;
        }

        $destination = $dir . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        // Droits en lecture/écriture uniquement (non exécutable)
        chmod($destination, 0644);

        return '/uploads/events/' . $fileName;
    }

    public function upcoming(): array
    {
        return $this->eventRequests->getUpcomingEvents();
    }
}
