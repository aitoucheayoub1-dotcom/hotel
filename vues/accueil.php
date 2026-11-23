<main class="container">
    <section class="hero">
        <h1>Bienvenue à notre Hôtel</h1>
        <p>Réservez votre séjour et profitez d'un confort moderne, d'un service attentionné et d'un accueil disponible à tout moment.</p>
        <a href="reservation.php" class="btn">Réserver une chambre</a>
    </section>

    <section class="section">
        <h2>Nos Chambres</h2>
        <div class="grid">
            <?php foreach ($chambres as $chambre): ?>
                <article class="card">
                    <h3><?php echo echapper($chambre['titre']); ?></h3>
                    <p><?php echo echapper($chambre['description']); ?></p>
                    <p class="price"><strong><?php echo echapper((string)$chambre['prix']); ?> DZD / nuit</strong></p>
                    <a href="chambre.php?id=<?php echo (int)$chambre['id']; ?>" class="btn">Voir détails</a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <h2>Nos Services</h2>
        <div class="grid">
            <?php foreach ($services as $service): ?>
                <article class="card">
                    <h3><?php echo echapper($service['titre']); ?></h3>
                    <p><?php echo echapper($service['description']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
