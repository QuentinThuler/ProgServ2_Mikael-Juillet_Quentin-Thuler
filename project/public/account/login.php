<?php

require_once __DIR__ . '/../../src/init.php';

use App\pendingVerifications\PendingVerificationController;
use App\pendingVerifications\PendingVerificationRequests;

use function App\services\escape;
use function App\services\verifyCsrfToken;

$errors = [];
$pageTitle = "Connection";

// TODO : Implement a proper login controller and requests class to handle user authentication.
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
            payload: $payload
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

<?php require_once __DIR__ . '/../../components/head.php' ?>

<body>
    <main>
        <?php require_once __DIR__ . '/../../components/header.php' ?>

        <img src="/images/logo/big-gratherly.svg" alt="Logo de Gratherly avec une illustration de personnes qui se font un câlin.">

        <h1>Connectez-vous à votre espace</h1>

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
                    placeholder="Votre adresse email"
                    type="email"
                    id="email"
                    name="email"
                    value="<?= escape($_POST['email'] ?? '') ?>"
                    required>
            </div>

            <div>
                <label for="password">Mot de passe</label>
                <input
                    placeholder="Votre mot de passe"
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required>
            </div>

            <button type="submit">Se connecter</button>

        </form>

        <p>Vous n’avez pas de compte ? <a href="/account/signup.php">S'inscrire</a></p>

    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
