<?php

require_once __DIR__ . '/../../src/db/database.php';
require_once __DIR__ . '/../../src/participants/ParticipantRequests.php';
require_once __DIR__ . '/../../src/participants/ParticipantController.php';

$pdo = Database::getInstance()->getConnection();

$requests = new ParticipantRequests($pdo);
$controller = new ParticipantController($requests);

$participants = $controller->index();
?>

<h1>Participants</h1>

<ul>
    <?php foreach ($participants as $participant): ?>
        <li>
            <?= htmlspecialchars($participant->getEmail()) ?>
        </li>
    <?php endforeach; ?>
</ul>
