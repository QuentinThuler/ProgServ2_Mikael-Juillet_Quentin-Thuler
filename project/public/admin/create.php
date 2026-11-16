<?php
require_once __DIR__ . '/../../src/init.php';
require_once __DIR__ . '/../../src/Event/EventController.php';
require_once __DIR__ . '/../../src/Event/EventRequests.php';

/* requireAdmin(); */
$_SESSION['user_id'] ??= 1; // Pour le test si besoin

$pageTitle = "Créer un Évènement";

// Initialisation des dépendances orientées objet
$eventRequests = new EventRequests($pdo);
$eventController = new EventController($eventRequests);

// Valeurs par défaut du formulaire
$title = '';
$description = '';
$tagline = '';
$address = '';
$city = '';
$room = '';
$date_time = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sauvegarde des saisies utilisateur pour les réinjecter en cas d'erreur
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tagline = trim($_POST['tagline'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $room = trim($_POST['room'] ?? '');
    $date_time = trim($_POST['date_time'] ?? '');

    try {
        $imageFile = $_FILES['image'] ?? null;
        $newEventId = $eventController->create($_POST, $imageFile, $_SESSION['user_id']);

        header('Location: ./view-admin.php?id=' . $newEventId);
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php' ?>

<body>

    <?php require __DIR__ . '/../../components/header.php' ?>

    <main>
        <?php if (!empty($errors)): ?>
            <p style="color: red;">Le formulaire contient des erreurs :</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="./create.php" method="POST" enctype="multipart/form-data">
            <label for="title">Titre*</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required maxlength="255" />

            <label for="description">Description*</label>
            <input type="text" id="description" name="description" value="<?= htmlspecialchars($description) ?>" required maxlength="500" />

            <label for="tagline">Phrase d'accroche (max 80 caractères)*</label>
            <input type="text" id="tagline" name="tagline" value="<?= htmlspecialchars($tagline) ?>" required maxlength="80" />

            <label for="address">Adresse*</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($address) ?>" required minlength="5" maxlength="60" />

            <label for="city">Ville*</label>
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($city) ?>" required maxlength="40" />

            <label for="room">Salle</label>
            <input type="text" id="room" name="room" value="<?= htmlspecialchars($room) ?>" maxlength="20" />

            <label for="date_time">Date et heure*</label>
            <input type="datetime-local" id="date_time" name="date_time" value="<?= htmlspecialchars($date_time) ?>" required />

            <?php if (defined('IMAGE_UPLOAD_ENABLED') && IMAGE_UPLOAD_ENABLED): ?>
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg" />
            <?php endif; ?>

            <button type="submit">Ajouter l'évènement</button>
        </form>
    </main>

    <?php require __DIR__ . '/../../components/footer.php' ?>
</body>

</html>