<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/helpers/seo.php';

$pageTitle = "Internationalization & Partnerships Office | Busitema University";
$pageDescription = "We create and support international relationships that open new possibilities for Busitema University students, staff, researchers and partner institutions.";
$pageCanonical = seo_absolute_url('/about/internationalization-office');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$officeAssetBase = $assetBase ?? '/assets';
$officeSiteBase = $siteBase ?? '';
?>

<main>
    <section class="office-hero" aria-labelledby="office-hero-title">
        <div class="office-hero__content">
            <div class="office-hero__copy">
                <h1 id="office-hero-title">Connecting Busitema to the world</h1>
                <p>
                    We create and support international relationships that open new possibilities for students,
                    staff, researchers and partner institutions.
                </p>
                <div class="office-hero__actions">
                    <a class="btn btn-primary" href="<?= htmlspecialchars($officeSiteBase) ?>/contact/">Talk to our team</a>
                    <a class="office-hero__text-link" href="<?= htmlspecialchars($officeSiteBase) ?>/partnerships/">
                        Explore partnerships <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="office-hero__media">
            <img
                src="<?= htmlspecialchars($officeAssetBase) ?>/images/hero/hero-partnerships.jpg"
                alt="University representatives meeting and shaking hands around a conference table"
                width="1672"
                height="941"
            >
        </div>
    </section>

    <section class="section office-intro" aria-labelledby="office-intro-title">
        <div class="container office-intro__layout">
            <div class="office-intro__copy">
                <p class="section-eyebrow">Who we are</p>
                <h2 class="section-title" id="office-intro-title">A gateway to global opportunity</h2>
                <p class="section-description">
                    The Internationalization &amp; Partnerships Office leads Busitema University&rsquo;s global engagement.
                    We bring people and institutions together, turning shared ideas into lasting academic relationships
                    and practical opportunities.
                </p>
                <p class="office-intro__body">
                    Working across the university, our office supports every stage of international collaboration &mdash;
                    from the first conversation and formal agreement to mobility, project delivery and ongoing partner care.
                </p>
                <div class="office-location-inline">
                    <span class="office-location-inline__icon" aria-hidden="true">&#128205;</span>
                    <div>
                        <p class="office-location-inline__label">Visit our office</p>
                        <h3>Engineering Library, ground floor</h3>
                        <p>Busitema University Main Campus</p>
                    </div>
                </div>
            </div>

            <aside class="office-purpose" aria-label="Our purpose">
                <span class="office-purpose__mark" aria-hidden="true">&#10022;</span>
                <p class="office-purpose__label">Our purpose</p>
                <blockquote>
                    To make international engagement accessible, purposeful and valuable for the entire Busitema community.
                </blockquote>
                <ul class="office-purpose__list">
                    <li><span aria-hidden="true">&#10003;</span> Build trusted global relationships</li>
                    <li><span aria-hidden="true">&#10003;</span> Expand learning and research opportunities</li>
                    <li><span aria-hidden="true">&#10003;</span> Support a welcoming university community</li>
                </ul>
            </aside>
        </div>
    </section>

    <section class="section office-pathways" aria-labelledby="office-pathways-title">
        <div class="container">
            <header class="section-header">
                <p class="section-eyebrow">Start here</p>
                <h2 class="section-title" id="office-pathways-title">How can we help you?</h2>
            </header>

            <div class="office-pathways__grid">
                <article class="office-pathway office-pathway--student">
                    <span class="office-pathway__number">01</span>
                    <div class="office-pathway__body">
                        <p class="office-pathway__eyebrow">Students &amp; scholars</p>
                        <h3>Plan your Busitema experience</h3>
                        <p>Find practical guidance for studying, exchanging or carrying out academic work with us in Uganda.</p>
                        <a href="<?= htmlspecialchars($officeSiteBase) ?>/international-students/">
                            International student information <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </article>

                <article class="office-pathway office-pathway--partner">
                    <span class="office-pathway__number">02</span>
                    <div class="office-pathway__body">
                        <p class="office-pathway__eyebrow">Institutions &amp; organisations</p>
                        <h3>Build something meaningful with us</h3>
                        <p>Explore our collaboration areas and learn how to begin a new relationship with Busitema University.</p>
                        <a href="<?= htmlspecialchars($officeSiteBase) ?>/partnerships/become-a-partner">
                            Become a partner <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section section-sm office-contact" aria-labelledby="office-contact-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <p class="office-contact__eyebrow">Let&rsquo;s connect</p>
                    <h2 id="office-contact-title">Your international journey starts with a conversation</h2>
                    <p>Tell us what you are exploring and our team will help connect you with the right information and people.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars($officeSiteBase) ?>/contact/">Contact the office</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars($officeSiteBase) ?>/mobility/">Explore mobility</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
