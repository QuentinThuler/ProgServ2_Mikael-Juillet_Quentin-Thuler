<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isConnected = isset($_SESSION['user_id']);
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<header>
    <nav>
        <a href="/index.php">Accueil</a>

        <?php if ($isConnected): ?>
            <a href="/mes-inscriptions.php">Mes inscriptions</a>
            <a href="/profil.php">Mon profil</a>
            <?php if ($isAdmin): ?>
                <a href="/admin/index.php">Espace administrateur</a>
            <?php endif; ?>
            <a href="/deconnexion.php">Se déconnecter</a>
        <?php else: ?>
            <a href="/connexion.php">Se connecter</a>
            <a href="/inscription.php">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>
