<?php

namespace App\services;

use RuntimeException;

function verifyCsrfToken(): void {
    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (
        !is_string($token)
        || !is_string($sessionToken)
        || $sessionToken === ''
        || !hash_equals($sessionToken, $token)
    ) {
        throw new RuntimeException('Requête invalide. Veuillez réessayer.');
    }
}
