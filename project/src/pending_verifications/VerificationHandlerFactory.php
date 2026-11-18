<?php
require_once __DIR__ . '/../registration/RegistrationController.php';
require_once __DIR__ . '/../user/UserController.php';

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
