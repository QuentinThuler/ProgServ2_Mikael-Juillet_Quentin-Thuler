<?php
require_once __DIR__ . '/../../src/init.php';

use App\participant\ParticipantRequests;
use App\role\RoleRequests;
use App\user\UserRequests;
use App\user\UserController;
use function App\services\escape;
use function App\services\translate;

$errors = [];
$user = null;
$pageTitle = escape(translate('Mon compte'));

if (!isset($_SESSION['user_id'])) {
    header('Location: /account/login.php');
    exit;
}

try {
    $controller = new UserController(
        new UserRequests($pdo),
        new ParticipantRequests($pdo),
        new RoleRequests($pdo)
    );

    $user = $controller->show($_SESSION['user_id']);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $errors[] = "Une erreur interne s'est produite. Veuillez réessayer.";
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    $errors[] = "Une erreur inattendue s'est produite.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../../components/head.php'; ?>

<body>

    <main>
        <?php require_once __DIR__ . '/../../components/header.php'; ?>

        <h1>Votre compte</h1>

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

                <button><?= escape(translate('Modification du profil')) ?></button>


                <?php if ($user->getDescription()): ?>
                    <div>
                        <h3><?= escape(translate('Description')) ?></h3>
                        <p><?= escape($user->getDescription()) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($user->getPhone()): ?>
                    <div>
                        <h3><?= escape(translate('Numéro de téléphone')) ?></strong>
                            <p><?= escape($user->getPhone()) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($user->getLinkedin()): ?>
                    <div>
                        <h3><?= escape(translate('LinkedIn')) ?></h3>
                        <a
                            href="<?= escape($user->getLinkedin()) ?>"
                            target="_blank"
                            rel="noopener noreferrer">
                            <?= escape($user->getLinkedin()) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </section>

            <button><?= escape(translate('Se déconnecter')) ?></button>

            <button><?= escape(translate('Effacer mes données')) ?></button>

        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/../../components/footer.php'; ?>

</body>

</html>
