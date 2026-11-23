<?php
require_once __DIR__ . '/includes/fonctions.php';

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
    $nom = nettoyerTexte($_POST['nom'] ?? '');
    $email = nettoyerTexte($_POST['email'] ?? '');
    $dateArrivee = nettoyerTexte($_POST['date_arrivee'] ?? '');
    $nuits = nettoyerTexte($_POST['nuits'] ?? '');
    $typeChambre = nettoyerTexte($_POST['type_chambre'] ?? 'standard');

    $errors = validerReservation($_POST);

    if (empty($errors)) {
        $successMessage = "Réservation validée pour " . $nom .
            " (" . (int)$nuits . " nuit(s), chambre " . $typeChambre .
            ", arrivée le " . $dateArrivee . ").";

        $nom = "";
        $email = "";
        $dateArrivee = "";
        $nuits = "1";
        $typeChambre = "standard";
    }
}

require_once __DIR__ . '/vues/reservation.php';
require_once __DIR__ . '/includes/footer.php';

