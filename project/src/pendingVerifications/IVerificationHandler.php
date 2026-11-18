<?php

namespace App\pendingVerifications;

use JsonSerializable;

interface IVerificationHandler {
    public function handle(string $email, JsonSerializable $payload): void;
}
