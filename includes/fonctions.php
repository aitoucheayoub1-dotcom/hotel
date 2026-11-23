<?php

function nettoyerTexte(string $valeur): string
{
    return trim($valeur);
}

function echapper(string $valeur): string
{
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
}

function obtenirChambres(): array
{
    return [
        1 => [
            'id' => 1,
            'titre' => 'Chambre Standard',
            'description' => 'Chambre confortable avec lit double, Wi-Fi haut débit et climatisation.',
            'details' => 'Lit double 160cm, salle de bain privée, TV écran plat, Wi-Fi fibre optique.',
            'prix' => 8500,
        ],
        2 => [
            'id' => 2,
            'titre' => 'Chambre Deluxe',
            'description' => 'Espace lumineux avec vue dégagée, coin salon et petit-déjeuner inclus.',
            'details' => 'Lit king-size, balcon avec vue, coin salon, petit-déjeuner buffet inclus.',
            'prix' => 13000,
        ],
        3 => [
            'id' => 3,
            'titre' => 'Suite Familiale',
            'description' => 'Suite spacieuse idéale pour les familles avec deux chambres et équipements complets.',
            'details' => 'Deux chambres communicantes, salon familial, minibar, accès prioritaire.',
            'prix' => 19500,
        ],
    ];
}

function obtenirChambreParId(int $id): ?array
{
    $chambres = obtenirChambres();
    return $chambres[$id] ?? null;
}

function obtenirServices(): array
{
    return [
        [
            'titre' => 'Réception 24h/24',
            'description' => 'Notre équipe est disponible jour et nuit pour faciliter votre arrivée et votre séjour.',
        ],
        [
            'titre' => 'Restaurant & Petit-déjeuner',
            'description' => 'Cuisine variée et petit-déjeuner servi chaque matin dans un cadre calme.',
        ],
        [
            'titre' => 'Parking Sécurisé',
            'description' => 'Stationnement surveillé pour nos clients voyageant en voiture.',
        ],
    ];
}

function validerReservation(array $donnees): array
{
    $erreurs = [];
    $nom = nettoyerTexte($donnees['nom'] ?? '');
    $email = nettoyerTexte($donnees['email'] ?? '');
    $dateArrivee = nettoyerTexte($donnees['date_arrivee'] ?? '');
    $nuits = nettoyerTexte($donnees['nuits'] ?? '');
    $typeChambre = nettoyerTexte($donnees['type_chambre'] ?? 'standard');

    if ($nom === '' || mb_strlen($nom) < 2) {
        $erreurs[] = "Le nom complet doit contenir au moins 2 caractères.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Veuillez saisir une adresse e-mail valide.";
    }

    if ($dateArrivee === '') {
        $erreurs[] = "Veuillez sélectionner une date d'arrivée.";
    }

    if (!ctype_digit($nuits) || (int)$nuits < 1 || (int)$nuits > 30) {
        $erreurs[] = "Le nombre de nuits doit être un entier entre 1 et 30.";
    }

    $chambresAutorisees = ['standard', 'deluxe', 'suite'];
    if (!in_array($typeChambre, $chambresAutorisees, true)) {
        $erreurs[] = "Type de chambre invalide.";
    }

    return $erreurs;
}
function rechercherTypesChambres(
    PDO $pdo,
    string $q = '',
    int $capaciteMin = 0,
    string $tri = 'id_asc',
    int $page = 1,
    int $parPage = 6
): array {
    $page = max(1, $page);
    $offset = ($page - 1) * $parPage;
    $where = [];
    $params = [];

    if ($q !== '') {
        $where[] = "nom LIKE :q";
        $params[':q'] = '%' . $q . '%';
    }

    if ($capaciteMin > 0) {
        $where[] = "capacite >= :capacite";
        $params[':capacite'] = $capaciteMin;
    }

    $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $trisAutorises = [
        'id_asc'     => 'id ASC',
        'prix_asc'   => 'prix_nuit ASC',
        'prix_desc'  => 'prix_nuit DESC',
        'nom_asc'    => 'nom ASC',
        'capacite_desc' => 'capacite DESC',
    ];
    $orderBy = $trisAutorises[$tri] ?? 'id ASC';

    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM types_chambres $sqlWhere");
    $stmtCount->execute($params);
    $total = (int) $stmtCount->fetchColumn();

    $sql = "SELECT id, nom, prix_nuit, capacite FROM types_chambres $sqlWhere ORDER BY $orderBy LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $val) {
        if ($key === ':capacite') {
            $stmt->bindValue($key, (int) $val, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
    }
    $stmt->bindValue(':limit', $parPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return [
        'items' => $stmt->fetchAll(),
        'total' => $total,
        'pages' => max(1, (int) ceil($total / $parPage)),
        'page'  => $page,
    ];
}
