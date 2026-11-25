<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$fonctionsPath = __DIR__ . '/fonctions.php';
if (file_exists($fonctionsPath)) {
    require_once $fonctionsPath;
}

if (!isset($pageTitle)) {
    $pageTitle = "Hôtel - Accueil";
}

$loggedIn = function_exists('isLoggedIn') ? isLoggedIn() : !empty($_SESSION['user_id']);
$userName = (string) ($_SESSION['user_nom'] ?? '');
$userRole = (string) ($_SESSION['user_role'] ?? 'client');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .site-header {
            background-color: #0f2942;
            border-bottom: 3px solid #c89d54;
            color: #ffffff;
        }
        .site-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 14px 20px;
        }
        .site-header .logo {
            color: #c89d54;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
        }
        .site-header .nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }
        .site-header .nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .site-header .nav a:hover {
            background-color: rgba(200, 157, 84, 0.18);
            color: #f3d08a;
        }
        .nav-user-badge {
            background-color: rgba(200, 157, 84, 0.16);
            border: 1px solid rgba(200, 157, 84, 0.55);
            color: #f6dc9c;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .btn-nav-auth {
            background-color: #c89d54;
            color: #0f2942 !important;
            font-weight: 700 !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
        }
        .btn-nav-auth:hover {
            background-color: #b3863d !important;
            color: #ffffff !important;
        }
        .flash-container {
            max-width: 1100px;
            margin: 16px auto 0;
            padding: 0 20px;
        }
        .flash-message, .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-weight: 600;
            border-left: 4px solid #0f2942;
        }
        .flash-success, .alert-success, .alert.success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-left-color: #059669;
        }
        .flash-error, .alert-error, .alert.error, .alert-danger, .alert.danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-left-color: #dc2626;
        }
        .flash-warning, .alert-warning, .alert.warning {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
            border-left-color: #c89d54;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="index.php" class="logo">Hôtel</a>
            <nav class="nav">
                <a href="index.php">Accueil</a>
                <a href="reservation.php">Réservation</a>

                <?php if ($loggedIn): ?>
                    <span class="nav-user-badge">
                        <?= htmlspecialchars($userName !== '' ? $userName : 'Utilisateur', ENT_QUOTES, 'UTF-8') ?>
                        (<?= htmlspecialchars($userRole, ENT_QUOTES, 'UTF-8') ?>)
                    </span>
                    <a href="logout.php" class="btn-nav-auth">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="btn-nav-auth">Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <?php if (function_exists('renderFlash')): ?>
        <div class="flash-container">
            <?php renderFlash(); ?>
        </div>
    <?php endif; ?>
