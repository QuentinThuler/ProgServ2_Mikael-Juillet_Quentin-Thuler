<?php

function render(string $component, array $data = []): void {
    $componentPath = __DIR__ . '/../components/' . $component . '.php';
    extract($data, EXTR_SKIP);
    require $componentPath;
}

const MAX_IMAGE_SIZE = 2 * 1024 * 1024;

const ALLOWED_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

function validateEvent(
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

function saveEventImage(array $file): ?string {
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

function addEvent(
    PDO $pdo,
    string $title,
    string $description,
    string $tagline,
    string $address,
    string $city,
    ?string $room,
    string $date_time,
    ?string $image,
    int $createdBy,
): ?int {
    $sql = "INSERT INTO events (
        title,
        description,
        tagline,
        address,
        city,
        room,
        date_time,
        image,
        created_by
    ) VALUES (
        :title,
        :description,
        :tagline,
        :address,
        :city,
        :room,
        :date_time,
        :image,
        :created_by
    )";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':tagline'     => $tagline,
            ':address'     => $address,
            ':city'        => $city,
            ':room'        => $room,
            ':date_time'   => $date_time,
            ':image'       => $image,
            ':created_by'  => $createdBy,
        ]);
    } catch (PDOException $e) {
        error_log('addEvent : ' . $e->getMessage());
        return null;
    }

    return (int) $pdo->lastInsertId();
}
