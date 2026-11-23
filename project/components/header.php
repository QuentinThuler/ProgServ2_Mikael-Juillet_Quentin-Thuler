<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 */

require_once __DIR__ . '/../src/init.php';
?>

<header>
    <nav aria-label="Principale">
        <a href="/index.php"><?= e(t('Accueil')) ?></a>

        <?php if ($isConnected): ?>
            <a href="/account/my-registrations.php"><?= e(t('Mes inscriptions')) ?></a>
            <a href="/profile/profile.php"><?= e(t('Détails du profil')) ?></a>
        <?php else: ?>
            <a href="/account/login.php"><?= e(t('Se connecter')) ?></a>
            <a href="/account/signup.php"><?= e(t('S\'inscrire')) ?></a>
        <?php endif; ?>

        <?php
        $frUrl = '?' . http_build_query(array_merge($_GET, ['lang' => 'fr']));
        $enUrl = '?' . http_build_query(array_merge($_GET, ['lang' => 'en']));
        ?>

        <nav class="lang-switcher" aria-label="Language">
            <a href="<?= e($frUrl) ?>" <?= $language === 'fr' ? 'aria-current="true"' : '' ?>>FR</a>
            <a href="<?= e($enUrl) ?>" <?= $language === 'en' ? 'aria-current="true"' : '' ?>>EN</a>
        </nav>
    </nav>
</header>
