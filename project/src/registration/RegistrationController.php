<?php

namespace App\registration;

use App\pendingVerifications\IVerificationHandler;
use JsonSerializable;

class RegistrationController implements IVerificationHandler {

    private $registrationRequests;

    public function __construct(RegistrationRequests $registrationRequests) {
        $this->registrationRequests = $registrationRequests;
    }

    public function handle(string $email, string $payload): void {
        // TODO : Implement the logic to handle the registration process after successful verification
    }
}
