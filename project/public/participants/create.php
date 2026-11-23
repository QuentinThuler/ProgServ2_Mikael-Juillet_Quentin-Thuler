<?php
require_once __DIR__ . '/../../src/init.php';

use App\participant\ParticipantRequests;
use App\participant\ParticipantController;

use function App\services\escape;
use function App\services\verifyCsrfToken;

$errors = [];

try {
    $requests = new ParticipantRequests($pdo);
    $controller = new ParticipantController($requests);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken();

        $email = $_POST['email'] ?? '';

        $controller->create($email);

        header(
            'Location: validate.php?email=' . urlencode(trim($email))
        );
        exit;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $errors[] = "Une erreur interne s'est produite. Veuillez réessayer plus tard.";
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    $errors[] = "Une erreur inattendue s'est produite : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../../components/head.php' ?>

<body>
    <main>
        <?php require_once __DIR__ . '/../../components/header.php' ?>

        <h1>Participants</h1>

        <?php if (!empty($errors)): ?>

            <?php foreach ($errors as $error): ?>
                <p role="alert">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endforeach; ?>

        <?php else: ?>

            <!-- Default action actual file (/participants/create.php) -->
            <form method="POST" action="">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= escape($_SESSION['csrf_token'] ?? '') ?>">

                <div>
                    <label for="email"><?= escape(translate('Adresse e-mail')) ?></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="254"
                        placeholder="<?= escape(translate('Votre adresse email')) ?>"

                        required>
                </div>

                <button type="submit">Participer</button>
            </form>

        <?php endif; ?>

    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
