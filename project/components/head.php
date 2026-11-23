<?php

use function App\services\escape;

/**
 * @var bool $language Defined in header.php
 * @var bool $pageTitle Defined in views pages
 */

require_once __DIR__ . '/../src/init.php';
?>

<head>
    <!DOCTYPE html>
    <html lang="<?= $language ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? escape($pageTitle) . ' - Gatherly' : 'Gatherly' ?></title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="icon" type="image/svg+xml" href="/images/logo/favicon.svg">
</head>
