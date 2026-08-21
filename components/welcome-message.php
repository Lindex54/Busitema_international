<?php
$welcomeAssetBase = $assetBase ?? '/assets';
$welcomeSiteBase = $siteBase ?? '';
?>

<section class="welcome-message" aria-labelledby="welcome-message-title">
    <div class="welcome-message__inner">
        <header class="welcome-message__intro">
            <div>
                <p class="welcome-message__eyebrow">International at Busitema</p>
                <h2 id="welcome-message-title">A university community open to the world</h2>
            </div>
            <p class="welcome-message__lead">
                Welcome to Busitema University, where local knowledge meets global opportunity and every international connection has the power to create lasting impact.
            </p>
        </header>

        <div class="welcome-message__body">
            <figure class="welcome-message__media">
                <img
                    src="<?= htmlspecialchars($welcomeAssetBase) ?>/images/hero/hero-life-uganda.png"
                    alt="Busitema University students sharing time together on campus"
                    loading="lazy"
                >
                <figcaption class="welcome-message__location">
                    <span>Busitema University</span>
                    Uganda
                </figcaption>
            </figure>

            <div class="welcome-message__content">
                <p class="welcome-message__kicker">Learn. Connect. Create impact.</p>
                <p class="welcome-message__copy">
                    We bring together students, scholars and institutions from across borders to learn, exchange ideas and build practical solutions for our communities. Whether you are joining us to study, teach, conduct research or form a partnership, you will find a collaborative home here in Uganda.
                </p>

                <p class="welcome-message__signature">
                    <strong>International Relations &amp; Partnerships</strong>
                    Busitema University
                </p>

                <a class="welcome-message__link" href="<?= htmlspecialchars($welcomeSiteBase) ?>/about/">
                    Discover Busitema <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</section>
