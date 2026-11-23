<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 * @var bool $language  Is used in head.php
 */

use function App\services\escape;
use function App\services\translate;

require_once __DIR__ . '/../src/init.php';

$frUrl = '?' . http_build_query(array_merge($_GET, ['lang' => 'fr']));
$enUrl = '?' . http_build_query(array_merge($_GET, ['lang' => 'en']));
?>

<header>
    <nav aria-label="Principale">
        <a href="../public/index.php"><?= escape(translate('Accueil')) ?></a>

        <?php if ($isConnected): ?>
            <a href="/account/my-registrations.php"><?= escape(translate('Mes inscriptions')) ?></a>
            <a href="/account/show.php"><?= escape(translate('Détails du profil')) ?></a>
            <a href="/account/logout.php"><?= escape(translate('Déconnexion')) ?> </a>
        <?php else: ?>
            <a href="/account/login.php"><?= escape(translate('Se connecter')) ?></a>
            <a href="/account/signup.php"><?= escape(translate('S\'inscrire')) ?></a>
        <?php endif; ?>

        <nav class="lang-switcher" aria-label="Language">
            <a href="<?= escape($frUrl) ?>" <?= $language === 'fr' ? 'aria-current="true"' : '' ?>>FR</a>
            <a href="<?= escape($enUrl) ?>" <?= $language === 'en' ? 'aria-current="true"' : '' ?>>EN</a>
        </nav>
    </nav>
</header>
