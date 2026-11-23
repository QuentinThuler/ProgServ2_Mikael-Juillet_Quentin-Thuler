<?php

require_once __DIR__ . '/../../src/init.php';

use App\participant\ParticipantRequests;
use App\role\RoleRequests;
use App\user\UserRequests;
use App\user\UserController;
use App\user\User;

use function App\services\escape;
use function App\services\translate;
use function App\services\verifyCsrfToken;

$pageTitle = escape(translate('Mise à jour du compte'));

if (!isset($_SESSION['user_id'])) {
    header('Location: /account/login.php');
    exit;
}

$userRequests = new UserRequests($pdo);
$participantRequests = new ParticipantRequests($pdo);
$roleRequests = new RoleRequests($pdo);

$userController = new UserController(
    $userRequests,
    $participantRequests,
    $roleRequests
);

$errors = [];

try {
    $user = $userController->show($_SESSION['user_id']);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $errors[] = "Une erreur interne s'est produite. Veuillez réessayer.";
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    $errors[] = "Une erreur inattendue s'est produite.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verifyCsrfToken();

        $userUpdated = new User(
            $user->getParticipant(),
            $user->getName(),
            $user->getPassword(),
            $user->getRole(),
            $_POST['description'] ?? '',
            $_POST['image'] ?? '',
            $_POST['linkedin'] ?? '',
            $_POST['phone'] ?? ''
        );
        $userUpdated->setId($user->getId());

        $userController->update($userUpdated);

        header('Location: /user/show.php');
        exit;
    } catch (Throwable $e) {
        error_log($e->getMessage());

        $errors[] = $e instanceof InvalidArgumentException
            ? $e->getMessage()
            : 'Une erreur est survenue lors de la modification.';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../../components/head.php' ?>

<body>
    <main>
        <?php require_once __DIR__ . '/../../components/header.php' ?>

        <h1>Modifier votre compte</h1>

        <?php if (!empty($errors)): ?>

            <?php foreach ($errors as $error): ?>
                <p class="error">
                    <?= escape(translate($error)) ?>
                </p>
            <?php endforeach; ?>

        <?php elseif (empty($user)): ?>

            <p><?= escape(translate('Utilisateur introuvable.')) ?></p>

        <?php else: ?>

            <section class="profile">

                <?php if ($user->getImage()): ?>
                    <img
                        src="<?= escape($user->getImage()) ?>"
                        alt="<?= escape(translate('Photo de profil')) ?>"
                        width="150">
                <?php endif; ?>

                <div>
                    <h2><?= escape($user->getName()) ?></h2>
                    <p><?= escape($user->getEmail()) ?></p>
                </div>

                <form method="POST">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= escape($_SESSION['csrf_token']) ?>">

                    <label for="description"><?= escape(translate('Description')) ?> :</label>
                    <textarea
                        name="description"
                        id="description"><?= escape($user->getDescription()) ?></textarea>

                    <label for="image"><?= escape(translate('Image')) ?> :</label>
                    <input
                        type="text"
                        name="image"
                        id="image"
                        value="<?= escape($user->getImage()) ?>">

                    <label for="linkedin"><?= escape(translate('LinkedIn')) ?> :</label>
                    <input
                        type="url"
                        name="linkedin"
                        id="linkedin"
                        value="<?= escape($user->getLinkedin()) ?>">

                    <label for="phone"><?= escape(translate('Numéro de téléphone')) ?> :</label>
                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        value="<?= escape($user->getPhone()) ?>">

                    <button type="submit"><?= escape(translate('Enregistrer les modifications')) ?></button>

                </form>
            <?php endif; ?>
    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
