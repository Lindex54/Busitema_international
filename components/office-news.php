<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/functions.php';
require_once __DIR__ . '/../app/models/News.php';

$officeNewsAssetBase = $assetBase ?? '/assets';
$officeNewsSiteBase = $siteBase ?? '';

try {
    $officeNewsItems = News::latest(3);
} catch (Throwable $e) {
    $officeNewsItems = [];
}

$officeNewsArticleUrl = static function (string $slug) use ($officeNewsSiteBase): string {
    return $officeNewsSiteBase . '/news/article?slug=' . rawurlencode($slug);
};

$officeNewsFormatDate = static function (?string $date): string {
    if (!$date) {
        return '';
    }
    $timestamp = strtotime($date);
    return $timestamp ? date('j F Y', $timestamp) : $date;
};
?>

<?php if ($officeNewsItems): ?>
<section class="office-news-teaser" aria-labelledby="office-news-teaser-title">
    <div class="office-news-teaser__inner">
        <header class="office-news-teaser__header">
            <div>
                <p class="office-news-teaser__eyebrow">From the Internationalization Office</p>
                <h2 id="office-news-teaser-title">Office news &amp; events</h2>
            </div>
            <a class="office-news-teaser__all-link" href="<?= htmlspecialchars($officeNewsSiteBase) ?>/news/">
                View all news &amp; events <span aria-hidden="true">&rarr;</span>
            </a>
        </header>

        <div class="office-news-teaser__grid">
            <?php foreach ($officeNewsItems as $item): ?>
                <?php $typeSlug = slugify($item['type']); ?>
                <article class="office-news-teaser__card">
                    <?php if (!empty($item['image'])): ?>
                        <a class="office-news-teaser__image" href="<?= htmlspecialchars($officeNewsArticleUrl($item['slug'])) ?>">
                            <img
                                src="<?= htmlspecialchars($officeNewsAssetBase) ?>/images/<?= htmlspecialchars(optimized_image_path($item['image'])) ?>"
                                alt="<?= htmlspecialchars($item['title']) ?>"
                                loading="lazy"
                            >
                        </a>
                    <?php endif; ?>
                    <div class="office-news-teaser__content">
                        <div class="office-news-card__meta">
                            <span class="office-news-badge office-news-badge--<?= htmlspecialchars($typeSlug) ?>"><?= htmlspecialchars($item['type']) ?></span>
                            <time><?= htmlspecialchars($officeNewsFormatDate($item['type'] === 'Event' ? $item['event_date'] : $item['published_at'])) ?></time>
                        </div>
                        <h3>
                            <a href="<?= htmlspecialchars($officeNewsArticleUrl($item['slug'])) ?>"><?= htmlspecialchars($item['title']) ?></a>
                        </h3>
                        <p><?= htmlspecialchars($item['summary']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
