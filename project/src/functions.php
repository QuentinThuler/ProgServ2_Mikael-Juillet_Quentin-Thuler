<?php

function render(string $component, array $data = []): void {
    $componentPath = __DIR__ . '/../components/' . $component . '.php';
    extract($data, EXTR_SKIP);
    require $componentPath;
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function t(string $key): string {
    global $translations, $language;

    return $translations[$language][$key] ?? $key;
}

const MAX_IMAGE_SIZE = 2 * 1024 * 1024;

const ALLOWED_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];
