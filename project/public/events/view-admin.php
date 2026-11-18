<?php
require_once __DIR__ . '/../../src/init.php';

use App\Event\EventRequests;
use App\Event\EventController;


// Contrôle d'accès administrateur (à décommenter dès que votre fonction est prête)
/* requireAdmin(); */

// Validation stricte de l'identifiant passé dans l'URL (?id=...) en entier positif
$eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$eventId) {
    http_response_code(400);
    die("Identifiant d'évènement invalide.");
}

// Initialisation des dépendances orientées objet
$eventRequests = new EventRequests($pdo);
$eventController = new EventController($eventRequests);

// Récupération de l'objet Event via le contrôleur
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

<?php require_once __DIR__ . '/../../components/head.php' ?>

<body>

    <?php require_once __DIR__ . '/../../components/header.php' ?>

    <main>
        <article class="event-detail">
            <h1><?= htmlspecialchars($event->getTitle(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
            <p class="tagline"><strong><?= htmlspecialchars($event->getTagline(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong></p>

            <?php if ($hasValidImage): ?>
                <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="<?= htmlspecialchars($event->getTitle(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" />
            <?php endif; ?>

            <section class="event-info">
                <p><strong>Date et heure :</strong> <?= htmlspecialchars($event->getDateTime()->format('d.m.Y - H:i'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                <p>
                    <strong>Lieu :</strong>
                    <?= htmlspecialchars($event->getAddress(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>, <?= htmlspecialchars($event->getCity(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    <?php if ($event->getRoom()): ?>
                        (Salle : <?= htmlspecialchars($event->getRoom(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>)
                    <?php endif; ?>
                </p>
            </section>

            <section class="event-description">
                <h2>Description</h2>
                <p><?= nl2br(htmlspecialchars($event->getDescription(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></p>
            </section>

            <?php if ($event->getCreatedAt()): ?>
                <p><small>Publié le <?= htmlspecialchars($event->getCreatedAt()->format('d.m.Y à H:i'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small></p>
            <?php endif; ?>
        </article>
    </main>

    <?php require_once __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
