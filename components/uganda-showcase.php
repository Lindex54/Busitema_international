<?php
$ugandaAssetBase = $assetBase ?? '/assets';
$ugandaStories = [
    [
        'title' => 'Community',
        'description' => 'Learning here reaches beyond lecture rooms. Students work alongside local communities, sharing knowledge and building practical solutions together.',
        'image' => 'community-outreach.png',
        'alt' => 'Students and community members working together in a garden',
    ],
    [
        'title' => 'Climate & Geography',
        'description' => 'Eastern Uganda brings together fertile farmland, warm tropical weather, dramatic rock formations and the highlands surrounding Mount Elgon.',
        'image' => 'climate-geography.png',
        'alt' => 'Green Eastern Ugandan landscape with farmland and distant mountains',
    ],
    [
        'title' => 'Diversity',
        'description' => 'Busitema connects Ugandan and international students through everyday campus life, shared study and friendships that cross borders.',
        'image' => 'student-diversity.png',
        'alt' => 'Students from different backgrounds walking together on campus',
    ],
    [
        'title' => 'Culture',
        'description' => 'Music, dance, language and community gatherings offer meaningful ways to understand the living cultures of Eastern Uganda.',
        'image' => 'culture.png',
        'alt' => 'Community members sharing music and dance at a cultural gathering',
    ],
    [
        'title' => 'Food',
        'description' => 'Local meals bring people together through matooke, groundnut sauce, fresh greens, beans, fish and the generosity of shared plates.',
        'image' => 'ugandan-food.png',
        'alt' => 'A shared table of Ugandan dishes including matooke, beans, greens and fish',
    ],
    [
        'title' => 'Hospitality',
        'description' => 'Hospitality is part of daily life: time offered freely, conversations over tea and practical support as newcomers settle into Uganda.',
        'image' => 'hospitality.png',
        'alt' => 'A Ugandan host welcoming international visitors with tea',
    ],
];
?>

<section class="uganda-showcase" aria-labelledby="uganda-showcase-title" data-uganda-showcase>
    <div class="uganda-showcase__layout">
        <aside class="uganda-showcase__panel">
            <div class="uganda-showcase__panel-inner">
                <h2 class="uganda-showcase__eyebrow" id="uganda-showcase-title">Life in Uganda</h2>
                <p class="uganda-showcase__intro">Discover the landscapes, people and everyday experiences that shape life beyond the classroom.</p>

                <nav class="uganda-showcase__menu" aria-label="Life in Uganda topics">
                    <?php foreach ($ugandaStories as $index => $story): ?>
                        <button
                            class="uganda-showcase__menu-button<?= $index === 0 ? ' is-active' : '' ?>"
                            type="button"
                            data-uganda-target="<?= $index ?>"
                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        ><?= htmlspecialchars($story['title']) ?></button>
                    <?php endforeach; ?>
                </nav>

                <div class="uganda-showcase__active-copy" aria-live="polite">
                    <p data-uganda-description><?= htmlspecialchars($ugandaStories[0]['description']) ?></p>
                </div>
            </div>
        </aside>

        <div class="uganda-showcase__gallery">
            <?php foreach ($ugandaStories as $index => $story): ?>
                <figure
                    class="uganda-showcase__story<?= $index === 0 ? ' is-active' : '' ?>"
                    data-uganda-story="<?= $index ?>"
                    data-uganda-description="<?= htmlspecialchars($story['description']) ?>"
                >
                    <img
                        src="<?= htmlspecialchars($ugandaAssetBase) ?>/images/uganda/<?= htmlspecialchars($story['image']) ?>"
                        alt="<?= htmlspecialchars($story['alt']) ?>"
                        loading="lazy"
                    >
                    <figcaption>
                        <strong><?= htmlspecialchars($story['title']) ?></strong>
                        <p><?= htmlspecialchars($story['description']) ?></p>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
