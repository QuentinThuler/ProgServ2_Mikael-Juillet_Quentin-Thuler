<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 */
?>
<footer>
    <nav aria-label="Pied de page">
        <section class="explore">
            <h2><?= e(t('')) ?><?= e(t('Explorer')) ?></h2>
            <a href="/index.php"><?= e(t('Accueil')) ?></a>
            <?php if ($isConnected): ?>
                <a href="/profile/my-registrations.php"><?= e(t('Mes inscriptions')) ?></a>
            <?php else: ?>
                <a href="/index.php"><?= e(t('Évènements à venir')) ?></a>
            <?php endif; ?>
        </section>

        <section class="account">
            <h2><?= e(t('Mon compte')) ?></h2>
            <?php if ($isConnected): ?>
                <a href="/profile/profile.php"><?= e(t('Détails du profil')) ?></a>
                <a href="/profile/profile-edit.php"><?= e(t('Modification du profil')) ?></a>
                <?php if ($isAdmin): ?>
                    <a href="/admin/index.php"><?= e(t('Espace administrateur')) ?></a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/account/login.php"><?= e(t('Se connecter')) ?></a>
                <a href="/account/signup.php"><?= e(t('S\'inscrire')) ?></a>
            <?php endif; ?>
        </section>
    </nav>
</footer>
