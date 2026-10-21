<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/News.php';

$requestedSlug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';

try {
    $newsItem = $requestedSlug !== '' ? News::findBySlug($requestedSlug) : null;
} catch (Throwable $e) {
    $newsItem = null;
}

if ($newsItem) {
    $seo = seo_meta([
        'title' => $newsItem['title'] . ' - Busitema University',
        'description' => $newsItem['summary'],
        'image' => $newsItem['image'],
        'overrides' => $newsItem,
        'canonical_path' => '/news/article?slug=' . $newsItem['slug'],
        'type' => 'article',
    ]);
    $pageTitle = $seo['title'];
    $pageDescription = $seo['description'];
    $pageImage = $seo['image'];
    $pageCanonical = $seo['canonical'];
    $pageNoindex = $seo['noindex'];
    $pageType = $seo['type'];

    if ($newsItem['type'] === 'Event') {
        $pageStructuredData = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $newsItem['title'],
            'description' => $seo['description'],
            'startDate' => $newsItem['event_date'],
            'location' => $newsItem['location'] ? [
                '@type' => 'Place',
                'name' => $newsItem['location'],
            ] : null,
            'image' => $seo['image'] ? [$seo['image']] : null,
            'url' => $seo['canonical'],
            'organizer' => [
                '@type' => 'Organization',
                'name' => seo_settings()['seo_site_name'],
            ],
        ], static fn ($value) => $value !== null);
    } else {
        $pageStructuredData = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $newsItem['title'],
            'description' => $seo['description'],
            'datePublished' => $newsItem['published_at'],
            'dateModified' => $newsItem['updated_at'] ?? $newsItem['published_at'],
            'image' => $seo['image'] ? [$seo['image']] : null,
            'url' => $seo['canonical'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => seo_settings()['seo_site_name'],
            ],
        ], static fn ($value) => $value !== null);
    }
} else {
    http_response_code(404);
    $pageTitle = 'Story Not Found - Busitema University';
    $pageNoindex = true;
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$newsSiteBase = $siteBase ?? '';
$newsAssetBase = $assetBase ?? '/assets';

$formatDate = static function (?string $date): string {
    if (!$date) {
        return '';
    }
    $timestamp = strtotime($date);
    return $timestamp ? date('j F Y', $timestamp) : $date;
};
?>

<main>
    <?php if (!$newsItem): ?>
        <section class="section" aria-labelledby="news-not-found-title">
            <div class="container partner-not-found">
                <h1 id="news-not-found-title">Story not found</h1>
                <p class="section-description">
                    We couldn't find the news item or event you were looking for. It may have been removed.
                </p>
                <a class="btn btn-primary" href="<?= htmlspecialchars($newsSiteBase . '/news/') ?>">Back to News &amp; Events</a>
            </div>
        </section>
    <?php else: ?>
        <?php $typeSlug = slugify($newsItem['type']); ?>
        <section class="page-hero" aria-labelledby="news-article-title">
            <div class="page-hero__inner">
                <p class="page-hero__eyebrow"><?= htmlspecialchars($newsItem['type']) ?></p>
                <h1 id="news-article-title"><?= htmlspecialchars($newsItem['title']) ?></h1>
            </div>
        </section>

        <section class="section" aria-label="Story details">
            <div class="container partner-profile">
                <a class="partner-profile__back" href="<?= htmlspecialchars($newsSiteBase . '/news/') ?>">
                    <span aria-hidden="true">&larr;</span> Back to News &amp; Events
                </a>

                <div class="partner-profile__layout">
                    <aside class="partner-profile__aside">
                        <dl class="partner-profile__facts">
                            <div>
                                <dt>Type</dt>
                                <dd>
                                    <span class="office-news-badge office-news-badge--<?= htmlspecialchars($typeSlug) ?>"><?= htmlspecialchars($newsItem['type']) ?></span>
                                </dd>
                            </div>
                            <?php if ($newsItem['type'] === 'Event'): ?>
                                <div>
                                    <dt>Date</dt>
                                    <dd><?= htmlspecialchars($formatDate($newsItem['event_date'])) ?></dd>
                                </div>
                                <?php if (!empty($newsItem['location'])): ?>
                                    <div>
                                        <dt>Location</dt>
                                        <dd><?= htmlspecialchars($newsItem['location']) ?></dd>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div>
                                    <dt>Published</dt>
                                    <dd><?= htmlspecialchars($formatDate($newsItem['published_at'])) ?></dd>
                                </div>
                            <?php endif; ?>
                        </dl>
                    </aside>

                    <div class="partner-profile__body">
                        <?php if (!empty($newsItem['image'])): ?>
                            <img
                                class="office-news-article__image"
                                src="<?= htmlspecialchars($newsAssetBase) ?>/images/<?= htmlspecialchars(optimized_image_path($newsItem['image'])) ?>"
                                alt="<?= htmlspecialchars($newsItem['title']) ?>"
                            >
                        <?php endif; ?>

                        <?php foreach (preg_split('/\r\n\r\n|\r\r|\n\n/', trim($newsItem['body'])) as $paragraph): ?>
                            <p><?= nl2br(htmlspecialchars(trim($paragraph))) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-sm" aria-labelledby="news-article-cta-title">
            <div class="container">
                <div class="cta-banner">
                    <div class="cta-banner__inner">
                        <h2 id="news-article-cta-title">Stay connected</h2>
                        <p>Follow more updates, partnerships and events from the Internationalization Office.</p>
                        <div class="cta-banner__actions">
                            <a class="btn btn-primary" href="<?= htmlspecialchars($newsSiteBase . '/news/') ?>">See all news &amp; events</a>
                            <a class="btn btn-outline" href="<?= htmlspecialchars($newsSiteBase . '/contact/') ?>">Contact our team</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
