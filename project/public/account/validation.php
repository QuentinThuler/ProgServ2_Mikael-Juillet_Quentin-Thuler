<?php

require_once __DIR__ . '/../../src/init.php';

use App\pendingVerifications\PendingVerificationController;
use App\pendingVerifications\PendingVerificationRequests;

use function App\services\verifyCsrfToken;
use function App\services\escape;

$errors = [];
$pageTitle = "Validation du compte";

try {
    $pendingVerificationRequest = new PendingVerificationRequests($pdo);
    $pendingVerificationController = new PendingVerificationController($pendingVerificationRequest);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken();

        $pendingVerificationController->validate(
            $_SESSION['email_temp'] ?? null,
            $_POST['verification_token'] ?? null
        );

        header('Location: index.php');
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

        <h1>Vérifiez votre email</h1>

        <p>Nous vous avons envoyé un lien de confirmation à l’adresse <?= escape($_POST['email'] ?? '') ?>.
            Veuillez le suivre pour confirmer votre inscription.</p>

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
                <label for="Verification token">Token de vérification</label>
                <input
                    type="text"
                    id="verification_token"
                    name="verification_token"
                    length="6"
                    value="<?= escape($_POST['verification_token'] ?? '') ?>"
                    required
                    placeholder="Code à 6 charactères">
            </div>

            <button type="submit">Valider</button>

        </form>

        <p>Vous n’avez pas reçu de code ? <a href="/account/revalidate">Renvoyer le code.</a></p>
    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
