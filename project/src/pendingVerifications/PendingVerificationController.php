<?php

namespace App\pendingVerifications;

use DateTime;
use InvalidArgumentException;

use function App\services\compareToken;
use function App\services\encriptToken;
use function App\services\generateToken;

class PendingVerificationController {
    private PendingVerificationRequests $pendingVerificationRequests;

    public function __construct(PendingVerificationRequests $pendingVerificationRequests) {
        $this->pendingVerificationRequests = $pendingVerificationRequests;
    }

    public function create(string $email, string $purpose, array $payload): void {

        $email = trim($email);

        $this->validateEmail($email);

        $_SESSION['email_temp'] = $email;

        $token = generateToken(6);
        $encryptedToken = encriptToken($token);

        $pendingVerification = new PendingVerification($email, $encryptedToken, $purpose, payload: $payload);
        $existingPendingVerification = $this->pendingVerificationRequests->getByEmail($email);

        if (!$existingPendingVerification) {
            $this->pendingVerificationRequests->create($pendingVerification);
        } else {
            $this->pendingVerificationRequests->update($pendingVerification);
        }

        error_log("Token generated for $email: $token"); // TODO : replace by sending the token via email to the user

    }

    public function validate(string $email, string $token): void {
        $email = trim($email);
        $token = trim($token);

        $this->validateEmail($email);

        // Validate the token length
        if (strlen($token) !== 6) {
            throw new InvalidArgumentException('Le code de vérification est invalide.');
        }

        $existingPendingVerification = $this->pendingVerificationRequests->getByEmail($email);

        if (!$existingPendingVerification) {
            throw new InvalidArgumentException('Aucune demande de vérification en attente trouvée pour cet e-mail.');
        }

        if (
            $existingPendingVerification->getTokenExpiresAt() > new DateTime()
            && compareToken($token, $existingPendingVerification->getTokenHash())
        ) {
            $verificationHandler = VerificationHandlerFactory::handle($existingPendingVerification->getPurpose());
            $this->pendingVerificationRequests->delete($email);
            $verificationHandler->handle($email, $existingPendingVerification->getPayload());
        } else {
            throw new InvalidArgumentException('Le code de vérification est invalide ou a expiré.');
        }
    }

    public function revalidate(string $email): void {
        $email = trim($email);

        $this->validateEmail($email);

        $_SESSION['email_temp'] = $email;

        $token = generateToken(6);
        $encryptedToken = encriptToken($token);

        $pendingVerification = new PendingVerification(email : $email, tokenHash : $encryptedToken, purpose : "");
        $existingPendingVerification = $this->pendingVerificationRequests->getByEmail($email);

        if (!$existingPendingVerification) {
            throw new InvalidArgumentException("Aucune demande en cours à cette adresse.");
        } else {
            $this->pendingVerificationRequests->updateToken($pendingVerification);
        }

        error_log("Token generated for $email: $token"); // TODO : replace by sending the token via email to the user
    }



    // PRIVATE METHODS

    private function validateEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Adresse e-mail invalide.');
        }
    }
}
