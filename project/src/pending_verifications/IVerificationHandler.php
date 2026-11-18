<?php

interface IVerificationHandler {
    public function handle(string $email, JsonSerializable $payload): void;
}
