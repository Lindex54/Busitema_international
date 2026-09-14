<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/Opportunity.php';

$pageTitle = "Scholarships - Busitema University";
$pageDescription = "Scholarships, staff and student exchange, research grants, internships and fellowships available through Busitema University and our partner institutions.";
$pageCanonical = seo_absolute_url('/study/scholarships');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$opportunitiesSiteBase = $siteBase ?? '';

try {
    $opportunities = Opportunity::all();
    $categories = Opportunity::distinctCategories();
} catch (Throwable $e) {
    $opportunities = [];
    $categories = [];
}
?>

<main>
    <section class="page-hero" aria-labelledby="opportunities-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">Study at Busitema</p>
            <h1 id="opportunities-hero-title">Scholarships, exchange and grants</h1>
            <p class="page-hero__lead">
                Scholarships, staff and student exchange, research grants, internships and fellowships available
                through Busitema University and our partner institutions.
            </p>
        </div>
    </section>

    <section class="section" aria-label="Opportunity listings">
        <div class="container">
            <?php if (!$opportunities): ?>
                <p class="section-description">Opportunities will appear here shortly.</p>
            <?php else: ?>
                <div class="opportunity-tabs" data-opportunity-tabs>
                    <div class="opportunity-tabs__nav" role="tablist" aria-label="Filter opportunities by category">
                        <button
                            class="opportunity-tabs__btn is-active"
                            type="button"
                            role="tab"
                            aria-selected="true"
                            data-opportunity-filter="all"
                        >All</button>
                        <?php foreach ($categories as $category): ?>
                            <button
                                class="opportunity-tabs__btn"
                                type="button"
                                role="tab"
                                aria-selected="false"
                                data-opportunity-filter="<?= htmlspecialchars(slugify($category)) ?>"
                            ><?= htmlspecialchars($category) ?></button>
                        <?php endforeach; ?>
                    </div>

                    <div class="opportunity-grid">
                        <?php foreach ($opportunities as $opportunity): ?>
                            <?php $statusClass = 'is-' . slugify($opportunity['status']); ?>
                            <article
                                class="opportunity-card"
                                data-opportunity-category="<?= htmlspecialchars(slugify($opportunity['category'])) ?>"
                            >
                                <div class="opportunity-card__badges">
                                    <span class="opportunity-badge opportunity-badge--category"><?= htmlspecialchars($opportunity['category']) ?></span>
                                    <span class="opportunity-badge opportunity-badge--status <?= htmlspecialchars($statusClass) ?>"><?= htmlspecialchars($opportunity['status']) ?></span>
                                </div>
                                <h3><?= htmlspecialchars($opportunity['title']) ?></h3>
                                <p class="opportunity-card__summary"><?= htmlspecialchars($opportunity['summary']) ?></p>
                                <p class="opportunity-card__deadline">
                                    <strong>Deadline:</strong> <?= htmlspecialchars($opportunity['deadline']) ?>
                                </p>
                                <a
                                    class="opportunity-card__link"
                                    href="<?= htmlspecialchars($opportunitiesSiteBase . '/study/scholarship?slug=' . urlencode(slugify($opportunity['title']))) ?>"
                                >View details <span aria-hidden="true">&rarr;</span></a>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <p class="opportunity-empty" data-opportunity-empty hidden>No opportunities in this category right now.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-sm" aria-labelledby="opportunities-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <h2 id="opportunities-cta-title">Don't see the right fit?</h2>
                    <p>New opportunities are added regularly through our partner network. Get in touch and we'll help point you in the right direction.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars($opportunitiesSiteBase . '/contact/') ?>">Contact our team</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars($opportunitiesSiteBase . '/partnerships/') ?>">Explore partnerships</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
