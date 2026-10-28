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

        // Validation (vous conservez votre logique de validation existante)
        $imageUpload = IMAGE_UPLOAD_ENABLED ? ($imageFile ?? null) : null;
        $errors = validateEvent(
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
            $imagePath = saveEventImage($imageUpload);
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
}
