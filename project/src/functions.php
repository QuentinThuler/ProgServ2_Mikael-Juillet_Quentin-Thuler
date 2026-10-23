<?php

function render(string $component, array $data = []): void {
    $componentPath = __DIR__ . '/../components/' . $component . '.php';
    extract($data, EXTR_SKIP);
    require $componentPath;
}

function validateEvent(
    ?string $title,
    ?string $description,
    ?string $tagline,
    ?string $address,
    ?string $city,
    ?string $room,
    ?string $date_time,
    ?string $image,
): array {
    $errors = [];

    if (empty($title)) {
        array_push($errors, "Le titre est obligatoire.");
    } else if (strlen($title) > 255) {
        array_push($errors, "Le titre ne peut pas faire plus de 255 caractères.");
    }

    if (empty($description)) {
        array_push($errors, "La description est obligatoire.");
    } else if (strlen($description) > 500) {
        array_push($errors, "Le titre ne peut pas faire plus de 500 caractères.");
    }

    if (empty($tagline)) {
        array_push($errors, "La phrase d'accroche est obligatoire.");
    } else if (strlen($description) > 80) {
        array_push($errors, "La phrase d'accroche ne peut pas faire plus de 80 caractères.");
    }

    if (empty($address)) {
        array_push($errors, "L'adresse est obligatoire.");
    } else if (strlen($address) > 60) {
        array_push($errors, "La phrase d'accroche ne peut pas faire plus de 60 caractères.");
    }

    if (empty($city)) {
        array_push($errors, "Le lieu est obligatoire.");
    } else if (strlen($city) > 40) {
        array_push($errors, "Le lieu  ne peut pas faire plus de 40 caractères.");
    }

    if (strlen($room) > 20) {
        array_push($errors, "La pièce ne peut pas faire plus de 20 caractères.");
    }

    if (empty($date_time)) {
        array_push($errors, "La date et l'heure sont obligatoires.");
    }

    if (strlen($image) < 3) {
        array_push($errors, "L'image n'est pas valide.");
    }

    return $errors;
}

function addEvent(
    PDO $pdo,
    ?string $title,
    ?string $description,
    ?string $tagline,
    ?string $address,
    ?string $city,
    ?string $room,
    ?string $date_time,
    ?string $image,
): ?int {
    $sql = "INSERT INTO events (
        title,
        description,
        tagline,
        address,
        city,
        room,
        date_time,
        image
    ) VALUES (
        :title,
        :description,
        :tagline,
        :address,
        :city,
        :room,
        :date_time,
        :image
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':tagline', $tagline);
    $stmt->bindValue(':address', $address);
    $stmt->bindValue(':city', $city);
    $stmt->bindValue(':room', $room);
    $stmt->bindValue(':date_time', $date_time);
    $stmt->bindValue(':image', $image);

    $stmt->execute();

    $lastInsertId = $pdo->lastInsertId();

    return $lastInsertId;
}
