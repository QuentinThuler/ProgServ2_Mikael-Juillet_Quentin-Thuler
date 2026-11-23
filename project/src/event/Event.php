<?php

namespace App\event;

use DateTime;

class Event {
    private ?int $id = null;
    private string $title;
    private string $description;
    private string $tagline;
    private string $address;
    private string $city;
    private ?string $room;
    private DateTime $dateTime;
    private ?string $imagePath;
    private int $creatorId;
    private ?DateTime $createdAt = null;

    public function __construct(
        string $title,
        string $description,
        string $tagline,
        string $address,
        string $city,
        ?string $room,
        DateTime $dateTime,
        ?string $imagePath,
        int $creatorId
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->tagline = $tagline;
        $this->address = $address;
        $this->city = $city;
        $this->room = $room;
        $this->dateTime = $dateTime;
        $this->imagePath = $imagePath;
        $this->creatorId = $creatorId;
    }

    public function setEventAllValues(
        int $id,
        DateTime $createdAt
    ): void {
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getTagline(): string {
        return $this->tagline;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function getRoom(): ?string {
        return $this->room;
    }

    public function getDateTime(): DateTime {
        return $this->dateTime;
    }

    public function getImagePath(): ?string {
        return $this->imagePath;
    }

    public function getCreatorId(): int {
        return $this->creatorId;
    }

    public function getCreatedAt(): ?DateTime {
        return $this->createdAt;
    }
}
