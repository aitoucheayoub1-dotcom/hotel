<?php
require_once __DIR__ . '/includes/fonctions.php';

$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : 0;
$chambre = obtenirChambreParId($id);

$pageTitle = $chambre ? "Hôtel - " . $chambre['titre'] : "Hôtel - Chambre introuvable";
require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
    <?php if ($chambre): ?>
        <h1><?php echo echapper($chambre['titre']); ?></h1>
        <p><?php echo echapper($chambre['description']); ?></p>
        <p><?php echo echapper($chambre['details']); ?></p>
        <p class="price"><strong><?php echo echapper((string)$chambre['prix']); ?> DZD / nuit</strong></p>
        <a href="reservation.php" class="btn">Réserver cette chambre</a>
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

