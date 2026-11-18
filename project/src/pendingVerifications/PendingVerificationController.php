<?php

namespace App\PendingVerifications;

use DateTime;
use InvalidArgumentException;
use JsonSerializable;
use PendingVerificationRequests;

class PendingVerificationController {
    private PendingVerificationRequests $pendingVerificationRequests;

    public function __construct(PendingVerificationRequests $pendingVerificationRequests) {
        $this->pendingVerificationRequests = $pendingVerificationRequests;
    }

    public function create(string $email, string $purpose, JsonSerializable $payload): void {

        $email = trim($email);

        $this->validateEmail($email);

        $token = generateToken(6);
        $encryptedToken = encriptToken($token);
        error_log($token); // TODO : replace by sending the token via email to the user

        $pendingVerification = new PendingVerification($email, $encryptedToken, $purpose, payload: $payload);
        $existingPendingVerification = $this->pendingVerificationRequests->getByEmail($email);

        if (!$existingPendingVerification) {
            $this->pendingVerificationRequests->create($pendingVerification);
        } else {
            $this->pendingVerificationRequests->update($pendingVerification);
        }
    }

    public function validateToken(string $email, string $token): void {
        $email = trim($email);
        $token = trim($token);

        $this->validateEmail($email);

        // Validate the token length
        if (strlen($token) !== 6) {
            throw new InvalidArgumentException('Le code de vérification est invalide.');
        }

        $existingPendingVerification = $this->pendingVerificationRequests->getPendingVerificationByEmail($email);

        if (!$existingPendingVerification) {
            throw new InvalidArgumentException('Aucune vérification en attente trouvée pour cet e-mail.');
        }

        if ($existingPendingVerification->getTokenExpiresAt() > new DateTime() && compareToken($token, $existingPendingVerification->getTokenHash())) {
            // TODO : Send purpose and payload to the next step of the registration process
            // TODO : Delete the pending verification after successful validation
        } else {
            throw new InvalidArgumentException('Le code de vérification est invalide ou a expiré.');
        }
    }

    // PRIVATE METHODS

    private function validateEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Adresse e-mail invalide.');
        }
    }
}
