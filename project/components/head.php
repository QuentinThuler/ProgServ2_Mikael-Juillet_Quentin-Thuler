<?php
require_once __DIR__ . '/../src/init.php';
?>

<head>
    <!DOCTYPE html>
    <html lang="<?= $language ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - Gatherly' : 'Gatherly' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
