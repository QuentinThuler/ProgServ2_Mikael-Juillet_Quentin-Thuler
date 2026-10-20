<?php

/**
 * @var bool $isConnected Defined in src/init.php
 * @var bool $isAdmin     Defined in src/init.php
 */
?>
<footer>
    <nav aria-label="Pied de page">
        <section class="explore">
            <h2>Explorer</h2>
            <a href="/index.php">Accueil</a>
            <?php if ($isConnected): ?>
                <a href="/my-registrations.php">Mes inscriptions</a>
            <?php else: ?>
                <a href="/index.php">Évènements à venir</a>
            <?php endif; ?>
        </section>

        <section class="account">
            <h2>Mon compte</h2>
            <?php if ($isConnected): ?>
                <a href="/profile.php">Détails du profil</a>
                <a href="/profile-edit.php">Modification du profil</a>
                <?php if ($isAdmin): ?>
                    <a href="/admin/index.php">Espace administrateur</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/login.php">Se connecter</a>
                <a href="/signup.php">S'inscrire</a>
            <?php endif; ?>
        </section>
    </nav>
</footer>
