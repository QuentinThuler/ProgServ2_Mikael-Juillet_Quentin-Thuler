<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 */
?>
<header>
    <nav aria-label="Principale">
        <a href="/index.php">Accueil</a>

        <?php if ($isConnected): ?>
            <a href="/account/my-registrations.php">Mes inscriptions</a>
            <a href="/profile/profile.php">Mon profil</a>
        <?php else: ?>
            <a href="/account/login.php">Se connecter</a>
            <a href="/account/signup.php">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>
