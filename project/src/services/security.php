<?php

namespace App\services;

use RuntimeException;

function escape(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function verifyCsrfToken(): void {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token)
        || !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        throw new RuntimeException('Requête invalide. Veuillez réessayer.');
    }
}
