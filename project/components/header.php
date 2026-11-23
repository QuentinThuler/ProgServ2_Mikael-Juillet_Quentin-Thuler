<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 */

use function App\services\escape;
?>

<header>
    <nav aria-label="Navigation principale">
        <a href="/index.php">Accueil</a>

        <?php if ($isConnected): ?>

            <a href="/account/my-registrations.php">
                Mes inscriptions
            </a>

            <a href="/user/show.php">
                <?= escape($_SESSION['email']) ?>
            </a>

            <a href="/account/logout.php">
                Déconnexion
            </a>

        <?php else: ?>

            <a href="/account/login.php">
                Se connecter
            </a>

            <a href="/account/signup.php">
                Créer un compte
            </a>

        <?php endif; ?>
    </nav>
</header>
