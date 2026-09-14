<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/Partner.php';

$requestedSlug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';

try {
    $partner = $requestedSlug !== '' ? Partner::findBySlug($requestedSlug) : null;
} catch (Throwable $e) {
    $partner = null;
}

$partnerRegion = $partner['region'] ?? null;

if ($partner) {
    $seo = seo_meta([
        'title' => $partner['name'] . ' - Busitema University Partners',
        'description' => $partner['about'],
        'image' => $partner['logo'] ? 'partners/' . $partner['logo'] : null,
        'overrides' => $partner,
        'canonical_path' => '/partnerships/partner?slug=' . $partner['slug'],
        'type' => 'website',
    ]);
    $pageTitle = $seo['title'];
    $pageDescription = $seo['description'];
    $pageImage = $seo['image'];
    $pageCanonical = $seo['canonical'];
    $pageNoindex = $seo['noindex'];
    $pageStructuredData = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $partner['name'],
        'description' => $seo['description'],
        'address' => $partner['country'] ? [
            '@type' => 'PostalAddress',
            'addressCountry' => $partner['country'],
        ] : null,
        'logo' => $seo['image'],
        'url' => $seo['canonical'],
    ], static fn ($value) => $value !== null);
} else {
    $pageTitle = 'Partner Not Found - Busitema University';
    $pageNoindex = true;
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$partnerAssetBase = $assetBase ?? '/assets';
$partnerSiteBase = $siteBase ?? '';
?>

<main>
    <?php if (!$partner): ?>
        <section class="section" aria-labelledby="partner-not-found-title">
            <div class="container partner-not-found">
                <h1 id="partner-not-found-title">Partner not found</h1>
                <p class="section-description">
                    We couldn't find the partner institution you were looking for. It may have been renamed or removed.
                </p>
                <a class="btn btn-primary" href="<?= htmlspecialchars($partnerSiteBase . '/partnerships/partners') ?>">Back to all partners</a>
            </div>
        </section>
    <?php else: ?>
        <section class="page-hero" aria-labelledby="partner-hero-title">
            <div class="page-hero__inner">
                <p class="page-hero__eyebrow"><?= htmlspecialchars($partnerRegion) ?> &middot; <?= htmlspecialchars($partner['country']) ?></p>
                <h1 id="partner-hero-title"><?= htmlspecialchars($partner['name']) ?></h1>
            </div>
        </section>

        <section class="section" aria-label="Partner institution details">
            <div class="container partner-profile">
                <a class="partner-profile__back" href="<?= htmlspecialchars($partnerSiteBase . '/partnerships/partners') ?>">
                    <span aria-hidden="true">&larr;</span> Back to all partners
                </a>

                <div class="partner-profile__layout">
                    <aside class="partner-profile__aside">
                        <?php if ($partner['logo']): ?>
                            <img
                                class="partner-profile__logo"
                                src="<?= htmlspecialchars($partnerAssetBase) ?>/images/partners/<?= htmlspecialchars($partner['logo']) ?>"
                                alt="Emblem for <?= htmlspecialchars($partner['name']) ?>"
                                width="96"
                                height="96"
                            >
                        <?php endif; ?>
                        <dl class="partner-profile__facts">
                            <div>
                                <dt>Region</dt>
                                <dd><?= htmlspecialchars($partnerRegion) ?></dd>
                            </div>
                            <div>
                                <dt>Country</dt>
                                <dd><?= htmlspecialchars($partner['country']) ?></dd>
                            </div>
                        </dl>
                    </aside>

                    <div class="partner-profile__body">
                        <h2>About</h2>
                        <p><?= htmlspecialchars($partner['about']) ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-sm" aria-labelledby="partner-cta-title">
            <div class="container">
                <div class="cta-banner">
                    <div class="cta-banner__inner">
                        <h2 id="partner-cta-title">Explore more partnership opportunities</h2>
                        <p>See other institutions we work with, or reach out to start a new collaboration of your own.</p>
                        <div class="cta-banner__actions">
                            <a class="btn btn-primary" href="<?= htmlspecialchars($partnerSiteBase . '/partnerships/become-a-partner') ?>">Become a partner</a>
                            <a class="btn btn-outline" href="<?= htmlspecialchars($partnerSiteBase . '/partnerships/partners') ?>">See all partners</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
