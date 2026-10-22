<?php
require_once __DIR__ . '/../src/init.php';

$stmt = $pdo->query(
    "SELECT id, title, city, date_time, tagline
     FROM events
     WHERE date_time >= NOW()
     ORDER BY date_time ASC"
);
$events = $stmt->fetchAll();

$pageTitle = "Accueil";
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../components/head.php' ?>

<body>
    <main>
        <?php require_once __DIR__ . '/../components/header.php' ?>

        <h1>ProgServ2 Course PHP Template</h1>

        <p>Bienvenue dans votre projet PHP pour le cours ProgServ2 à la HEIG-VD !</p>

        <h2>Résultats de la base de données :</h2>

        <ul>
            <?php if (empty($events)): ?>
                <p>Aucun évènement à venir pour le moment.</p>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <article class="event-card">
                        <h3><?= htmlspecialchars($event['title']) ?> - <?= htmlspecialchars($event['city']) ?></h3>
                        <p><?= date('d.m.Y - H:i', strtotime($event['date_time'])) ?></p>
                        <p><?= htmlspecialchars($event['tagline']) ?></p>
                        <a href="/event.php?id=<?= (int) $event['id'] ?>">Détails de l'évènement</a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </main>
    <?php require_once __DIR__ . '/../components/footer.php' ?>
</body>

</html>
