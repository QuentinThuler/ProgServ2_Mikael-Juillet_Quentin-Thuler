<?php

require_once __DIR__ . '/../../src/init.php';

use App\Participant\ParticipantRequests;
use App\Role\RoleRequests;
use App\User\UserRequests;
use App\User\UserController;
use App\User\User;

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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verifyCsrfToken();

        // On conserve le participant déjà lié à cet utilisateur.
        $updatedUser = new User(
            $user->getParticipant(),
            trim($_POST['name'] ?? ''),
            $_POST['password'] ?? '',
            (int) ($_POST['role_id'] ?? 0),
            trim($_POST['description'] ?? '') ?: null,
            trim($_POST['image'] ?? '') ?: null,
            trim($_POST['linkedin'] ?? '') ?: null,
            trim($_POST['phone'] ?? '') ?: null
        );

        $updatedUser->setId($user->getId());

        $userController->update($updatedUser);

        header('Location: index.php?success=updated');
        exit;
    } catch (Throwable $e) {
        error_log($e->getMessage());

        $errors[] = $e instanceof InvalidArgumentException
            ? $e->getMessage()
            : 'Une erreur est survenue lors de la modification.';
    }
}

$name = $_POST['name'] ?? $user->getName();
$roleId = $_POST['role_id'] ?? $user->getRoleId();
$description = $_POST['description'] ?? $user->getDescription();
$image = $_POST['image'] ?? $user->getImage();
$linkedin = $_POST['linkedin'] ?? $user->getLinkedin();
$phone = $_POST['phone'] ?? $user->getPhone();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur - Gatherly</title>
</head>

<body>

    <h1>Modifier l'utilisateur</h1>

    <a href="index.php">Retour à la liste</a>

    <p>
        E-mail du participant :
        <?= e($user->getParticipant()->getEmail()) ?>
    </p>

    <?php foreach ($errors as $error): ?>
        <p style="color: red;">
            <?= e($error) ?>
        </p>
    <?php endforeach; ?>

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= e($_SESSION['csrf_token']) ?>">

        <label for="name">Nom :</label>
        <input
            type="text"
            name="name"
            id="name"
            value="<?= e($name) ?>"
            required>

        <br><br>

        <label for="password">
            Nouveau mot de passe (laisser vide pour conserver l'ancien) :
        </label>

        <input
            type="password"
            name="password"
            id="password"
            minlength="8">

        <br><br>

        <label for="role_id">ID du rôle :</label>
        <input
            type="number"
            name="role_id"
            id="role_id"
            min="1"
            value="<?= e((string) $roleId) ?>"
            required>

        <br><br>

        <label for="description">Description :</label>
        <textarea
            name="description"
            id="description"><?= e($description) ?></textarea>

        <br><br>

        <label for="image">Image :</label>
        <input
            type="text"
            name="image"
            id="image"
            value="<?= e($image) ?>">

        <br><br>

        <label for="linkedin">LinkedIn :</label>
        <input
            type="url"
            name="linkedin"
            id="linkedin"
            value="<?= e($linkedin) ?>">

        <br><br>

        <label for="phone">Téléphone :</label>
        <input
            type="tel"
            name="phone"
            id="phone"
            value="<?= e($phone) ?>">

        <br><br>

        <button type="submit">Enregistrer les modifications</button>

    </form>

</body>

</html>
