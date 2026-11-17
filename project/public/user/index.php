<?php
require_once __DIR__ . '/../../src/user/User.php';
require_once __DIR__ . '/../../src/user/UserRequests.php';
require_once __DIR__ . '/../../src/user/UserController.php';
require_once __DIR__ . '/../../src/user/RoleRequests.php';
require_once __DIR__ . '/../../src/participants/ParticipantRequests.php';

$errors = [];

try {
    require_once __DIR__ . '/../../src/init.php';
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

        <h1>users</h1>

        <?php if (!empty($errors)): ?>

            <?php foreach ($errors as $error): ?>
                <p role="alert">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endforeach; ?>

        <?php elseif (empty($users)): ?>

            <p>Aucun user pour le moment.</p>

        <?php else: ?>

            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <?= htmlspecialchars($user->getEmail()) ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endif; ?>

    </main>
    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
