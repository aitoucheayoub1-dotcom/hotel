<?php
require_once __DIR__ . '/../includes/fonctions.php';

$erreursVide = validerReservation([]);
if (empty($erreursVide)) {
    echo "ÉCHEC : un formulaire vide doit retourner des erreurs.\n";
    exit(1);
}

$erreursValide = validerReservation([
    'nom' => 'Ayoub Test',
    'email' => 'ayoub@exemple.com',
    'date_arrivee' => '2026-12-01',
    'nuits' => '2',
    'type_chambre' => 'deluxe',
]);
if (!empty($erreursValide)) {
    echo "ÉCHEC : des données valides ne doivent pas retourner d'erreurs.\n";
    exit(1);
}

$chambre = obtenirChambreParId(1);
if (!$chambre || $chambre['titre'] !== 'Chambre Standard') {
    echo "ÉCHEC : la chambre 1 est introuvable ou incorrecte.\n";
    exit(1);
}

echo "SUCCÈS : tous les tests de l'étape 6 sont passés.\n";
