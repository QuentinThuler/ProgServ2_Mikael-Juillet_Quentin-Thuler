<?php

require_once __DIR__ . '/Event.php';

class EventRequests {
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function create(
        string $title,
        string $description,
        string $tagline,
        string $address,
        string $city,
        ?string $room,
        string $dateTime,
        ?string $imagePath,
        int $creatorId
    ): ?int {
        $sql = "INSERT INTO evenements (titre, description, phrase_accroche, adresse, ville, salle, date_et_heure, image, createur_id) 
                VALUES (:title, :description, :tagline, :address, :city, :room, :date_time, :image, :creator_id)";

        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'title' => $title,
            'description' => $description,
            'tagline' => $tagline,
            'address' => $address,
            'city' => $city,
            'room' => $room,
            'date_time' => $dateTime,
            'image' => $imagePath,
            'creator_id' => $creatorId
        ]);

        if ($success) {
            return (int) $this->pdo->lastInsertId();
        }

        return null;
    }
}
