<?php
$aboutSiteBase = $siteBase ?? '';
$aboutFacts = [
    ['value' => 50257, 'suffix' => '+', 'label' => 'Students'],
    ['value' => 7, 'suffix' => '', 'label' => 'Faculties'],
    ['value' => 131, 'suffix' => '', 'label' => 'Programmes'],
    ['value' => 450, 'suffix' => '+', 'label' => 'Staff'],
];
?>

<section class="about-busitema" aria-labelledby="about-busitema-title">
    <div class="about-busitema__inner">
        <div class="about-busitema__content">
            <header class="about-busitema__heading">
                <h2 id="about-busitema-title">About Busitema</h2>
            </header>

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
            <dl class="about-busitema__facts" aria-label="Busitema University impact in numbers">
                <?php foreach ($aboutFacts as $fact): ?>
                    <div class="about-busitema__fact">
                        <dt
                            data-count-up
                            data-count-to="<?= $fact['value'] ?>"
                            data-count-suffix="<?= htmlspecialchars($fact['suffix']) ?>"
                        ><?= number_format($fact['value']) ?><?= htmlspecialchars($fact['suffix']) ?></dt>
                        <dd><?= htmlspecialchars($fact['label']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
</section>
