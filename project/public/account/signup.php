<?php

require_once __DIR__ . '/../../src/init.php';

use App\pendingVerifications\PendingVerificationController;
use App\pendingVerifications\PendingVerificationRequests;

use function App\services\escape;
use function App\services\verifyCsrfToken;

$errors = [];

try {
    $pendingVerificationRequest = new PendingVerificationRequests($pdo);
    $pendingVerificationController = new PendingVerificationController($pendingVerificationRequest);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken();

        $payload = [
            'name' => $_POST['name'] ?? null,
            'password' => $_POST['password'] ?? null
        ];

        $pendingVerificationController->create(
            email: $_POST['email'] ?? '',
            purpose: 'account_creation',
            payload: json_encode($payload)
        );

        header('Location: validation.php');
        exit;
    }
} catch (InvalidArgumentException | RuntimeException $e) {
    $errors[] = $e->getMessage();
} catch (Throwable $e) {
    error_log($e->getMessage());
    $errors[] = 'Une erreur interne est survenue. Veuillez réessayer.';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
</head>

<body>

    <h1>Créer un compte</h1>

    <?php foreach ($errors as $error): ?>
        <p class="error">
            <?= escape($error) ?>
        </p>
    <?php endforeach; ?>

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= escape($_SESSION['csrf_token'] ?? '') ?>">

        <div>
            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= escape($_POST['email'] ?? '') ?>"
                required>
        </div>

        <div>
            <label for="name">Nom</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= escape($_POST['name'] ?? '') ?>"
                required>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required>
        </div>

        <button type="submit">Créer mon compte</button>

    </form>

</body>

</html>
```
