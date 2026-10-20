<?php
const DB_HOST = 'mariadb';
const DB_PORT = 3306;
const DB_NAME = 'my_database';
const DB_USER = 'username';
const DB_PASSWORD = 'password';

$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

$sql = "SELECT * FROM my_table";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$results = $stmt->fetchAll();

$pageTitle = "Accueil";
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once __DIR__ . '/../components/head.php' ?>

<body>

    <?php require_once __DIR__ . '/../components/header.php' ?>

    <h1>ProgServ2 Course PHP Template</h1>

    <p>Bienvenue dans votre projet PHP pour le cours ProgServ2 à la HEIG-VD !</p>

    <h2>Résultats de la base de données :</h2>

    <ul>
        <?php foreach ($results as $row) { ?>
            <li>ID: <?= htmlspecialchars($row['id']) ?>
                <ul>
                    <li>Column1: <?= htmlspecialchars($row['column1']) ?></li>
                    <li>Column2: <?= htmlspecialchars($row['column2']) ?></li>
                </ul>
            </li>
        <?php } ?>
    </ul>

    <?php require_once __DIR__ . '/../components/footer.php' ?>
</body>

</html>
