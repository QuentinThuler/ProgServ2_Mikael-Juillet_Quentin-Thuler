<?php
require_once __DIR__ . '/../../src/init.php';
/* requireAdmin(); */

$pageTitle = "Créer un Évènement";

// Définition des valeurs par défaut de l'évènement
$title = $_POST["title"] ?? '';
$description = $_POST["description"] ?? '';
$tagline = $_POST["tagline"] ?? '';
$address = $_POST["address"] ?? '';
$city = $_POST["city"] ?? '';
$room = $_POST["room"] ?? '';
$date_time = $_POST["date_time"] ?? '';
$image = $_POST["image"] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation de l'évènement
    $errors = validateEvent(
        $title,
        $description,
        $tagline,
        $address,
        $city,
        $room,
        $date_time,
        $image,
    );

    if (empty($errors)) {
        $newEventId = addEvent(
            $pdo,
            $title,
            $description,
            $tagline,
            $address,
            $city,
            $room ?: null,
            $date_time,
            $image ?: null,
        );

        if ($newEventId !== null) {
            header('Location: ./view.php?id=' . $newEventId);
            exit;
        } else {
            $errors = array_push($errors, "Une erreur est survenue lors de l'ajout de l'évènement.");
        }
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
        <form action="./create.php">
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

            <label for="">Image</label>
            <input
                type="file"
                id="image"
                name="image"
                value="<?= htmlspecialchars($image) ?>"
                accept="/png, /jpeg, /jpg, /tiff, /svg" />

            <button type="submit">Ajouter l'évènement</button>
        </form>

    </main>

    <?php require __DIR__ . '/../../components/footer.php' ?>
</body>

</html>
