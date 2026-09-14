<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/PartnershipArea.php';

$pageTitle = "Partnership Areas - Busitema University";
$pageDescription = "The ways Busitema University collaborates with partner institutions, including student exchange, staff mobility, joint research and more.";
$pageCanonical = seo_absolute_url('/partnerships/partnership-areas');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

try {
    $partnershipAreas = PartnershipArea::all();
} catch (Throwable $e) {
    $partnershipAreas = [];
}
?>

<main>
    <section class="page-hero" aria-labelledby="areas-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">How We Collaborate</p>
            <h1 id="areas-hero-title">Partnership areas</h1>
            <p class="page-hero__lead">
                Every partnership is shaped around shared goals. These are the main ways Busitema University works with
                institutions, organisations and agencies internationally.
            </p>
        </div>
    </section>

    <section class="section" aria-label="Areas of partnership">
        <div class="container">
            <?php if (!$partnershipAreas): ?>
                <p class="section-description">Partnership areas will appear here shortly.</p>
            <?php else: ?>
                <div class="area-grid">
                    <?php foreach ($partnershipAreas as $area): ?>
                        <article class="area-card">
                            <h3><?= htmlspecialchars($area['title']) ?></h3>
                            <p><?= htmlspecialchars($area['description']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-sm" aria-labelledby="areas-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <h2 id="areas-cta-title">Have a specific idea in mind?</h2>
                    <p>
                        Not every partnership fits a single category. Tell us what you have in mind and our team will help
                        shape the right kind of collaboration.
                    </p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars(($siteBase ?? '') . '/partnerships/become-a-partner') ?>">Become a partner</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars(($siteBase ?? '') . '/partnerships/partners') ?>">See our partners</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
