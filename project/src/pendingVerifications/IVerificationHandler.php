<?php

namespace App\PendingVerifications;

use JsonSerializable;

interface IVerificationHandler {
    public function handle(string $email, JsonSerializable $payload): void;
}
