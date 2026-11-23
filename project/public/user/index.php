<?php
require_once __DIR__ . '/../../src/init.php';

use App\participant\ParticipantRequests;
use App\role\RoleRequests;
use App\user\UserRequests;
use App\user\UserController;

use function App\services\escape;
use function App\services\translate;

$errors = [];
$pageTitle = escape(translate('Liste des utilisateurs'));

try {
    $requests = new UserRequests($pdo);
    $controller = new UserController($requests, new ParticipantRequests($pdo), new RoleRequests($pdo));

    $users = $controller->index();
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

        <h1><?= escape(translate('Liste des utilisateurs')) ?></h1>

        <?php if (!empty($errors)): ?>

            <?php foreach ($errors as $error): ?>
                <p class="error">
                    <?= escape(translate($error)) ?>
                </p>
            <?php endforeach; ?>

        <?php elseif (empty($users)): ?>

            <p><?= escape(translate('Aucun utilisateur pour le moment.')) ?></p>

        <?php else: ?>

            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <?= escape($user->getEmail()) ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endif; ?>

    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
