<?php
$pageTitle = "Hôtel - Réservation";
require_once __DIR__ . '/includes/header.php';

$errors = [];
$successMessage = "";

$nom = "";
$email = "";
$dateArrivee = "";
$nuits = "1";
$typeChambre = "standard";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dateArrivee = trim($_POST['date_arrivee'] ?? '');
    $nuits = trim($_POST['nuits'] ?? '');
    $typeChambre = trim($_POST['type_chambre'] ?? 'standard');

    if ($nom === '' || mb_strlen($nom) < 2) {
        $errors[] = "Le nom complet doit contenir au moins 2 caractères.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez saisir une adresse e-mail valide.";
    }

    if ($dateArrivee === '') {
        $errors[] = "Veuillez sélectionner une date d'arrivée.";
    }

    if (!ctype_digit($nuits) || (int)$nuits < 1 || (int)$nuits > 30) {
        $errors[] = "Le nombre de nuits doit être un entier entre 1 et 30.";
    }

    $chambresAutorisees = ['standard', 'deluxe', 'suite'];
    if (!in_array($typeChambre, $chambresAutorisees, true)) {
        $errors[] = "Type de chambre invalide.";
    }

    if (empty($errors)) {
        $successMessage = "Réservation validée pour " .
            htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') .
            " (" . (int)$nuits . " nuit(s), chambre " .
            htmlspecialchars($typeChambre, ENT_QUOTES, 'UTF-8') .
            ", arrivée le " . htmlspecialchars($dateArrivee, ENT_QUOTES, 'UTF-8') . ").";

        $nom = "";
        $email = "";
        $dateArrivee = "";
        $nuits = "1";
        $typeChambre = "standard";
    }
}
?>

<main class="container">
    <h1>Formulaire de réservation</h1>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($successMessage !== ""): ?>
        <div class="form-success">
            <p><?php echo $successMessage; ?></p>
        </div>
    <?php endif; ?>

    <form action="reservation.php" method="POST" class="form-grid">
        <div>
            <label for="nom">Nom complet</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div>
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div>
            <label for="date_arrivee">Date d'arrivée</label>
            <input type="date" id="date_arrivee" name="date_arrivee" value="<?php echo htmlspecialchars($dateArrivee, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div>
            <label for="nuits">Nombre de nuits</label>
            <input type="number" id="nuits" name="nuits" min="1" max="30" value="<?php echo htmlspecialchars($nuits, ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div>
            <label for="type_chambre">Type de chambre</label>
            <select id="type_chambre" name="type_chambre">
                <option value="standard" <?php echo ($typeChambre === 'standard') ? 'selected' : ''; ?>>Standard</option>
                <option value="deluxe" <?php echo ($typeChambre === 'deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                <option value="suite" <?php echo ($typeChambre === 'suite') ? 'selected' : ''; ?>>Suite</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn">Confirmer la réservation</button>
        </div>
    </form>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
