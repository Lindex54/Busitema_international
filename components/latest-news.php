<?php
$newsAssetBase = $assetBase ?? '/assets';
$newsBase = $siteBase ?? '';
$newsItems = [
    [
        'category' => 'Partnerships',
        'date' => '18 August 2026',
        'title' => 'Busitema expands international research collaboration',
        'summary' => 'A new academic partnership opens pathways for joint research, staff exchange and practical learning across institutions.',
        'image' => 'international-partnership-signing.jpg',
        'alt' => 'University leaders signing an international academic partnership agreement',
        'slug' => 'international-research-collaboration',
    ],
    [
        'category' => 'Innovation',
        'date' => '14 August 2026',
        'title' => 'Student innovators present practical solutions for agriculture',
        'summary' => 'Engineering students demonstrated locally built technologies designed to support efficient and climate-aware farming.',
        'image' => 'student-innovation-showcase.jpg',
        'alt' => 'Students presenting a solar-powered agricultural innovation',
        'slug' => 'student-agriculture-innovation',
    ],
    [
        'category' => 'Global Mobility',
        'date' => '9 August 2026',
        'title' => 'International students begin their Busitema experience',
        'summary' => 'New exchange students met campus ambassadors and discovered the academic and cultural life awaiting them in Uganda.',
        'image' => 'international-student-orientation.jpg',
        'alt' => 'International students walking through campus during orientation',
        'slug' => 'international-student-orientation',
    ],
    [
        'category' => 'Research',
        'date' => '2 August 2026',
        'title' => 'Researchers work with farmers on climate-smart agriculture',
        'summary' => 'Field teams are combining scientific evidence and community knowledge to strengthen resilient food systems.',
        'image' => 'climate-smart-field-research.jpg',
        'alt' => 'Researchers and a local farmer conducting agricultural fieldwork',
        'slug' => 'climate-smart-agriculture-research',
    ],
];

$leadNews = $newsItems[0];
$supportingNews = array_slice($newsItems, 1);

$articleUrl = static function (string $slug) use ($newsBase): string {
    return $newsBase . '/news/article?slug=' . rawurlencode($slug);
};
?>

<section class="latest-news" id="latest-news" aria-labelledby="latest-news-title">
    <div class="latest-news__inner">
        <header class="latest-news__header">
            <div>
                <p class="latest-news__eyebrow">From the university</p>
                <h2 id="latest-news-title">Latest news</h2>
            </div>
            <a class="latest-news__all-link" href="<?= htmlspecialchars($newsBase) ?>/news/">
                View all news <span aria-hidden="true">&rarr;</span>
            </a>
        </header>

        <div class="latest-news__layout">
            <article class="latest-news__lead">
                <a class="latest-news__lead-image" href="<?= htmlspecialchars($articleUrl($leadNews['slug'])) ?>">
                    <img
                        src="<?= htmlspecialchars($newsAssetBase) ?>/images/news/<?= htmlspecialchars($leadNews['image']) ?>"
                        alt="<?= htmlspecialchars($leadNews['alt']) ?>"
                        width="1536"
                        height="1024"
                        loading="lazy"
                    >
                </a>
                <div class="latest-news__lead-content">
                    <div class="latest-news__meta">
                        <span><?= htmlspecialchars($leadNews['category']) ?></span>
                        <time datetime="2026-08-18"><?= htmlspecialchars($leadNews['date']) ?></time>
                    </div>
                    <h3>
                        <a href="<?= htmlspecialchars($articleUrl($leadNews['slug'])) ?>"><?= htmlspecialchars($leadNews['title']) ?></a>
                    </h3>
                    <p><?= htmlspecialchars($leadNews['summary']) ?></p>
                    <a class="latest-news__read-link" href="<?= htmlspecialchars($articleUrl($leadNews['slug'])) ?>">
                        Read story <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </article>

            <div class="latest-news__list">
                <?php foreach ($supportingNews as $index => $item): ?>
                    <article class="latest-news__item">
                        <a class="latest-news__item-image" href="<?= htmlspecialchars($articleUrl($item['slug'])) ?>">
                            <img
                                src="<?= htmlspecialchars($newsAssetBase) ?>/images/news/<?= htmlspecialchars($item['image']) ?>"
                                alt="<?= htmlspecialchars($item['alt']) ?>"
                                width="1536"
                                height="1024"
                                loading="lazy"
                            >
                        </a>
                        <div class="latest-news__item-content">
                            <div class="latest-news__meta">
                                <span><?= htmlspecialchars($item['category']) ?></span>
                                <time datetime="<?= ['2026-08-14', '2026-08-09', '2026-08-02'][$index] ?>"><?= htmlspecialchars($item['date']) ?></time>
                            </div>
                            <h3>
                                <a href="<?= htmlspecialchars($articleUrl($item['slug'])) ?>"><?= htmlspecialchars($item['title']) ?></a>
                            </h3>
                            <p><?= htmlspecialchars($item['summary']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
