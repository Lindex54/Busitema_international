<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/helpers/seo.php';

$pageTitle = "Global Partnerships - Busitema University";
$pageDescription = "Busitema University's international partnerships with universities and institutions worldwide, supporting student exchange, joint research and staff mobility.";
$pageCanonical = seo_absolute_url('/partnerships/');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$partnershipsSiteBase = $siteBase ?? '';

$partnershipStats = [
    ['value' => '45+', 'label' => 'Partner institutions'],
    ['value' => '20+', 'label' => 'Countries represented'],
    ['value' => '30+', 'label' => 'Active MOUs & agreements'],
    ['value' => '500+', 'label' => 'Students & staff exchanged'],
];

$exploreLinks = [
    [
        'icon' => '&#127760;',
        'title' => 'Our Partners',
        'description' => 'Meet the universities, colleges and organisations Busitema collaborates with across the world.',
        'href' => '/partnerships/partners',
    ],
    [
        'icon' => '&#129309;',
        'title' => 'Partnership Areas',
        'description' => 'Explore the ways we work together, from student exchange to joint research and capacity building.',
        'href' => '/partnerships/partnership-areas',
    ],
    [
        'icon' => '&#128221;',
        'title' => 'Become a Partner',
        'description' => 'Learn how your institution or organisation can start a formal partnership with Busitema University.',
        'href' => '/partnerships/become-a-partner',
    ],
];
?>

<main>
    <section class="page-hero" aria-labelledby="partnerships-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">International Partnerships</p>
            <h1 id="partnerships-hero-title">Building bridges across borders</h1>
            <p class="page-hero__lead">
                Busitema University works with institutions and organisations around the world to expand opportunities
                for research, teaching, exchange and community impact &mdash; here and beyond Uganda.
            </p>
        </div>
    </section>

    <section class="stat-strip" aria-label="Partnerships at a glance">
        <div class="container stat-strip__grid">
            <?php foreach ($partnershipStats as $stat): ?>
                <div class="stat-item">
                    <p class="stat-item__value"><?= htmlspecialchars($stat['value']) ?></p>
                    <p class="stat-item__label"><?= htmlspecialchars($stat['label']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section" aria-labelledby="partnerships-why-title">
        <div class="container">
            <header class="section-header">
                <p class="section-eyebrow">Why partner with Busitema</p>
                <h2 class="section-title" id="partnerships-why-title">A collaborative, practical approach</h2>
                <p class="section-description">
                    We believe international collaboration should create real value for students, staff and communities on
                    both sides. Our partnerships office manages agreements from first enquiry through to implementation,
                    making it straightforward to start working with us.
                </p>
            </header>

            <div class="explore-grid">
                <?php foreach ($exploreLinks as $link): ?>
                    <a class="explore-card" href="<?= htmlspecialchars($partnershipsSiteBase . $link['href']) ?>">
                        <span class="explore-card__icon" aria-hidden="true"><?= $link['icon'] ?></span>
                        <h3><?= htmlspecialchars($link['title']) ?></h3>
                        <p><?= htmlspecialchars($link['description']) ?></p>
                        <span class="explore-card__link">Explore <span aria-hidden="true">&rarr;</span></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section-sm" aria-labelledby="partnerships-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <h2 id="partnerships-cta-title">Ready to start a partnership?</h2>
                    <p>
                        Whether you represent a university, government agency or organisation, our team is ready to discuss
                        how we can work together.
                    </p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars($partnershipsSiteBase) ?>/partnerships/become-a-partner">Become a partner</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars($partnershipsSiteBase) ?>/contact/">Contact our team</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
