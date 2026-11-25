<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Bienvenue à notre hôtel";
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
        --hotel-muted: #526071;
        --hotel-border: #e2e8f0;
    }

    body {
        background-color: var(--hotel-bg);
        color: var(--hotel-text);
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        margin: 0;
    }

    .hotel-page {
        max-width: 1100px;
        margin: 28px auto 48px;
        padding: 0 20px;
    }

    .hero-banner {
        background: linear-gradient(135deg, var(--hotel-navy) 0%, var(--hotel-navy-dark) 100%);
        color: #ffffff;
        padding: 52px 36px;
        border-radius: 16px;
        box-shadow: 0 14px 30px rgba(15, 41, 66, 0.16);
        border-bottom: 4px solid var(--hotel-gold);
    }

    .hero-badge {
        display: inline-block;
        background-color: rgba(200, 157, 84, 0.18);
        color: #f3d299;
        border: 1px solid rgba(200, 157, 84, 0.45);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.4px;
        margin-bottom: 14px;
    }

    .hero-banner h1 {
        margin: 0 0 12px;
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight: 700;
        color: #ffffff;
    }

    .hero-banner p {
        margin: 0 0 26px;
        max-width: 650px;
        font-size: 1.05rem;
        line-height: 1.65;
        color: #dce5ef;
    }

    .btn-hotel-primary {
        display: inline-block;
        background-color: var(--hotel-gold);
        color: #ffffff;
        text-decoration: none;
        padding: 13px 26px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 6px 16px rgba(200, 157, 84, 0.35);
        transition: transform 0.15s ease, background-color 0.2s ease;
    }

    .btn-hotel-primary:hover {
        background-color: var(--hotel-gold-hover);
        transform: translateY(-2px);
    }

    .services-section {
        margin-top: 32px;
    }

    .services-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 22px;
    }

    @media (min-width: 768px) {
        .services-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .service-box {
        background-color: var(--hotel-card);
        border: 1px solid var(--hotel-border);
        border-top: 4px solid var(--hotel-gold);
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
    }

    .service-box h2 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--hotel-navy);
    }

    .service-box p {
        margin: 0;
        color: var(--hotel-muted);
        line-height: 1.6;
        font-size: 0.96rem;
    }

    .service-link {
        color: var(--hotel-navy);
        font-weight: 700;
        text-decoration: none;
        border-bottom: 2px solid var(--hotel-gold);
        padding-bottom: 2px;
        align-self: flex-start;
    }

    .service-link:hover {
        color: var(--hotel-gold-hover);
    }
</style>

<main class="hotel-page">
    <section class="hero-banner">
        <span class="hero-badge">Séjour &amp; Confort</span>
        <h1>Bienvenue à notre hôtel</h1>
        <p>Profitez d'un séjour confortable et calme avec des services pensés pour votre détente et vos déplacements.</p>
        <a href="reservation.php" class="btn-hotel-primary">Réserver une chambre</a>
    </section>

    <section class="services-section">
        <div class="services-grid">
            <article class="service-box">
                <div>
                    <h2>Parking</h2>
                    <p>Un espace de stationnement sécurisé et pratique disponible pour nos clients tout au long de leur séjour.</p>
                </div>
            </article>

            <article class="service-box">
                <div>
                    <h2>Petit déjeuner</h2>
                    <p>Un petit déjeuner varié et frais servi chaque matin pour bien démarrer votre journée.</p>
                </div>
            </article>

            <article class="service-box">
                <div>
                    <h2>Chambres confortables</h2>
                    <p>Des chambres adaptées à vos besoins avec une réservation simple et rapide en ligne.</p>
                </div>
                <a href="reservation.php" class="service-link">Accéder à la réservation →</a>
            </article>
        </div>
    </section>
</main>

<?php
$footerPath = __DIR__ . '/includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
}
