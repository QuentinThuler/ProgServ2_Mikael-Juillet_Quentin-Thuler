<?php
require_once __DIR__ . '/../src/init.php';

use App\event\EventRequests;
use App\event\EventController;

use function App\services\escape;

$eventRequests = new EventRequests($pdo);
$eventController = new EventController($eventRequests);

$events = $eventController->upcoming();

$pageTitle = "Accueil";
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../components/head.php' ?>

<body id="top">
    <main>
        <?php require_once __DIR__ . '/../components/header.php' ?>

        <h1>L'association</h1>

        <p>Cette association, basée à Yverdon-les-Bains, vise à faire la promotion de la photographie auprès de jeunes étudiants.</p>

        <h2>Évènements à venir</h2>

        <?php if (empty($events)): ?>
            <p>Aucun évènement à venir pour le moment.</p>
        <?php else: ?>
            <ul id="events-list">
                <?php foreach ($events as $index => $event): ?>
                    <li <?= $index >= 8 ? 'hidden' : '' ?>>
                        <article class="event-card">
                            <h3><?= escape($event->getTitle()) ?> - <?= escape($event->getCity()) ?></h3>
                            <p><?= escape($event->getDateTime()->format('d.m.Y - H:i')) ?></p>
                            <p><?= escape($event->getTagline()) ?></p>
                            <a href="./view.php?id=<?= (int) $event->getId() ?>">Détails de l'évènement</a>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if (count($events) > 8): ?>
                <button type="button" id="load-more-btn">Afficher plus</button>
            <?php endif; ?>
        <?php endif; ?>

        <p>
            <a href="#top" id="back-to-top-btn">Haut de la page</a>
        </p>
    </main>

    <?php require_once __DIR__ . '/../components/footer.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadMoreBtn = document.getElementById('load-more-btn');
            const batchSize = 8;

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', () => {
                    const hiddenItems = document.querySelectorAll('#events-list > li[hidden]');

                    for (let i = 0; i < batchSize && i < hiddenItems.length; i++) {
                        hiddenItems[i].removeAttribute('hidden');
                    }

                    if (document.querySelectorAll('#events-list > li[hidden]').length === 0) {
                        loadMoreBtn.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>

</html>
