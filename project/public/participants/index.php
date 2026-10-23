<?php

require_once __DIR__ . '/../../src/db/database.php';
require_once __DIR__ . '/../../src/participants/Participant.php';
require_once __DIR__ . '/../../src/participants/ParticipantRequests.php';
require_once __DIR__ . '/../../src/participants/ParticipantController.php';

$errors = [];

try {
    $pdo = Database::getInstance()->getConnection();

    $requests = new ParticipantRequests($pdo);
    $controller = new ParticipantController($requests);

    $participants = $controller->index();
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $errors[] = "Une erreur interne s'est produite. Veuillez réessayer plus tard.";
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    $errors[] = "Une erreur inattendue s'est produite : " . $e->getMessage();
}

?>

<h1>Participants</h1>

<?php if (!empty($errors)): ?>

    <?php foreach ($errors as $error): ?>
        <p role="alert">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endforeach; ?>

<?php elseif (empty($participants)): ?>

    <p>Aucun participant pour le moment.</p>

<?php else: ?>

    <ul>
        <?php foreach ($participants as $participant): ?>
            <li>
                <?= htmlspecialchars($participant->getEmail()) ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>
