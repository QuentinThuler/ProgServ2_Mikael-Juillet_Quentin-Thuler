<?php

require_once __DIR__ . '/../../src/user/User.php';
require_once __DIR__ . '/../../src/user/UserRequests.php';
require_once __DIR__ . '/../../src/user/UserController.php';
require_once __DIR__ . '/../../src/user/RoleRequests.php';
require_once __DIR__ . '/../../src/participants/ParticipantRequests.php';
require_once __DIR__ . '/../../src/init.php';

$userRequests = new UserRequests($pdo);
$participantRequests = new ParticipantRequests($pdo);
$roleRequests = new RoleRequests($pdo);

$userController = new UserController(
    $userRequests,
    $participantRequests,
    $roleRequests
);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$user = $userController->show($id);

if ($user === null) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verifyCsrfToken();

        $userController->delete($id);

        header('Location: index.php?success=deleted');
        exit;
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $error = 'Une erreur est survenue lors de la suppression.';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un utilisateur - Gatherly</title>
</head>

<body>

    <h1>Supprimer un utilisateur</h1>

    <p>
        Es-tu sûr de vouloir supprimer cet utilisateur ?
    </p>

    <p>
        Nom : <?= escape($user->getName()) ?>
    </p>

    <p>
        E-mail : <?= escape($user->getParticipant()->getEmail()) ?>
    </p>

    <?php if ($error !== null): ?>
        <p style="color: red;">
            <?= escape($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= escape($_SESSION['csrf_token']) ?>">

        <button type="submit">
            Oui, supprimer
        </button>

        <a href="index.php">
            Annuler
        </a>

    </form>

</body>

</html>
