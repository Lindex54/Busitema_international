<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/Opportunity.php';

$requestedSlug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';

try {
    $opportunity = $requestedSlug !== '' ? Opportunity::findBySlug($requestedSlug) : null;
} catch (Throwable $e) {
    $opportunity = null;
}

if ($opportunity) {
    // No structured data type here (Schema.org has no clean "Scholarship"
    // type, and the deadline is free text, not a reliable ISO date) -
    // meta tags only, rather than forcing an inaccurate schema.
    $seo = seo_meta([
        'title' => $opportunity['title'] . ' - Busitema University',
        'description' => $opportunity['summary'],
        'image' => null,
        'overrides' => $opportunity,
        'canonical_path' => '/study/scholarship?slug=' . $opportunity['slug'],
    ]);
    $pageTitle = $seo['title'];
    $pageDescription = $seo['description'];
    $pageImage = $seo['image'];
    $pageCanonical = $seo['canonical'];
    $pageNoindex = $seo['noindex'];
} else {
    http_response_code(404);
    $pageTitle = 'Opportunity Not Found - Busitema University';
    $pageNoindex = true;
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$opportunitySiteBase = $siteBase ?? '';
?>

<main>
    <?php if (!$opportunity): ?>
        <section class="section" aria-labelledby="opportunity-not-found-title">
            <div class="container partner-not-found">
                <h1 id="opportunity-not-found-title">Opportunity not found</h1>
                <p class="section-description">
                    We couldn't find the opportunity you were looking for. It may have closed or been removed.
                </p>
                <a class="btn btn-primary" href="<?= htmlspecialchars($opportunitySiteBase . '/study/scholarships') ?>">Back to all opportunities</a>
            </div>
        </section>
    <?php else: ?>
        <?php $statusClass = 'is-' . slugify($opportunity['status']); ?>
        <section class="page-hero" aria-labelledby="opportunity-hero-title">
            <div class="page-hero__inner">
                <p class="page-hero__eyebrow"><?= htmlspecialchars($opportunity['category']) ?> &middot; <?= htmlspecialchars($opportunity['status']) ?></p>
                <h1 id="opportunity-hero-title"><?= htmlspecialchars($opportunity['title']) ?></h1>
            </div>
        </section>

        <section class="section" aria-label="Opportunity details">
            <div class="container partner-profile">
                <a class="partner-profile__back" href="<?= htmlspecialchars($opportunitySiteBase . '/study/scholarships') ?>">
                    <span aria-hidden="true">&larr;</span> Back to all opportunities
                </a>

                <div class="partner-profile__layout">
                    <aside class="partner-profile__aside">
                        <dl class="partner-profile__facts">
                            <div>
                                <dt>Category</dt>
                                <dd><?= htmlspecialchars($opportunity['category']) ?></dd>
                            </div>
                            <div>
                                <dt>Status</dt>
                                <dd>
                                    <span class="opportunity-badge opportunity-badge--status <?= htmlspecialchars($statusClass) ?>"><?= htmlspecialchars($opportunity['status']) ?></span>
                                </dd>
                            </div>
                            <div>
                                <dt>Deadline</dt>
                                <dd><?= htmlspecialchars($opportunity['deadline']) ?></dd>
                            </div>
                        </dl>
                    </aside>

                    <?php
                    $eligibilityItems = Opportunity::linesToList($opportunity['eligibility'] ?? null);
                    $howToApplyItems = Opportunity::linesToList($opportunity['how_to_apply'] ?? null);
                    ?>
                    <div class="partner-profile__body">
                        <h2>Overview</h2>
                        <p><?= htmlspecialchars($opportunity['overview']) ?></p>

                        <?php if ($eligibilityItems): ?>
                            <h2>Eligibility</h2>
                            <ul class="opportunity-detail__list">
                                <?php foreach ($eligibilityItems as $item): ?>
                                    <li><?= htmlspecialchars($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if ($howToApplyItems): ?>
                            <h2>How to apply</h2>
                            <ol class="opportunity-detail__list opportunity-detail__list--steps">
                                <?php foreach ($howToApplyItems as $step): ?>
                                    <li><?= htmlspecialchars($step) ?></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-sm" aria-labelledby="opportunity-cta-title">
            <div class="container">
                <div class="cta-banner">
                    <div class="cta-banner__inner">
                        <h2 id="opportunity-cta-title">Have questions before you apply?</h2>
                        <p>Our team can help you understand eligibility, deadlines and next steps.</p>
                        <div class="cta-banner__actions">
                            <a class="btn btn-primary" href="<?= htmlspecialchars($opportunitySiteBase . '/contact/') ?>">Contact our team</a>
                            <a class="btn btn-outline" href="<?= htmlspecialchars($opportunitySiteBase . '/study/scholarships') ?>">See all opportunities</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
