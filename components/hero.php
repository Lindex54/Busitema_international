<?php
$heroAssetBase = $assetBase ?? '/assets';
$heroSiteBase = $siteBase ?? '';
$heroSlides = [
    [
        'image' => 'hero-campus-students.png',
        'eyebrow' => 'Global Connections. Local Impact.',
        'title' => 'Welcome to Busitema University',
        'description' => 'Discover study pathways, partnerships, mobility opportunities and a vibrant student life in Uganda.',
        'primaryLabel' => 'Study at Busitema',
        'primaryUrl' => '/study/',
        'secondaryLabel' => 'Partner with Us',
        'secondaryUrl' => '/partnerships/',
    ],
    [
        'image' => 'hero-mobility-exchange.png',
        'eyebrow' => 'Student & Staff Mobility',
        'title' => 'Move Across Borders With Purpose',
        'description' => 'Explore exchange programmes, visiting scholar routes and international academic experiences.',
        'primaryLabel' => 'Explore Mobility',
        'primaryUrl' => '/mobility/',
        'secondaryLabel' => 'Before You Arrive',
        'secondaryUrl' => '/international-students/before-you-arrive.php',
    ],
    [
        'image' => 'hero-life-uganda.png',
        'eyebrow' => 'Life in Uganda',
        'title' => 'Feel at Home While You Study',
        'description' => 'Find practical guidance on accommodation, culture, health, transport and daily life.',
        'primaryLabel' => 'Student Life',
        'primaryUrl' => '/international-students/student-life.php',
        'secondaryLabel' => 'Life in Uganda',
        'secondaryUrl' => '/life-in-uganda/',
    ],
    [
        'image' => 'hero-partnerships.png',
        'eyebrow' => 'Partnerships',
        'title' => 'Build Academic Partnerships That Matter',
        'description' => 'Connect with Busitema through research, mobility, capacity building and institutional collaboration.',
        'primaryLabel' => 'Become a Partner',
        'primaryUrl' => '/partnerships/become-a-partner.php',
        'secondaryLabel' => 'Partnership Areas',
        'secondaryUrl' => '/partnerships/partnership-areas.php',
    ],
    [
        'image' => 'hero-research-opportunities.png',
        'eyebrow' => 'Opportunities',
        'title' => 'Turn International Ideas Into Action',
        'description' => 'Stay close to scholarships, research calls, events and global collaboration opportunities.',
        'primaryLabel' => 'View Opportunities',
        'primaryUrl' => '/opportunities/',
        'secondaryLabel' => 'News & Events',
        'secondaryUrl' => '/news/',
    ],
];
?>

<section class="hero-flow" aria-label="Busitema international highlights">
    <div class="hero-flow__viewport" data-hero-flow>
        <?php foreach ($heroSlides as $index => $slide): ?>
            <?php
            $imageUrl = $heroAssetBase . '/images/hero/' . $slide['image'];
            $isActive = $index === 0;
            ?>
            <article
                class="hero-flow__slide<?= $isActive ? ' is-active' : '' ?>"
                data-image="<?= htmlspecialchars($imageUrl) ?>"
                aria-hidden="<?= $isActive ? 'false' : 'true' ?>"
            >
                <div class="hero-flow__image" style="background-image: url('<?= htmlspecialchars($imageUrl) ?>');"></div>

                <?php for ($slice = 1; $slice <= 4; $slice++): ?>
                    <div
                        class="hero-flow__slice hero-flow__slice--<?= $slice ?>"
                        style="background-image: url('<?= htmlspecialchars($imageUrl) ?>');"
                        aria-hidden="true"
                    ></div>
                <?php endfor; ?>

                <div class="hero-flow__shade" aria-hidden="true"></div>

                <div class="hero-flow__content">
                    <p class="hero-flow__eyebrow"><?= htmlspecialchars($slide['eyebrow']) ?></p>
                    <h1><?= htmlspecialchars($slide['title']) ?></h1>
                    <p class="hero-flow__description"><?= htmlspecialchars($slide['description']) ?></p>
                    <div class="hero-flow__actions">
                        <a class="hero-flow__button hero-flow__button--primary" href="<?= htmlspecialchars($heroSiteBase . $slide['primaryUrl']) ?>">
                            <?= htmlspecialchars($slide['primaryLabel']) ?>
                        </a>
                        <a class="hero-flow__button hero-flow__button--secondary" href="<?= htmlspecialchars($heroSiteBase . $slide['secondaryUrl']) ?>">
                            <?= htmlspecialchars($slide['secondaryLabel']) ?>
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="hero-flow__pagination" aria-label="Hero slides">
            <?php foreach ($heroSlides as $index => $slide): ?>
                <button
                    class="hero-flow__dot<?= $index === 0 ? ' is-active' : '' ?>"
                    type="button"
                    data-hero-slide="<?= $index ?>"
                    aria-label="Show <?= htmlspecialchars($slide['eyebrow']) ?>"
                    aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                ></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
