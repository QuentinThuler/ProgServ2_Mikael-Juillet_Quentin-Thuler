<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 */

use function App\services\escape;
use function App\services\translate;

// TODO : I'm not sure if it's necessary to include the escape character as well 
?>
<footer>
    <nav aria-label="Pied de page">
        <section class="explore">
            <h2><?= escape(translate('Explorer')) ?></h2>
            <a href="/index.php"><?= escape(translate('Accueil')) ?></a>
            <?php if ($isConnected): ?>
                <a href="/profile/my-registrations.php"><?= escape(translate('Mes inscriptions')) ?></a>
            <?php else: ?>
                <a href="/index.php"><?= escape(translate('Évènements à venir')) ?></a>
            <?php endif; ?>
        </section>

        <section class="account">
            <h2><?= escape(translate('Mon compte')) ?></h2>
            <?php if ($isConnected): ?>
                <a href="/profile/profile.php"><?= escape(translate('Détails du profil')) ?></a>
                <a href="/profile/profile-edit.php"><?= escape(translate('Modification du profil')) ?></a>
                <?php if ($isAdmin): ?>
                    <a href="/admin/index.php"><?= escape(translate('Espace administrateur')) ?></a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/account/login.php"><?= escape(translate('Se connecter')) ?></a>
                <a href="/account/signup.php"><?= escape(translate('S\'inscrire')) ?></a>
            <?php endif; ?>
        </section>
    </nav>
</footer>
