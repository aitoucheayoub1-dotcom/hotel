<main class="container">
    <section class="hero">
        <h1>Bienvenue à notre Hôtel</h1>
        <p>Réservez votre séjour et profitez d'un confort moderne, d'un accueil chaleureux et de services adaptés à vos besoins.</p>
        <a href="reservation.php" class="btn">Réserver une chambre</a>
    </section>

    <section class="section">
        <h2>Nos Chambres</h2>

        <form method="get" action="index.php" class="filtres">
            <input type="text" name="q" placeholder="Rechercher par nom..." value="<?php echo echapper($q ?? ''); ?>">
            <input type="number" name="capacite" min="0" placeholder="Capacité min" value="<?php echo (int)($capacite ?? 0); ?>">
            <select name="tri">
                <option value="id_asc" <?php echo ($tri ?? '') === 'id_asc' ? 'selected' : ''; ?>>Par défaut</option>
                <option value="prix_asc" <?php echo ($tri ?? '') === 'prix_asc' ? 'selected' : ''; ?>>Prix croissant</option>
                <option value="prix_desc" <?php echo ($tri ?? '') === 'prix_desc' ? 'selected' : ''; ?>>Prix décroissant</option>
                <option value="nom_asc" <?php echo ($tri ?? '') === 'nom_asc' ? 'selected' : ''; ?>>Nom (A-Z)</option>
                <option value="capacite_desc" <?php echo ($tri ?? '') === 'capacite_desc' ? 'selected' : ''; ?>>Capacité décroissante</option>
            </select>
            <button type="submit" class="btn">Filtrer</button>
        </form>

        <div class="grid">
            <?php if (!empty($chambres)): ?>
                <?php foreach ($chambres as $chambre): ?>
                    <article class="card">
                        <h3><?php echo echapper($chambre['nom']); ?></h3>
                        <p>Capacité : <?php echo (int)$chambre['capacite']; ?> personnes</p>
                        <p class="price"><strong><?php echo echapper((string)$chambre['prix_nuit']); ?> € / nuit</strong></p>
                        <a href="chambre.php?id=<?php echo (int)$chambre['id']; ?>" class="btn">Voir détails</a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune chambre ne correspond à votre recherche.</p>
            <?php endif; ?>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
            <nav class="pagination">
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <?php
                    $queryParams = http_build_query([
                        'q' => $q ?? '',
                        'capacite' => $capacite ?? 0,
                        'tri' => $tri ?? 'id_asc',
                        'page' => $p,
                    ]);
                    ?>
                    <a href="index.php?<?php echo echapper($queryParams); ?>" class="btn<?php echo $p === ($pageCourante ?? 1) ? ' active' : ''; ?>">
                        <?php echo $p; ?>
                    </a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    </section>

    <section class="section">
        <h2>Nos Services</h2>
        <div class="grid">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <article class="card">
                        <h3><?php echo echapper($service['nom'] ?? $service['titre'] ?? ''); ?></h3>
                        <?php if (!empty($service['description'])): ?>
                            <p><?php echo echapper($service['description']); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

