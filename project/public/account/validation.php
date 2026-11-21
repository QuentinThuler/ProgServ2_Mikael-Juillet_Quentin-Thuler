<?php

require_once __DIR__ . '/../../src/init.php';

use App\pendingVerifications\PendingVerificationController;
use App\pendingVerifications\PendingVerificationRequests;

use function App\services\verifyCsrfToken;
use function App\services\escape;

$errors = [];

try {
    $pendingVerificationRequest = new PendingVerificationRequests($pdo);
    $pendingVerificationController = new PendingVerificationController($pendingVerificationRequest);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken();

        $pendingVerificationController->validate(
            $_SESSION['email_temp'] ?? null,
            $_POST['verification_token'] ?? null
        );

        header('Location: index.php?success=created');
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
    <title>Validation du compte</title>
</head>

<body>

    <h1>Validation du compte</h1>

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

        <?php if (!isset($_SESSION['email_temp'])): ?>
            <div>
                <label for="email">Adresse e-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= escape($_POST['email'] ?? '') ?>"
                    required>
            </div>
        <?php endif; ?>

        <div>
            <label for="Verification token">Token de vérification</label>
            <input
                type="text"
                id="verification_token"
                name="verification_token"
                length="6"
                value="<?= escape($_POST['verification_token'] ?? '') ?>"
                required>
        </div>

        <button type="submit">Créer mon compte</button>

    </form>

</body>

</html>
```
