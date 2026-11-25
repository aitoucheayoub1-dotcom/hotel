<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$fonctionsPath = __DIR__ . '/includes/fonctions.php';
if (file_exists($fonctionsPath)) {
    require_once $fonctionsPath;
}
if (function_exists('requireLogin')) {
    requireLogin('login.php');
}

require_once __DIR__ . '/includes/db.php';

$pageTitle = "Hôtel - Réservation";
$headerPath = __DIR__ . '/includes/header.php';
if (file_exists($headerPath)) {
    require_once $headerPath;
}

$errors = [];
$successMessage = "";

$nom = "";
$email = "";
$dateArrivee = "";
$nuits = "1";
$typeChambre = "Standard";

$idChambre = (isset($_GET['id']) && ctype_digit((string) $_GET['id'])) ? (int) $_GET['id'] : 0;
if ($idChambre === 2) {
    $typeChambre = "Suite";
} elseif ($idChambre === 3) {
    $typeChambre = "Deluxe";
} elseif ($idChambre === 4) {
    $typeChambre = "Familiale";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim((string) ($_POST['nom'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $dateArrivee = trim((string) ($_POST['date_arrivee'] ?? ''));
    $nuits = trim((string) ($_POST['nuits'] ?? '1'));
    $typeChambre = trim((string) ($_POST['type_chambre'] ?? 'Standard'));

    if ($nom === '' || mb_strlen($nom) < 2) {
        $errors[] = "Le nom complet doit contenir au moins 2 caractères.";
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez saisir une adresse e-mail valide.";
    }
    if ($dateArrivee === '') {
        $errors[] = "Veuillez sélectionner une date d'arrivée.";
    }
    if (!ctype_digit($nuits) || (int) $nuits < 1 || (int) $nuits > 30) {
        $errors[] = "Le nombre de nuits doit être un entier entre 1 et 30.";
    }

    $mapChambres = [
        'standard'  => ['label' => 'Standard', 'id' => 1],
        'suite'     => ['label' => 'Suite', 'id' => 2],
        'deluxe'    => ['label' => 'Deluxe', 'id' => 3],
        'familiale' => ['label' => 'Familiale', 'id' => 4],
    ];
    $cleChambre = mb_strtolower($typeChambre);
    if (!isset($mapChambres[$cleChambre])) {
        $errors[] = "Type de chambre invalide.";
    }

    if (empty($errors)) {
        try {
            $dtArrivee = new DateTime($dateArrivee);
            $dtDepart = clone $dtArrivee;
            $dtDepart->modify('+' . (int) $nuits . ' days');
            $dateDepart = $dtDepart->format('Y-m-d');

            $utilisateurId = 1;
            $stmtUser = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email LIMIT 1");
            $stmtUser->execute([':email' => $email]);
            $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
            if ($userRow && !empty($userRow['id'])) {
                $utilisateurId = (int) $userRow['id'];
            } else {
                $stmtFirst = $pdo->query("SELECT id FROM utilisateurs ORDER BY id ASC LIMIT 1");
                $firstRow = $stmtFirst ? $stmtFirst->fetch(PDO::FETCH_ASSOC) : null;
                if ($firstRow && !empty($firstRow['id'])) {
                    $utilisateurId = (int) $firstRow['id'];
                }
            }

            $chambreId = ($idChambre > 0) ? $idChambre : $mapChambres[$cleChambre]['id'];

            $sql = "INSERT INTO reservations (utilisateur_id, chambre_id, date_arrivee, date_depart, statut)
                    VALUES (:utilisateur_id, :chambre_id, :date_arrivee, :date_depart, :statut)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':utilisateur_id' => $utilisateurId,
                ':chambre_id'     => $chambreId,
                ':date_arrivee'   => $dateArrivee,
                ':date_depart'    => $dateDepart,
                ':statut'         => 'confirmee',
            ]);

            $successMessage = "Réservation enregistrée avec succès pour " . $nom .
                " (" . (int) $nuits . " nuit(s), chambre " . $mapChambres[$cleChambre]['label'] .
                ", du " . $dateArrivee . " au " . $dateDepart . ").";

            $nom = "";
            $email = "";
            $dateArrivee = "";
            $nuits = "1";
            $typeChambre = "Standard";
        } catch (Throwable $e) {
            $errors[] = "Erreur lors de l'enregistrement de la réservation : " . $e->getMessage();
        }
    }
}
?>

<style>
    :root {
        --hotel-navy: #0f2942;
        --hotel-navy-dark: #091a2b;
        --hotel-gold: #c89d54;
        --hotel-gold-hover: #b3863d;
        --hotel-bg: #f4f6f9;
        --hotel-card: #ffffff;
        --hotel-text: #1e293b;
        --hotel-border: #cbd5e1;
    }

    body {
        background-color: var(--hotel-bg);
        color: var(--hotel-text);
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        margin: 0;
    }

    .reservation-wrapper {
        max-width: 920px;
        margin: 28px auto 48px;
        padding: 0 20px;
    }

    .reservation-card {
        background-color: var(--hotel-card);
        border: 1px solid #e2e8f0;
        border-top: 4px solid var(--hotel-gold);
        border-radius: 16px;
        padding: 32px 28px;
        box-shadow: 0 12px 28px rgba(15, 41, 66, 0.08);
    }

    .reservation-header {
        margin-bottom: 24px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .reservation-header h1 {
        margin: 0 0 6px;
        color: var(--hotel-navy);
        font-size: 1.8rem;
    }

    .reservation-header p {
        margin: 0;
        color: #526071;
        font-size: 0.96rem;
    }

    .reservation-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 18px 28px;
    }

    @media (min-width: 768px) {
        .reservation-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-field label {
        font-weight: 600;
        color: var(--hotel-navy);
        font-size: 0.94rem;
    }

    .form-field input,
    .form-field select {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid var(--hotel-border);
        border-radius: 8px;
        background-color: #f8fafc;
        color: var(--hotel-text);
        font-size: 0.96rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .form-field input:focus,
    .form-field select:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--hotel-navy);
        box-shadow: 0 0 0 3px rgba(200, 157, 84, 0.25);
    }

    .form-actions {
        grid-column: 1 / -1;
        margin-top: 10px;
    }

    .btn-submit-reservation {
        background-color: var(--hotel-navy);
        color: #ffffff;
        border: none;
        border-bottom: 3px solid var(--hotel-gold);
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.15s ease;
    }

    .btn-submit-reservation:hover {
        background-color: var(--hotel-navy-dark);
        transform: translateY(-1px);
    }

    .alert {
        padding: 14px 16px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-weight: 500;
    }

    .alert.success {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert.error {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert ul {
        margin: 0;
        padding-left: 18px;
    }
</style>

<main class="reservation-wrapper">
    <div class="reservation-card">
        <div class="reservation-header">
            <h1>Formulaire de réservation</h1>
            <p>Sélectionnez vos dates et votre type de chambre pour confirmer votre séjour.</p>
        </div>

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

        <form method="POST" action="" class="reservation-grid">
            <div class="form-field">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>">
            </div>

            <div class="form-field">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
            </div>

            <div class="form-field">
                <label for="date_arrivee">Date d'arrivée</label>
                <input type="date" id="date_arrivee" name="date_arrivee" value="<?= htmlspecialchars($dateArrivee) ?>">
            </div>

            <div class="form-field">
                <label for="nuits">Nombre de nuits</label>
                <input type="number" id="nuits" name="nuits" min="1" value="<?= htmlspecialchars($nuits) ?>">
            </div>

            <div class="form-field">
                <label for="type_chambre">Type de chambre</label>
                <select id="type_chambre" name="type_chambre">
                    <?php foreach (['Standard', 'Suite', 'Deluxe', 'Familiale'] as $option): ?>
                        <option value="<?= htmlspecialchars($option) ?>"<?= (mb_strtolower($typeChambre) === mb_strtolower($option)) ? ' selected' : '' ?>>
                            <?= htmlspecialchars($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-reservation">Confirmer la réservation</button>
            </div>
        </form>
    </div>
</main>

<?php
$footerPath = __DIR__ . '/includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
}
