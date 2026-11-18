<?php

require_once __DIR__ . '/../../src/init.php';

use App\Participant\ParticipantRequests;
use App\Participant\ParticipantController;

$requests = new ParticipantRequests($pdo);
$controller = new ParticipantController($requests);

$email = $_GET['email'] ?? $_POST['email'] ?? '';
$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller->validateToken(
            $email,
            $_POST['token'] ?? ''
        );

        $success = true;

        //TODO : Rediriger vers une page. 
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $error = 'Une erreur interne est survenue.';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Vérification de l'adresse e-mail</title>
</head>

<body>

    <h1>Vérifier votre adresse e-mail</h1>

    <?php if ($success): ?>
        <p>Votre adresse e-mail a été confirmée avec succès !</p>
    <?php else: ?>

        <?php if ($error !== null): ?>
            <p role="alert">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <input
                type="hidden"
                name="email"
                value="<?= htmlspecialchars($email) ?>">

            <label for="token">Code de vérification</label>

            <input
                type="text"
                id="token"
                name="token"
                maxlength="6"
                minlength="6"
                required>

            <button type="submit">
                Confirmer mon adresse e-mail
            </button>
        </form>

    <?php endif; ?>

</body>

</html>
