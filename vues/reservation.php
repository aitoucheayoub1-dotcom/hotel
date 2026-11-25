<?php
require_once __DIR__ . '/includes/fonctions.php';
require_once __DIR__ . '/includes/db.php';

$pageTitle = "Hôtel - Réservation";
require_once __DIR__ . '/includes/header.php';

$errors = [];
$successMessage = "";

$nom = "";
$email = "";
$dateArrivee = "";
$nuits = "1";
$typeChambre = "Standard";

// جلب نوع الغرفة من الرابط إذا تم تمرير id صحيح
$idChambre = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int) $_GET['id'] : 0;
if ($idChambre > 0) {
    $chambreSelectionnee = obtenirTypeChambreParId($pdo, $idChambre);
    if (is_array($chambreSelectionnee) && !empty($chambreSelectionnee['nom'])) {
        $typeChambre = trim((string) $chambreSelectionnee['nom']);
    } elseif (is_string($chambreSelectionnee) && $chambreSelectionnee !== '') {
        $typeChambre = trim($chambreSelectionnee);
    }
}

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = nettoyerTexte($_POST['nom'] ?? '');
    $email = nettoyerTexte($_POST['email'] ?? '');
    $dateArrivee = nettoyerTexte($_POST['date_arrivee'] ?? '');
    $nuits = nettoyerTexte($_POST['nuits'] ?? '1');
    $typeChambre = nettoyerTexte($_POST['type_chambre'] ?? 'Standard');

    $errors = validerReservation($_POST);

    if (empty($errors)) {
        $successMessage = "Réservation validée pour " . $nom .
            " (" . (int)$nuits . " nuit(s), chambre " . $typeChambre .
            ", arrivée le " . $dateArrivee . ").";

        $nom = "";
        $email = "";
        $dateArrivee = "";
        $nuits = "1";
        $typeChambre = "Standard";
    }
}

// خيارات الغرف المتاحة
$optionsChambres = ['Standard', 'Suite', 'Deluxe', 'Familiale'];
$trouve = false;
foreach ($optionsChambres as $opt) {
    if (mb_strtolower(trim($opt), 'UTF-8') === mb_strtolower(trim($typeChambre), 'UTF-8')) {
        $typeChambre = $opt;
        $trouve = true;
        break;
    }
}
if (!$trouve && !empty($typeChambre)) {
    $optionsChambres[] = $typeChambre;
}
?>

<main class="container">
    <h1>Formulaire de réservation</h1>

    <?php if (!empty($successMessage)): ?>
        <div class="alert success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="nom">Nom complet</label><br>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>">
        </div>
        <br>

        <div>
            <label for="email">Adresse e-mail</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
        </div>
        <br>

        <div>
            <label for="date_arrivee">Date d'arrivée</label><br>
            <input type="date" id="date_arrivee" name="date_arrivee" value="<?= htmlspecialchars($dateArrivee) ?>">
        </div>
        <br>

        <div>
            <label for="nuits">Nombre de nuits</label><br>
            <input type="number" id="nuits" name="nuits" min="1" value="<?= htmlspecialchars($nuits) ?>">
        </div>
        <br>

        <div>
            <label for="type_chambre">Type de chambre</label><br>
            <select id="type_chambre" name="type_chambre">
                <?php foreach ($optionsChambres as $option): ?>
                    <option value="<?= htmlspecialchars($option) ?>" <?= mb_strtolower(trim($typeChambre), 'UTF-8') === mb_strtolower(trim($option), 'UTF-8') ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <button type="submit">Confirmer la réservation</button>
    </form>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
