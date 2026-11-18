<?php

require_once __DIR__ . '/../../src/init.php';

use App\participant\ParticipantRequests;
use App\role\RoleRequests;
use App\user\UserRequests;
use App\user\UserController;

$errors = [];

try {
    $participantRequests = new ParticipantRequests($pdo);
    $userRequests = new UserRequests($pdo);
    $roleRequests = new RoleRequests($pdo);
    $userController = new UserController($userRequests, $participantRequests, $roleRequests);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken();

        $userController->create(
            $_POST['email'] ?? '',
            $_POST['name'] ?? '',
            $_POST['password'] ?? '',
            $_POST['description'] ?? null,
            null,
            $_POST['linkedin'] ?? null,
            $_POST['phone'] ?? null
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
    <title>Créer un compte</title>
</head>

<body>

    <h1>Créer un compte</h1>

    <?php foreach ($errors as $error): ?>
        <p class="error">
            <?= e($error) ?>
        </p>
    <?php endforeach; ?>

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= e($_SESSION['csrf_token'] ?? '') ?>">

        <div>
            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($_POST['email'] ?? '') ?>"
                required>
        </div>

        <div>
            <label for="name">Nom</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= e($_POST['name'] ?? '') ?>"
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
