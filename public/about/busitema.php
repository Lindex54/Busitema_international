<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/Campus.php';

$pageTitle = "About Busitema University | Busitema International";
$pageDescription = "Busitema University is a public multi-campus institution in Eastern Uganda, established in 2007 to expand access to quality higher education through teaching, research and innovation.";
$pageCanonical = seo_absolute_url('/about/busitema');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$aboutAssetBase = $assetBase ?? '/assets';
$aboutSiteBase = $siteBase ?? '';

try {
    $campuses = Campus::all();
} catch (Throwable $e) {
    $campuses = [];
}
?>

<main>
    <section class="about-university-hero" aria-labelledby="about-university-title">
        <img
            class="about-university-hero__image"
            src="<?= htmlspecialchars($aboutAssetBase) ?>/images/hero/hero-campus-students.jpg"
            alt="Students walking together across a university campus"
            width="1672"
            height="941"
        >
        <div class="about-university-hero__overlay"></div>
        <div class="container about-university-hero__content">
            <p class="about-university-hero__eyebrow">About Busitema University</p>
            <h1 id="about-university-title">One university.<br>Many communities.</h1>
            <p>
                A public multi-campus university advancing practical education, research and innovation
                across Eastern Uganda.
            </p>
        </div>
    </section>

    <section class="about-university-facts" aria-label="Busitema University at a glance">
        <div class="container about-university-facts__grid">
            <div class="about-university-fact">
                <strong>2007</strong>
                <span>Year established</span>
            </div>
            <div class="about-university-fact">
                <strong><?= count($campuses) ?></strong>
                <span>Campus locations</span>
            </div>
            <div class="about-university-fact">
                <strong><?= count(array_unique(array_column($campuses, 'district'))) ?></strong>
                <span>Districts and cities</span>
            </div>
            <div class="about-university-fact">
                <strong>Eastern Uganda</strong>
                <span>Regional footprint</span>
            </div>
        </div>
    </section>

    <section class="section about-university-story" aria-labelledby="about-story-title">
        <div class="container about-university-story__layout">
            <div class="about-university-story__heading">
                <p class="section-eyebrow">Our story</p>
                <h2 class="section-title" id="about-story-title">Built to expand access and serve the nation</h2>
            </div>
            <div class="about-university-story__copy">
                <p>
                    Busitema University is a public multi-campus institution established by the Government of Uganda
                    under Statutory Instrument No. 22 of 2007, in accordance with the Universities and Other Tertiary
                    Institutions Act, 2001. The University was founded to expand access to quality higher education and
                    to contribute to national development through teaching, research, innovation and community engagement.
                </p>
                <p>
                    The University officially opened its doors to students in October 2007 and adopted a unique
                    multi-campus model that brought together several well-established tertiary institutions across
                    Eastern Uganda.
                </p>
                <p>
                    This approach enabled Busitema to build on the rich heritage, infrastructure and expertise of those
                    institutions while responding to the growing demand for university education in science, technology,
                    agriculture, health sciences, education, natural resources and management sciences.
                </p>
            </div>
        </div>
    </section>

    <section class="about-university-identity" aria-label="University vision and mission">
        <div class="container about-university-identity__grid">
            <article class="about-university-identity__item">
                <p class="section-eyebrow">Our vision</p>
                <h2>A centre of academic and professional excellence in science, technology and innovation.</h2>
            </article>
            <article class="about-university-identity__item about-university-identity__item--gold">
                <p class="section-eyebrow">Our mission</p>
                <h2>To provide high-standard training, engage in quality research and outreach for socio-economic transformation and sustainable development.</h2>
            </article>
        </div>
    </section>

    <section class="section about-campuses" aria-labelledby="about-campuses-title">
        <div class="container">
            <header class="section-header about-campuses__header">
                <p class="section-eyebrow">Our campuses</p>
                <h2 class="section-title" id="about-campuses-title">A university shaped by place</h2>
                <p class="section-description">
                    Each campus has a distinct academic focus, bringing specialised teaching, research and community
                    engagement into one shared university system.
                </p>
            </header>

            <?php if (!$campuses): ?>
                <p class="section-description">Campus information will appear here shortly.</p>
            <?php else: ?>
                <div class="about-campuses__grid">
                    <?php foreach ($campuses as $campus): ?>
                        <article class="about-campus-card<?= !empty($campus['is_featured']) ? ' about-campus-card--featured' : '' ?><?= !empty($campus['is_new']) ? ' about-campus-card--new' : '' ?>">
                            <div class="about-campus-card__topline">
                                <span class="about-campus-card__district"><?= htmlspecialchars($campus['district']) ?></span>
                                <?php if (!empty($campus['is_new'])): ?>
                                    <span class="about-campus-card__new">New</span>
                                <?php endif; ?>
                            </div>
                            <h3><?= htmlspecialchars($campus['name']) ?></h3>
                            <p class="about-campus-card__focus"><?= htmlspecialchars($campus['focus']) ?></p>
                            <p class="about-campus-card__description"><?= htmlspecialchars($campus['description']) ?></p>
                            <?php if (!empty($campus['link'])): ?>
                                <a class="about-campus-card__link" href="<?= htmlspecialchars($campus['link']) ?>" target="_blank" rel="noopener noreferrer">
                                    Visit campus site
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-sm about-university-cta" aria-labelledby="about-university-cta-title">
        <div class="container">
            <div class="cta-banner">
                <div class="cta-banner__inner">
                    <p class="about-university-cta__eyebrow">Pursuing excellence</p>
                    <h2 id="about-university-cta-title">Find your place at Busitema</h2>
                    <p>Explore programmes designed around practical learning, innovation and Uganda&rsquo;s development priorities.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn-primary" href="<?= htmlspecialchars($aboutSiteBase) ?>/study/programmes">Explore programmes</a>
                        <a class="btn btn-outline" href="<?= htmlspecialchars($aboutSiteBase) ?>/study/how-to-apply">How to apply</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
