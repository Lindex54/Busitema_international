<?php
$pageTitle = "Partnership Areas - Busitema University";
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$partnershipAreas = [
    [
        'icon' => '&#127891;',
        'title' => 'Student Exchange',
        'description' => 'Semester and year-long exchange places for Busitema students abroad and incoming international students on our campuses.',
    ],
    [
        'icon' => '&#128188;',
        'title' => 'Staff & Faculty Mobility',
        'description' => 'Teaching visits, research sabbaticals and training placements that strengthen academic ties between institutions.',
    ],
    [
        'icon' => '&#128300;',
        'title' => 'Joint & Collaborative Research',
        'description' => 'Co-authored publications, shared laboratory access and joint applications for international research funding.',
    ],
    [
        'icon' => '&#127942;',
        'title' => 'Dual & Joint Degree Programmes',
        'description' => 'Structured study pathways that lead to recognised qualifications from Busitema and a partner institution.',
    ],
    [
        'icon' => '&#128218;',
        'title' => 'Capacity Building & Training',
        'description' => 'Short courses, workshops and professional development programmes for academic and administrative staff.',
    ],
    [
        'icon' => '&#128736;',
        'title' => 'Internships & Industrial Attachments',
        'description' => 'Practical placements with partner organisations, giving students hands-on, real-world experience.',
    ],
    [
        'icon' => '&#129309;',
        'title' => 'Community Engagement & Outreach',
        'description' => 'Joint community service, outreach clinics and development projects delivered alongside partner institutions.',
    ],
    [
        'icon' => '&#128176;',
        'title' => 'Grants & Resource Mobilisation',
        'description' => 'Collaborative proposals for research grants, infrastructure development and student support funding.',
    ],
];
?>

<main>
    <section class="page-hero" aria-labelledby="areas-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">How We Collaborate</p>
            <h1 id="areas-hero-title">Partnership areas</h1>
            <p class="page-hero__lead">
                Every partnership is shaped around shared goals. These are the main ways Busitema University works with
                institutions, organisations and agencies internationally.
            </p>
        </div>
    </section>

    <section class="section" aria-label="Areas of partnership">
        <div class="container">
            <div class="area-grid">
                <?php foreach ($partnershipAreas as $area): ?>
                    <article class="area-card">
                        <span class="area-card__icon" aria-hidden="true"><?= $area['icon'] ?></span>
                        <h3><?= htmlspecialchars($area['title']) ?></h3>
                        <p><?= htmlspecialchars($area['description']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section-sm" aria-labelledby="areas-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <h2 id="areas-cta-title">Have a specific idea in mind?</h2>
                    <p>
                        Not every partnership fits a single category. Tell us what you have in mind and our team will help
                        shape the right kind of collaboration.
                    </p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars(($siteBase ?? '') . '/partnerships/become-a-partner') ?>">Become a partner</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars(($siteBase ?? '') . '/partnerships/partners') ?>">See our partners</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
