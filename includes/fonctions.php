<?php

function nettoyerTexte(?string $texte): string
{
    if ($texte === null) {
        return '';
    }
    return trim(htmlspecialchars($texte, ENT_QUOTES, 'UTF-8'));
}

function obtenirTypeChambreParId(PDO $pdo, int $id): string
{
    $typesParId = [
        1 => 'Standard',
        2 => 'Suite',
        3 => 'Deluxe',
        4 => 'Familiale',
    ];

    try {
        $sql = "SELECT nom FROM chambres WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['nom'])) {
            return trim((string) $row['nom']);
        }
    } catch (PDOException $e) {
        // إذا لم يكن جدول chambres موجوداً بعد، نرجع للقيمة الافتراضية حسب id
    }

    return $typesParId[$id] ?? 'Standard';
}

function validerReservation(array $donnees): array
{
    $erreurs = [];

    $nom = trim((string) ($donnees['nom'] ?? ''));
    $email = trim((string) ($donnees['email'] ?? ''));
    $dateArrivee = trim((string) ($donnees['date_arrivee'] ?? ''));
    $nuits = trim((string) ($donnees['nuits'] ?? ''));
    $typeChambre = trim((string) ($donnees['type_chambre'] ?? 'Standard'));

    if ($nom === '' || mb_strlen($nom) < 2) {
        $erreurs[] = "Le nom complet doit contenir au moins 2 caractères.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Veuillez saisir une adresse e-mail valide.";
    }

    if ($dateArrivee === '') {
        $erreurs[] = "Veuillez sélectionner une date d'arrivée.";
    }

    if (!ctype_digit($nuits) || (int) $nuits < 1 || (int) $nuits > 30) {
        $erreurs[] = "Le nombre de nuits doit être un entier entre 1 et 30.";
    }

    $typesValides = ['standard', 'suite', 'deluxe', 'familiale'];
    if (!in_array(mb_strtolower($typeChambre), $typesValides, true)) {
        $erreurs[] = "Type de chambre invalide.";
    }

    return $erreurs;
}

function enregistrerReservation(PDO $pdo, array $donnees): bool
{
    $sql = "INSERT INTO reservations (nom_client, email, date_arrivee, nuits, type_chambre)
            VALUES (:nom_client, :email, :date_arrivee, :nuits, :type_chambre)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':nom_client'   => trim((string) $donnees['nom']),
        ':email'        => trim((string) $donnees['email']),
        ':date_arrivee' => trim((string) $donnees['date_arrivee']),
        ':nuits'        => (int) $donnees['nuits'],
        ':type_chambre' => trim((string) $donnees['type_chambre']),
    ]);
}

if (!function_exists('setFlash')) {
    function setFlash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message,
        ];
    }
}

if (!function_exists('getFlash')) {
    function getFlash(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
            return null;
        }
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
}

if (!function_exists('renderFlash')) {
    function renderFlash(): void
    {
        $flash = getFlash();
        if (!$flash) {
            return;
        }
        $type = htmlspecialchars((string) ($flash['type'] ?? 'success'), ENT_QUOTES, 'UTF-8');
        $msg  = htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        echo '<div class="alert ' . $type . '">' . $msg . '</div>';
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['user_id']);
    }
}

if (!function_exists('hasRole')) {
    function hasRole(string $role): bool
    {
        if (!isLoggedIn()) {
            return false;
        }
        return (($_SESSION['user_role'] ?? 'client') === $role);
    }
}
