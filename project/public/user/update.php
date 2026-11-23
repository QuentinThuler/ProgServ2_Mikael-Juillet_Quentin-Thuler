<?php

require_once __DIR__ . '/../../src/init.php';

use App\participant\ParticipantRequests;
use App\role\RoleRequests;
use App\user\UserRequests;
use App\user\UserController;
use App\user\User;

use function App\services\escape;
use function App\services\verifyCsrfToken;

$userRequests = new UserRequests($pdo);
$participantRequests = new ParticipantRequests($pdo);
$roleRequests = new RoleRequests($pdo);

$userController = new UserController(
    $userRequests,
    $participantRequests,
    $roleRequests
);

$pageTitle = "Mise à jour du compte";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /index.php');
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

        $updatedUser = new User(
            $user->getParticipant(),
            trim($_POST['name'] ?? ''),
            $_POST['password'] ?? '',
            $user->getRole(),
            trim($_POST['description'] ?? '') ?: null,
            trim($_POST['image'] ?? '') ?: null,
            trim($_POST['linkedin'] ?? '') ?: null,
            trim($_POST['phone'] ?? '') ?: null
        );

        $updatedUser->setId($user->getId());

        $userController->update($updatedUser);

        header('Location: /index.php');
        exit;
    } catch (Throwable $e) {
        error_log($e->getMessage());

        $errors[] = $e instanceof InvalidArgumentException
            ? $e->getMessage()
            : 'Une erreur est survenue lors de la modification.';
    }
}

$name = $_POST['name'] ?? $user->getName();
$description = $_POST['description'] ?? $user->getDescription();
$image = $_POST['image'] ?? $user->getImage();
$linkedin = $_POST['linkedin'] ?? $user->getLinkedin();
$phone = $_POST['phone'] ?? $user->getPhone();

?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../../components/head.php' ?>

<body>
    <main>
        <?php require_once __DIR__ . '/../../components/header.php' ?>

        <h1>Modifier l'utilisateur</h1>

        <a href="index.php">Retour à la liste</a>

        <p>
            E-mail du participant :
            <?= escape($user->getParticipant()->getEmail()) ?>
        </p>

        <?php foreach ($errors as $error): ?>
            <p style="color: red;">
                <?= escape($error) ?>
            </p>
        <?php endforeach; ?>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= escape($_SESSION['csrf_token']) ?>">

            <label for="name">Nom :</label>
            <input
                type="text"
                name="name"
                id="name"
                value="<?= escape($name) ?>"
                required>

            <label for="password">
                Nouveau mot de passe (laisser vide pour conserver l'ancien) :
            </label>

            <input
                type="password"
                name="password"
                id="password"
                minlength="8">

            <label for="description">Description :</label>
            <textarea
                name="description"
                id="description"><?= escape($description) ?></textarea>

            <label for="image">Image :</label>
            <input
                type="text"
                name="image"
                id="image"
                value="<?= escape($image) ?>">

            <label for="linkedin">LinkedIn :</label>
            <input
                type="url"
                name="linkedin"
                id="linkedin"
                value="<?= escape($linkedin) ?>">

            <label for="phone">Téléphone :</label>
            <input
                type="tel"
                name="phone"
                id="phone"
                value="<?= escape($phone) ?>">

            <button type="submit">Enregistrer les modifications</button>

        </form>
    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
