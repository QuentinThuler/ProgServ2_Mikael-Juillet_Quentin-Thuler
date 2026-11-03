<?php

class Event {
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public string $tagline,
        public string $address,
        public string $city,
        public ?string $room,
        public string $dateTime,
        public ?string $imagePath,
        public int $creatorId,
        public ?string $createdAt = null
    ) {
    }
}
