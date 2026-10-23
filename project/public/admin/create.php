<?php
require_once __DIR__ . '/../../src/init.php';
/* requireAdmin(); */
/* $_SESSION['user_id'] ??= 1; */

$pageTitle = "Créer un Évènement";

// Définition des valeurs par défaut de l'évènement
$title = $_POST["title"] ?? '';
$description = $_POST["description"] ?? '';
$tagline = $_POST["tagline"] ?? '';
$address = $_POST["address"] ?? '';
$city = $_POST["city"] ?? '';
$room = $_POST["room"] ?? '';
$date_time = $_POST["date_time"] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $imageFile = IMAGE_UPLOAD_ENABLED ? ($_FILES['image'] ?? null) : null;

    // Validation de l'évènement
    $errors = validateEvent(
        $title,
        $description,
        $tagline,
        $address,
        $city,
        $room,
        $date_time,
        $imageFile,
    );

    $imagePath = null;
    if (empty($errors) && $imageFile !== null && $imageFile['error'] === UPLOAD_ERR_OK) {
        $imagePath = saveEventImage($imageFile);
        if ($imagePath === null) {
            $errors[] = "Impossible d'enregistrer l'image.";
        }
    }

    if (empty($errors)) {
        $newEventId = addEvent(
            $pdo,
            $title,
            $description,
            $tagline,
            $address,
            $city,
            $room ?: null,
            (new DateTime($date_time))->format('Y-m-d H:i:s'),
            $imagePath,
            $_SESSION['user_id']
        );

        if ($newEventId !== null) {
            header('Location: ./view.php?id=' . $newEventId);
            exit;
        }

        $errors[] = "Une erreur est survenue lors de l'ajout de l'évènement.";
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php' ?>

<body>

    <?php require __DIR__ . '/../../components/header.php' ?>

    <main>
        <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
            <?php if (!empty($errors)) { ?>
                <p style="color: red;">Le formulaire contient des erreurs :</p>
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?= $error ?></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>
        <form action="./create.php" method="POST" enctype="multipart/form-data">
            <label for="title">Titre*</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars($title) ?>"
                required
                maxlength="255" />

            <label for="">Description*</label>
            <input
                type="text"
                id="description"
                name="description"
                value="<?= htmlspecialchars($description) ?>"
                required
                maxlength="500" />

            <label for="">Phrase d'accroche (max 80 caractères)*</label>
            <input
                type="text"
                id="tagline"
                name="tagline"
                value="<?= htmlspecialchars($tagline) ?>"
                required
                maxlength="80" />

            <label for="">Adresse*</label>
            <input
                type="text"
                id="address"
                name="address"
                value="<?= htmlspecialchars($address) ?>"
                required
                minlength="5"
                maxlength="60" />

            <label for="">Ville*</label>
            <input
                type="text"
                id="city"
                name="city"
                value="<?= htmlspecialchars($city) ?>"
                required
                maxlength="40" />

            <label for="">Salle</label>
            <input
                type="text"
                id="room"
                name="room"
                value="<?= htmlspecialchars($room) ?>"
                maxlength="20" />

            <label for="">Date et heure*</label>
            <input
                type="datetime-local"
                id="date_time"
                name="date_time"
                value="<?= htmlspecialchars($date_time) ?>"
                required />

            <?php if (IMAGE_UPLOAD_ENABLED) { ?>
                <label for="">Image</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    value="<?= htmlspecialchars($imageFile) ?>"
                    accept="image/png, image/jpeg, image/jpg" />
            <?php } ?>

            <button type="submit">Ajouter l'évènement</button>
        </form>

    </main>

    <?php require __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
