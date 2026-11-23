<?php
if (!isset($pageTitle)) {
    $pageTitle = "Hôtel - Accueil";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="index.php" class="logo">Hôtel</a>
            <nav class="nav">
                <a href="index.php">Accueil</a>
                <a href="reservation.php">Réservation</a>
            </nav>
        </div>
    </header>
   