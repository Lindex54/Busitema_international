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
        'secondaryUrl' => '/international-students/before-you-arrive',
    ],
    [
        'image' => 'hero-life-uganda.png',
        'eyebrow' => 'Life in Uganda',
        'title' => 'Feel at Home While You Study',
        'description' => 'Find practical guidance on accommodation, culture, health, transport and daily life.',
        'primaryLabel' => 'Student Life',
        'primaryUrl' => '/international-students/student-life',
        'secondaryLabel' => 'Life in Uganda',
        'secondaryUrl' => '/life-in-uganda/',
    ],
    [
        'image' => 'hero-partnerships.png',
        'eyebrow' => 'Partnerships',
        'title' => 'Build Academic Partnerships That Matter',
        'description' => 'Connect with Busitema through research, mobility, capacity building and institutional collaboration.',
        'primaryLabel' => 'Become a Partner',
        'primaryUrl' => '/partnerships/become-a-partner',
        'secondaryLabel' => 'Partnership Areas',
        'secondaryUrl' => '/partnerships/partnership-areas',
    ],
    [
        'image' => 'hero-research-opportunities.png',
        'eyebrow' => 'Scholarships',
        'title' => 'Turn International Ideas Into Action',
        'description' => 'Stay close to scholarships, research calls, events and global collaboration opportunities.',
        'primaryLabel' => 'View Scholarships',
        'primaryUrl' => '/study/scholarships',
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

                <div class="hero-flow__shade" aria-hidden="true"></div>

                <div class="hero-flow__content">
                    <h1><?= htmlspecialchars($slide['title']) ?></h1>
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
