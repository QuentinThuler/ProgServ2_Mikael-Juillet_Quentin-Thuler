<?php

namespace App\pendingVerifications;

use DateTime;
use JsonSerializable;

class PendingVerification {
    private ?int $id;
    private string $email;
    private string $tokenHash;
    private DateTime $tokenExpiresAt;
    private string $purpose;
    private ?JsonSerializable $payload;
    private int $attemptCount;

    public function __construct(
        string $email,
        ?string $tokenHash,
        string $purpose,
        ?DateTime $tokenExpiresAt = null,
        ?JsonSerializable $payload = null,
        int $attemptCount = 0
    ) {
        $this->email = $email;
        $this->tokenHash = $tokenHash;
        $this->tokenExpiresAt = $tokenExpiresAt ?? new DateTime('+3 hours');
        $this->purpose = $purpose;
        $this->payload = $payload;
        $this->attemptCount = $attemptCount;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTokenHash(): ?string {
        return $this->tokenHash;
    }

    public function getTokenExpiresAt(): DateTime {
        return $this->tokenExpiresAt;
    }

    public function getPurpose(): string {
        return $this->purpose;
    }

    public function getPayload(): JSONSerializable {
        return $this->payload;
    }

    public function getAttemptCount(): int {
        return $this->attemptCount;
    }

    public function incrementAttemptCount(): void {
        $this->attemptCount++;
    }

    public function setID(int $id): void {
        $this->id = $id;
    }
}
