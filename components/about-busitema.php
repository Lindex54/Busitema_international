<?php
$aboutSiteBase = $siteBase ?? '';
$aboutFacts = [
    ['value' => '50,257+', 'label' => 'Students'],
    ['value' => '7', 'label' => 'Faculties'],
    ['value' => '131', 'label' => 'Programmes'],
    ['value' => '450+', 'label' => 'Staff'],
];
?>

<section class="about-busitema" aria-labelledby="about-busitema-title">
    <div class="about-busitema__inner">
        <div class="about-busitema__content">
            <div class="about-busitema__heading">
                <p class="about-busitema__label">About Busitema University</p>
                <h2 id="about-busitema-title">Education shaped by purpose and place</h2>
            </div>

            <div class="about-busitema__story">
                <p class="about-busitema__lead">
                    Busitema University is a public, multi-campus institution advancing science, technology and innovation through practical learning and impactful research.
                </p>
                <p>
                    Established in 2007, the University serves communities across Eastern Uganda while creating opportunities for students and scholars to connect, collaborate and contribute to the wider world.
                </p>

                <div class="about-busitema__actions">
                    <a class="about-busitema__button" href="<?= htmlspecialchars($aboutSiteBase) ?>/about/busitema.php">
                        Learn more about Busitema
                    </a>
                    <a class="about-busitema__text-link" href="<?= htmlspecialchars($aboutSiteBase) ?>/about/campuses.php">
                        Explore our campuses <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="about-busitema__impact">
            <header class="about-busitema__impact-header">
                <h3>Our Impact in Numbers</h3>
                <p>A multi-campus public university committed to practical training and impactful research.</p>
            </header>

            <dl class="about-busitema__facts" aria-label="Busitema University impact in numbers">
                <?php foreach ($aboutFacts as $fact): ?>
                    <div class="about-busitema__fact">
                        <dt><?= htmlspecialchars($fact['value']) ?></dt>
                        <dd><?= htmlspecialchars($fact['label']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
</section>
