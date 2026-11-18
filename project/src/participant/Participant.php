<?php

namespace App\participant;

class Participant {
    private ?int $id;
    private string $email;

    public function __construct(string $email) {
        $this->email = $email;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setID(int $id): void {
        $this->id = $id;
    }
}
