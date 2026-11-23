<?php

namespace App\services;

function escape(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function translate(string $key): string {
    global $translations, $language;

    return $translations[$language][$key] ?? $key;
}
