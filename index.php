<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

$pageTitle = "Hôtel - Accueil";

$q = trim($_GET['q'] ?? '');
$capacite = isset($_GET['capacite']) && ctype_digit($_GET['capacite']) ? (int) $_GET['capacite'] : 0;
$tri = $_GET['tri'] ?? 'id_asc';
$page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int) $_GET['page'] : 1;

$resultat = rechercherTypesChambres($pdo, $q, $capacite, $tri, $page, 6);
$chambres = $resultat['items'];
$totalPages = $resultat['pages'];
$pageCourante = $resultat['page'];

$services = obtenirServices();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/vues/accueil.php';
require_once __DIR__ . '/includes/footer.php';



