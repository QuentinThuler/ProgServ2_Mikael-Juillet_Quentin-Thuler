<?php

namespace App\pendingVerifications;

use App\db\Database;
use App\participant\ParticipantRequests;
use App\registration\RegistrationController;
use App\registration\RegistrationRequests;
use App\role\RoleRequests;
use App\user\UserController;
use App\user\UserRequests;
use RuntimeException;

class VerificationHandlerFactory {
    public static function handle(string $purpose): IVerificationHandler {

        $database = Database::getInstance();
        $pdo = $database->getConnection();

        switch ($purpose) {
            case 'event_registration':
                return new RegistrationController(
                    new RegistrationRequests($pdo)
                );
            case 'account_creation':
                return new UserController(
                    new UserRequests($pdo),
                    new ParticipantRequests($pdo),
                    new RoleRequests($pdo)
                );
            default:
                throw new RuntimeException("Unknown purpose: $purpose");
        }
    }
}
