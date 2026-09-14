<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/ContactPerson.php';

$pageTitle = "Become a Partner - Busitema University";
$pageDescription = "Explore how your institution can become an official partner of Busitema University for student exchange, joint research and staff mobility.";
$pageCanonical = seo_absolute_url('/partnerships/become-a-partner');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$becomePartnerAssetBase = $assetBase ?? '/assets';
$becomePartnerSiteBase = $siteBase ?? '';

try {
    $partnershipsContact = ContactPerson::findByEmail('david.okwii@busitema.ac.ug');
} catch (Throwable $e) {
    $partnershipsContact = null;
}

$processSteps = [
    [
        'title' => 'Initial enquiry',
        'description' => 'Reach out with an outline of your institution and the kind of collaboration you have in mind.',
    ],
    [
        'title' => 'Discussion & scoping',
        'description' => 'Our team meets with you (in person or virtually) to align on shared goals and partnership areas.',
    ],
    [
        'title' => 'Agreement & signing',
        'description' => 'A Memorandum of Understanding is drafted, reviewed and formally signed by both institutions.',
    ],
    [
        'title' => 'Implementation',
        'description' => 'Exchanges, research projects or joint activities begin, with regular check-ins to track progress.',
    ],
];

$benefits = [
    'Access to a diverse, growing student and researcher community in East Africa.',
    'Support from a dedicated International Relations & Partnerships office throughout the agreement.',
    'Opportunities for joint funding applications and research grants.',
    'Cultural exchange experiences for staff and students on both sides.',
    'Flexible partnership models, from single projects to long-term institutional agreements.',
    'Local knowledge and logistics support for visiting staff, students and delegations.',
];
?>

<main>
    <section class="page-hero" aria-labelledby="become-partner-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">Start a Partnership</p>
            <h1 id="become-partner-hero-title">Become a partner institution</h1>
            <p class="page-hero__lead">
                Starting a partnership with Busitema University is a straightforward process, guided by our
                International Relations &amp; Partnerships team from the first conversation to full implementation.
            </p>
        </div>
    </section>

    <section class="section" aria-labelledby="process-title">
        <div class="container">
            <header class="section-header">
                <p class="section-eyebrow">How it works</p>
                <h2 class="section-title" id="process-title">Four steps to a formal partnership</h2>
                <p class="section-description">
                    Most partnerships move from first contact to a signed agreement within a few months, depending on the
                    scope of collaboration.
                </p>
            </header>

            <div class="process-steps">
                <?php foreach ($processSteps as $index => $step): ?>
                    <div class="process-step">
                        <span class="process-step__index"><?= $index + 1 ?></span>
                        <h3><?= htmlspecialchars($step['title']) ?></h3>
                        <p><?= htmlspecialchars($step['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section benefits-section" aria-labelledby="benefits-title">
        <div class="container">
            <header class="section-header">
                <p class="section-eyebrow">Why partner with us</p>
                <h2 class="section-title" id="benefits-title">What partner institutions gain</h2>
            </header>

            <div class="benefits-grid">
                <?php foreach ($benefits as $benefit): ?>
                    <div class="benefit-item">
                        <span class="benefit-item__check" aria-hidden="true">&#10003;</span>
                        <p><?= htmlspecialchars($benefit) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="partner-contact-title">
        <div class="container">
            <header class="section-header">
                <p class="section-eyebrow">Talk to us</p>
                <h2 class="section-title" id="partner-contact-title">Your partnerships contact</h2>
                <p class="section-description">
                    For new partnership enquiries, exchange agreements and MOU discussions, reach out directly to our
                    Partnerships &amp; Exchange Coordinator.
                </p>
            </header>

            <?php if (!$partnershipsContact): ?>
                <p class="section-description">Contact details will appear here shortly &mdash; in the meantime, please use the general <a href="<?= htmlspecialchars($becomePartnerSiteBase . '/contact/') ?>">Contact page</a>.</p>
            <?php else: ?>
                <article class="contact-card contact-card--solo">
                    <?php if ($partnershipsContact['photo']): ?>
                        <img
                            class="contact-card__photo"
                            src="<?= htmlspecialchars($becomePartnerAssetBase) ?>/images/team/<?= htmlspecialchars($partnershipsContact['photo']) ?>"
                            alt="Portrait of <?= htmlspecialchars($partnershipsContact['name']) ?>"
                            loading="lazy"
                            width="120"
                            height="120"
                        >
                    <?php endif; ?>
                    <div class="contact-card__body">
                        <h3><?= htmlspecialchars($partnershipsContact['name']) ?></h3>
                        <p class="contact-card__role"><?= htmlspecialchars($partnershipsContact['role']) ?></p>
                        <?php if ($partnershipsContact['bio']): ?>
                            <p class="contact-card__bio"><?= htmlspecialchars($partnershipsContact['bio']) ?></p>
                        <?php endif; ?>
                        <ul class="contact-card__meta">
                            <li>
                                <span aria-hidden="true">&#9993;</span>
                                <a href="mailto:<?= htmlspecialchars($partnershipsContact['email']) ?>"><?= htmlspecialchars($partnershipsContact['email']) ?></a>
                            </li>
                            <?php if ($partnershipsContact['phone']): ?>
                                <li>
                                    <span aria-hidden="true">&#128222;</span>
                                    <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $partnershipsContact['phone'])) ?>"><?= htmlspecialchars($partnershipsContact['phone']) ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </article>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-sm" aria-labelledby="partner-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <h2 id="partner-cta-title">Let's build something together</h2>
                    <p>Send us a message with a short summary of your institution and partnership interests to get started.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars($becomePartnerSiteBase) ?>/contact/">Start the conversation</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars($becomePartnerSiteBase) ?>/partnerships/partnership-areas">See partnership areas</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
