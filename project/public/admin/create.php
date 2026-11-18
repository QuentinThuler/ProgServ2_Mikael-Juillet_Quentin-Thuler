<?php
require_once __DIR__ . '/../../src/init.php';
require_once __DIR__ . '/../../src/Event/EventController.php';
require_once __DIR__ . '/../../src/Event/EventRequests.php';
require_once __DIR__ . '/../../src/functions.php';

$_SESSION['user_id'] ??= 1;

// Génération d'un jeton CSRF en session s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pageTitle = "Créer un Évènement";

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
    // 1. Vérification du jeton CSRF
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        http_response_code(403);
        die("Requête invalide (jeton CSRF manquant ou incorrect).");
    }

    // 2. Récupération sécurisée des entrées (vérification que ce sont bien des chaînes et non des tableaux)
    $title       = is_string($_POST['title'] ?? null) ? trim($_POST['title']) : '';
    $description = is_string($_POST['description'] ?? null) ? trim($_POST['description']) : '';
    $tagline     = is_string($_POST['tagline'] ?? null) ? trim($_POST['tagline']) : '';
    $address     = is_string($_POST['address'] ?? null) ? trim($_POST['address']) : '';
    $city        = is_string($_POST['city'] ?? null) ? trim($_POST['city']) : '';
    $room        = is_string($_POST['room'] ?? null) ? trim($_POST['room']) : '';
    $date_time   = is_string($_POST['date_time'] ?? null) ? trim($_POST['date_time']) : '';

    try {
        $imageFile = (isset($_FILES['image']) && is_array($_FILES['image'])) ? $_FILES['image'] : null;
        $userId = (int) $_SESSION['user_id'];

        $newEventId = $eventController->create([
            'title'       => $title,
            'description' => $description,
            'tagline'     => $tagline,
            'address'     => $address,
            'city'        => $city,
            'room'        => $room,
            'date_time'   => $date_time,
        ], $imageFile, $userId);

        // Régénération du jeton CSRF après succès
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: ./view-admin.php?id=' . (int) $newEventId);
        exit;
    } catch (InvalidArgumentException $e) {
        $errors[] = $e->getMessage();
    } catch (Throwable $e) {
        // Erreur technique (PDO, disque, etc.)
        error_log("Erreur création évènement : " . $e->getMessage());
        $errors[] = "Une erreur interne est survenue lors de l'enregistrement de l'évènement.";
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
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="./create.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>" />

            <label for="title">Titre*</label>
            <input type="text" id="title" name="title" value="<?= e($title) ?>" required maxlength="255" />

            <label for="description">Description*</label>
            <input type="text" id="description" name="description" value="<?= e($description) ?>" required maxlength="500" />

            <label for="tagline">Phrase d'accroche (max 80 caractères)*</label>
            <input type="text" id="tagline" name="tagline" value="<?= e($tagline) ?>" required maxlength="80" />

            <label for="address">Adresse*</label>
            <input type="text" id="address" name="address" value="<?= e($address) ?>" required minlength="5" maxlength="60" />

            <label for="city">Ville*</label>
            <input type="text" id="city" name="city" value="<?= e($city) ?>" required maxlength="40" />

            <label for="room">Salle</label>
            <input type="text" id="room" name="room" value="<?= e($room) ?>" maxlength="20" />

            <label for="date_time">Date et heure*</label>
            <input type="datetime-local" id="date_time" name="date_time" value="<?= e($date_time) ?>" required />

            <?php if (defined('IMAGE_UPLOAD_ENABLED') && IMAGE_UPLOAD_ENABLED): ?>
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/webp" />
            <?php endif; ?>

            <button type="submit">Ajouter l'évènement</button>
        </form>
    </main>

    <?php require __DIR__ . '/../../components/footer.php' ?>
</body>

</html>