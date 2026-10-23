<?php 

class Participant {
    private int $id;
    private string $email;
    private bool $isConfirmed;
    private ?string $tokenHash;
    private ?DateTime $tokenExpiresAt;

    public function __construct(string $email) {
        $this->email = $email;
    }

    public function setParticipantAllValues(int $id, string $email, bool $isConfirmed, ?string $tokenHash, ?DateTime $tokenExpiresAt): void {
        $this->id = $id;
        $this->isConfirmed = $isConfirmed;
        $this->tokenHash = $tokenHash;
        $this->tokenExpiresAt = $tokenExpiresAt;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function isConfirmed(): bool {
        return $this->isConfirmed;
    }

    public function getTokenHash(): string {
        return $this->tokenHash;
    }

    public function getTokenExpiresAt(): ?DateTime {
        return $this->tokenExpiresAt;
    }
}
