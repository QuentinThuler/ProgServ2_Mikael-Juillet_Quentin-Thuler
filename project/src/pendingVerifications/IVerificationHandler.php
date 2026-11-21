<?php

namespace App\pendingVerifications;

interface IVerificationHandler {
    public function handle(string $email, string $payload): void;
}
