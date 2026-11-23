<?php
require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/../src/Event/EventController.php';
require_once __DIR__ . '/../src/Event/EventRequests.php';

$eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$eventId) {
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
$imagePath = $event->getImagePath();
$hasValidImage = $imagePath !== null && str_starts_with($imagePath, '/uploads/events/');
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../components/head.php' ?>

<body>

    <?php require_once __DIR__ . '/../components/header.php' ?>

    <main>
        <article class="event-detail">
            <h1><?= e($event->getTitle()) ?></h1>
            <p class="tagline"><strong><?= e($event->getTagline()) ?></strong></p>

            <?php if ($hasValidImage): ?>
                <img src="<?= e($imagePath) ?>" alt="<?= e($event->getTitle()) ?>" />
            <?php endif; ?>

            <section class="event-info">
                <p><strong><?= e(t('Date et heure : ')) ?></strong> <?= e($event->getDateTime()->format('d.m.Y - H:i')) ?></p>
                <p>
                    <strong><?= e(t('Lieu :')) ?></strong>
                    <?= e($event->getAddress()) ?>, <?= e($event->getCity()) ?>
                    <?php if ($event->getRoom()): ?>
                        <?= e(t('(Salle : ')) ?><?= e($event->getRoom()) ?>)
                    <?php endif; ?>
                </p>
            </section>

            <section class="event-description">
                <h2><?= e(t('Description')) ?></h2>
                <p><?= nl2br(e($event->getDescription())) ?></p>
            </section>

            <?php if ($event->getCreatedAt()): ?>
                <p><small><?= e(t('Publié le ')) ?><?= e($event->getCreatedAt()->format('d.m.Y à H:i')) ?></small></p>
            <?php endif; ?>
        </article>
    </main>

    <?php require_once __DIR__ . '/../components/footer.php' ?>
</body>

</html>
