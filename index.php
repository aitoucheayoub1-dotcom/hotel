<?php
require_once __DIR__ . '/includes/fonctions.php';

$pageTitle = "Hôtel - Accueil";
$chambres = obtenirChambres();
$services = obtenirServices();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/vues/accueil.php';
require_once __DIR__ . '/includes/footer.php';


