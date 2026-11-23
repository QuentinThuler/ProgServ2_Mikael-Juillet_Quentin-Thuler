<?php
require_once __DIR__ . '/../../src/init.php';

use App\event\EventRequests;
use App\event\EventController;
use function App\services\escape;
use function App\services\translate;

// TODO : missing try catch, can be cleaned and translated

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
            <h1><?= escape($event->getTitle()) ?></h1>
            <p class="tagline"><strong><?= escape($event->getTagline()) ?></strong></p>

            <?php if ($hasValidImage): ?>
                <img src="<?= escape($imagePath) ?>" alt="<?= escape($event->getTitle()) ?>" />
            <?php endif; ?>

            <section class="event-info">
                <p><strong><?= escape(translate('Date et heure')) ?>:</strong> <?= escape($event->getDateTime()->format('d.m.Y - H:i')) ?></p>
                <p>
                    <strong><?= escape(translate('Lieu')) ?> :</strong>
                    <?= escape($event->getAddress()) ?>, <?= escape($event->getCity()) ?>
                    <?php if ($event->getRoom()): ?>
                        (<?= escape(translate('Salle')) ?> :<?= escape($event->getRoom()) ?>)
                    <?php endif; ?>
                </p>
            </section>

            <section class="event-description">
                <h2><?= escape(translate('Description')) ?></h2>
                <p><?= nl2br(escape($event->getDescription())) ?></p>
            </section>

            <?php if ($event->getCreatedAt()): ?>
                <p><small><?= escape(translate('Publié le')) ?><?= escape($event->getCreatedAt()->format('d.m.Y à H:i')) ?></small></p>
            <?php endif; ?>
        </article>
    </main>

    <?php require_once __DIR__ . '/../components/footer.php' ?>
</body>

</html>
