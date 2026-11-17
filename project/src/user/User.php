<?php

class User {
    private ?int $id;
    private Participant $participant;
    private string $name;
    private string $password;
    private ?string $description;
    private ?string $image;
    private ?string $linkedin;
    private ?string $phone;
    private Role $role;

    public function __construct(
        Participant $participant,
        string $name,
        string $password,
        Role $role,
        ?string $description = null,
        ?string $image = null,
        ?string $linkedin = null,
        ?string $phone = null
    ) {
        $this->participant = $participant;
        $this->name = $name;
        $this->password = $password;
        $this->role = $role;
        $this->description = $description;
        $this->image = $image;
        $this->linkedin = $linkedin;
        $this->phone = $phone;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->participant->getEmail();
    }

    public function getParticipant(): Participant {
        return $this->participant;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function getLinkedin(): ?string {
        return $this->linkedin;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function getRole(): Role {
        return $this->role;
    }
}
