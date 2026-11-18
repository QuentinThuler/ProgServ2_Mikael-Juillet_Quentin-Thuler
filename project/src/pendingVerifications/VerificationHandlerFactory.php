<?php

namespace App\PendingVerifications;

use InvalidArgumentException;
use RegistrationController;
use UserController;

class VerificationHandlerFactory {
    public static function handle(string $purpose): IVerificationHandler {
        switch ($purpose) {
            case 'event_registration':
                return new RegistrationController();
            case 'account_creation':
                return new UserController();
            default:
                throw new InvalidArgumentException("Unknown purpose: $purpose");
        }
    }
}
