<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int) $_GET['id'] : 0;
$chambre = obtenirTypeChambreParId($pdo, $id);

$pageTitle = $chambre ? "Hôtel - " . echapper($chambre['nom']) : "Hôtel - Chambre";
require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
    <?php if ($chambre): ?>
        <h1><?php echo echapper($chambre['nom']); ?></h1>
        <p>Capacité : <?php echo (int) $chambre['capacite']; ?> personnes</p>
        <p class="price"><strong><?php echo echapper((string) $chambre['prix_nuit']); ?> € / nuit</strong></p>
        <a href="reservation.php?id=<?php echo (int) $chambre['id']; ?>" class="btn">Réserver cette chambre</a>
        <a href="index.php" class="btn">Retour à l'accueil</a>
    <?php else: ?>
        <h1>Chambre introuvable</h1>
        <p>La chambre demandée n'existe pas.</p>
        <a href="index.php" class="btn">Retour à l'accueil</a>
    <?php endif; ?>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

