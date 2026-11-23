<?php

require_once __DIR__ . '/../../src/init.php';

use App\pendingVerifications\PendingVerificationController;
use App\pendingVerifications\PendingVerificationRequests;

use function App\services\escape;
use function App\services\translate;
use function App\services\verifyCsrfToken;

$errors = [];
$pageTitle = escape(translate('S\'inscrire'));

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

        <h1> <?= escape(translate('Créez votre compte')) ?></h1>
        <p class="">
            <?= escape(translate('Démarrez de suite avec la solution
            la plus simple pour réserver et gérer
            les événements d\'une association !')) ?>
        </p>

        <?php foreach ($errors as $error): ?>
            <p class="error">
                <?= escape(translate($error)) ?>
            </p>
        <?php endforeach; ?>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= escape($_SESSION['csrf_token'] ?? '') ?>">

            <div>
                <label for="email"><?= escape(translate('Adresse e-mail')) ?></label>
                <input
                    placeholder="<?= escape(translate('Votre adresse email')) ?>"
                    type="email"
                    id="email"
                    name="email"
                    value="<?= escape($_POST['email'] ?? '') ?>"
                    required>
            </div>

            <div>
                <label for="name"><?= escape(translate('Nom d\'utilisateur')) ?></label>
                <input
                    placeholder="<?= escape(translate('Votre nom d\'utilisateur')) ?>"
                    type="text"
                    id="name"
                    name="name"
                    value="<?= escape($_POST['name'] ?? '') ?>"
                    required>
            </div>

            <div>
                <label for="password"><?= escape(translate('Mot de passe')) ?></label>
                <input
                    placeholder="<?= escape(translate('Votre mot de passe')) ?>"
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required>
            </div>

            <button type="submit"><?= escape(translate('Continuer')) ?></button>

        </form>

        <p><?= escape(translate('Vous avez déjà un compte ?')) ?> <a href="/account/login.php"><?= escape(translate('Se connecter')) ?></a></p>
    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
