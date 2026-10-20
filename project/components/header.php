<?php
$isConnected = isset($_SESSION['user_id']);
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<header>
    <nav>
        <a href="/index.php">Accueil</a>

        <?php if ($isConnected): ?>
            <a href="/my-registrations.php">Mes inscriptions</a>
            <a href="/profile.php">Mon profil</a>
            <?php if ($isAdmin): ?>
                <a href="/admin/index.php">Espace administrateur</a>
            <?php endif; ?>
            <a href="/logout.php">Se déconnecter</a>
        <?php else: ?>
            <a href="/login.php">Se connecter</a>
            <a href="/register.php">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>
