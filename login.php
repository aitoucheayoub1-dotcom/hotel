<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/fonctions.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    $target = hasRole('admin') ? 'index.php' : 'reservation.php';
    header('Location: ' . $target);
    exit;
}

$email = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['mot_de_passe'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Veuillez saisir une adresse e-mail valide.';
    } elseif ($password === '') {
        $errorMessage = 'Veuillez saisir votre mot de passe.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nom, email, mot_de_passe, role FROM utilisateurs WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, (string) $user['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_nom'] = (string) $user['nom'];
            $_SESSION['user_email'] = (string) $user['email'];
            $_SESSION['user_role'] = in_array($user['role'] ?? 'client', ['client', 'admin'], true)
                ? $user['role']
                : 'client';

            setFlash('success', 'Connexion réussie. Bienvenue ' . $_SESSION['user_nom'] . ' !');
            $redirectTo = ($_SESSION['user_role'] === 'admin') ? 'index.php' : 'reservation.php';
            header('Location: ' . $redirectTo);
            exit;
        }

        $errorMessage = 'Adresse e-mail ou mot de passe incorrect.';
    }
}

$pageTitle = 'Connexion - Hôtel';
$headerPath = __DIR__ . '/includes/header.php';
if (file_exists($headerPath)) {
    require_once $headerPath;
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

    .auth-wrapper {
        max-width: 460px;
        margin: 36px auto 56px;
        padding: 0 20px;
    }

    .auth-card {
        background-color: var(--hotel-card);
        border: 1px solid #e2e8f0;
        border-top: 4px solid var(--hotel-gold);
        border-radius: 16px;
        padding: 32px 28px;
        box-shadow: 0 12px 28px rgba(15, 41, 66, 0.08);
    }

    .auth-card h1 {
        margin: 0 0 8px;
        color: var(--hotel-navy);
        font-size: 1.7rem;
    }

    .auth-card p {
        margin: 0 0 22px;
        color: #526071;
        font-size: 0.95rem;
    }

    .auth-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .auth-field label {
        font-weight: 600;
        color: var(--hotel-navy);
        font-size: 0.92rem;
    }

    .auth-field input {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid var(--hotel-border);
        border-radius: 8px;
        background-color: #f8fafc;
        color: var(--hotel-text);
        font-size: 0.96rem;
    }

    .auth-field input:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--hotel-navy);
        box-shadow: 0 0 0 3px rgba(200, 157, 84, 0.25);
    }

    .btn-auth {
        width: 100%;
        background-color: var(--hotel-navy);
        color: #ffffff;
        border: none;
        border-bottom: 3px solid var(--hotel-gold);
        border-radius: 10px;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-auth:hover {
        background-color: var(--hotel-navy-dark);
    }

    .alert {
        padding: 12px 14px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-weight: 500;
    }

    .alert.error, .alert.warning {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert.success {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
</style>

<main class="auth-wrapper">
    <div class="auth-card">
        <h1>Connexion</h1>
        <p>Connectez-vous à votre compte client ou administrateur.</p>

        <?php if (function_exists('renderFlash')) { renderFlash(); } ?>

        <?php if ($errorMessage !== ''): ?>
            <div class="alert error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="auth-field">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="auth-field">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <button type="submit" class="btn-auth">Se connecter</button>
        </form>
    </div>
</main>

<?php
$footerPath = __DIR__ . '/includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
}
