<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/News.php';

$pageTitle = "News & Events - Busitema University";
$pageDescription = "Updates, partnerships, orientation activities and upcoming events from the Busitema University Internationalization Office.";
$pageCanonical = seo_absolute_url('/news/');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$newsSiteBase = $siteBase ?? '';
$newsAssetBase = $assetBase ?? '/assets';

try {
    $newsItems = News::all();
} catch (Throwable $e) {
    $newsItems = [];
}

$featured = null;
foreach ($newsItems as $candidate) {
    if (!empty($candidate['is_featured'])) {
        $featured = $candidate;
        break;
    }
}
if (!$featured && $newsItems) {
    $featured = $newsItems[0];
}

$restItems = $featured
    ? array_values(array_filter($newsItems, static fn ($item) => $item['id'] !== $featured['id']))
    : $newsItems;

$articleUrl = static function (string $slug) use ($newsSiteBase): string {
    return $newsSiteBase . '/news/article?slug=' . rawurlencode($slug);
};

$formatDate = static function (?string $date): string {
    if (!$date) {
        return '';
    }
    $timestamp = strtotime($date);
    return $timestamp ? date('j F Y', $timestamp) : $date;
};
?>

<main>
    <section class="page-hero" aria-labelledby="news-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">Internationalization Office</p>
            <h1 id="news-hero-title">News &amp; Events</h1>
            <p class="page-hero__lead">
                Updates, partnerships, orientation activities and upcoming events from the Internationalization Office.
            </p>
        </div>
    </section>

    <section class="section" aria-label="Office news and events">
        <div class="container">
            <?php if (!$newsItems): ?>
                <p class="section-description">News and events will appear here shortly.</p>
            <?php else: ?>
                <?php
                    $newsTypes = ['News', 'Event'];
                ?>
                <div class="office-news-tabs" data-news-tabs>
                    <div class="office-news-tabs__nav" role="tablist" aria-label="Filter by type">
                        <button
                            class="office-news-tabs__btn is-active"
                            type="button"
                            role="tab"
                            aria-selected="true"
                            data-news-filter="all"
                        >All</button>
                        <?php foreach ($newsTypes as $type): ?>
                            <button
                                class="office-news-tabs__btn"
                                type="button"
                                role="tab"
                                aria-selected="false"
                                data-news-filter="<?= htmlspecialchars(slugify($type)) ?>"
                            ><?= htmlspecialchars($type) ?>s</button>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($featured): ?>
                        <?php $featuredTypeSlug = slugify($featured['type']); ?>
                        <article class="office-news-feature" data-news-category="<?= htmlspecialchars($featuredTypeSlug) ?>">
                            <?php if (!empty($featured['image'])): ?>
                                <a class="office-news-feature__image" href="<?= htmlspecialchars($articleUrl($featured['slug'])) ?>">
                                    <img
                                        src="<?= htmlspecialchars($newsAssetBase) ?>/images/<?= htmlspecialchars($featured['image']) ?>"
                                        alt="<?= htmlspecialchars($featured['title']) ?>"
                                        loading="lazy"
                                    >
                                </a>
                            <?php endif; ?>
                            <div class="office-news-feature__content">
                                <div class="office-news-card__meta">
                                    <span class="office-news-badge office-news-badge--<?= htmlspecialchars($featuredTypeSlug) ?>"><?= htmlspecialchars($featured['type']) ?></span>
                                    <time><?= htmlspecialchars($formatDate($featured['type'] === 'Event' ? $featured['event_date'] : $featured['published_at'])) ?></time>
                                </div>
                                <h2>
                                    <a href="<?= htmlspecialchars($articleUrl($featured['slug'])) ?>"><?= htmlspecialchars($featured['title']) ?></a>
                                </h2>
                                <p><?= htmlspecialchars($featured['summary']) ?></p>
                                <?php if ($featured['type'] === 'Event' && !empty($featured['location'])): ?>
                                    <p class="office-news-card__location"><span aria-hidden="true">&#128205;</span> <?= htmlspecialchars($featured['location']) ?></p>
                                <?php endif; ?>
                                <a class="office-news-feature__link" href="<?= htmlspecialchars($articleUrl($featured['slug'])) ?>">
                                    Read more <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    <?php endif; ?>

                    <div class="office-news-grid">
                        <?php foreach ($restItems as $item): ?>
                            <?php $typeSlug = slugify($item['type']); ?>
                            <article class="office-news-card" data-news-category="<?= htmlspecialchars($typeSlug) ?>">
                                <?php if (!empty($item['image'])): ?>
                                    <a class="office-news-card__image" href="<?= htmlspecialchars($articleUrl($item['slug'])) ?>">
                                        <img
                                            src="<?= htmlspecialchars($newsAssetBase) ?>/images/<?= htmlspecialchars($item['image']) ?>"
                                            alt="<?= htmlspecialchars($item['title']) ?>"
                                            loading="lazy"
                                        >
                                    </a>
                                <?php endif; ?>
                                <div class="office-news-card__content">
                                    <div class="office-news-card__meta">
                                        <span class="office-news-badge office-news-badge--<?= htmlspecialchars($typeSlug) ?>"><?= htmlspecialchars($item['type']) ?></span>
                                        <time><?= htmlspecialchars($formatDate($item['type'] === 'Event' ? $item['event_date'] : $item['published_at'])) ?></time>
                                    </div>
                                    <h3>
                                        <a href="<?= htmlspecialchars($articleUrl($item['slug'])) ?>"><?= htmlspecialchars($item['title']) ?></a>
                                    </h3>
                                    <p><?= htmlspecialchars($item['summary']) ?></p>
                                    <?php if ($item['type'] === 'Event' && !empty($item['location'])): ?>
                                        <p class="office-news-card__location"><span aria-hidden="true">&#128205;</span> <?= htmlspecialchars($item['location']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <p class="office-news-empty" data-news-empty hidden>Nothing here right now.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-sm" aria-labelledby="news-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <h2 id="news-cta-title">Have something to share?</h2>
                    <p>Reach out to the Internationalization Office to feature your partnership news or upcoming event on this page.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars($newsSiteBase . '/contact/') ?>">Contact our team</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
