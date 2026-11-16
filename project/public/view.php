<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/../src/Event/EventController.php';
require_once __DIR__ . '/../src/Event/EventRequests.php';

$eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$eventId || $eventId <= 0) {
    http_response_code(400);
    die("Identifiant d'évènement invalide.");
}

$eventRequests = new EventRequests($pdo);
$eventController = new EventController($eventRequests);

$event = $eventController->show($eventId);

if (!$event) {
    http_response_code(404);
    die("Évènement introuvable.");
}

$pageTitle = $event->getTitle();
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../components/head.php' ?>

<body>

    <?php require_once __DIR__ . '/../components/header.php' ?>

    <main>
        <article class="event-detail">
            <h1><?= htmlspecialchars($event->getTitle()) ?></h1>
            <p class="tagline"><strong><?= htmlspecialchars($event->getTagline()) ?></strong></p>

            <?php if ($event->getImagePath()): ?>
                <img src="<?= htmlspecialchars($event->getImagePath()) ?>" alt="<?= htmlspecialchars($event->getTitle()) ?>" />
            <?php endif; ?>

            <section class="event-info">
                <p><strong>Date et heure :</strong> <?= $event->getDateTime()->format('d.m.Y - H:i') ?></p>
                <p>
                    <strong>Lieu :</strong>
                    <?= htmlspecialchars($event->getAddress()) ?>, <?= htmlspecialchars($event->getCity()) ?>
                    <?php if ($event->getRoom()): ?>
                        (Salle : <?= htmlspecialchars($event->getRoom()) ?>)
                    <?php endif; ?>
                </p>
            </section>

            <section class="event-description">
                <h2>Description</h2>
                <p><?= nl2br(htmlspecialchars($event->getDescription())) ?></p>
            </section>

            <?php if ($event->getCreatedAt()): ?>
                <p><small>Publié le <?= $event->getCreatedAt()->format('d.m.Y à H:i') ?></small></p>
            <?php endif; ?>
        </article>
    </main>

    <?php require_once __DIR__ . '/../components/footer.php' ?>
</body>

</html>