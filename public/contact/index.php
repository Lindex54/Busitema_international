<?php
$pageTitle = "Contact Us";
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$contactAssetBase = $assetBase ?? '/assets';

$contactPersons = [
    [
        'name' => 'Grace Nabirye',
        'role' => 'International Relations Officer',
        'bio' => 'Guides incoming international students and partner institutions through admissions, orientation and day-to-day support.',
        'email' => 'grace.nabirye@busitema.ac.ug',
        'phone' => '+256 772 445 210',
        'image' => 'grace-nabirye.svg',
    ],
    [
        'name' => 'David Okwii',
        'role' => 'Partnerships &amp; Exchange Coordinator',
        'bio' => 'Manages exchange agreements, staff mobility and new partnership enquiries from universities and organisations abroad.',
        'email' => 'david.okwii@busitema.ac.ug',
        'phone' => '+256 701 998 344',
        'image' => 'david-okwii.svg',
    ],
];
?>

<main>
    <section class="page-hero" aria-labelledby="page-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">Get in touch</p>
            <h1 id="page-hero-title">We would love to hear from you</h1>
            <p class="page-hero__lead">
                Whether you are an international student planning your journey, a scholar exploring exchange, or an institution
                interested in partnership, our International Relations &amp; Partnerships team is here to help.
            </p>
        </div>
    </section>

    <section class="contact-info" aria-label="Office contact details">
        <div class="container contact-info__grid">
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#128205;</span>
                <div>
                    <h2>Visit us</h2>
                    <p>Jinja &ndash; Malaba Road<br>P.O. Box 236, Tororo, Uganda</p>
                </div>
            </div>
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#128222;</span>
                <div>
                    <h2>Call us</h2>
                    <p><a href="tel:+256454448808">+256 454 448 808</a></p>
                </div>
            </div>
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#9993;</span>
                <div>
                    <h2>Email us</h2>
                    <p><a href="mailto:vc.adm@busitema.ac.ug">vc.adm@busitema.ac.ug</a></p>
                </div>
            </div>
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#128337;</span>
                <div>
                    <h2>Office hours</h2>
                    <p>Mon &ndash; Fri, 8:00am &ndash; 5:00pm</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section contact-people" aria-labelledby="contact-people-title">
        <div class="container">
            <header class="section-header">
                <p class="section-eyebrow">Talk to our team</p>
                <h2 class="section-title" id="contact-people-title">Your contact persons</h2>
                <p class="section-description">
                    Reach out directly to the team members handling international students and institutional partnerships.
                </p>
            </header>

            <div class="contact-people__grid">
                <?php foreach ($contactPersons as $person): ?>
                    <article class="contact-card">
                        <img
                            class="contact-card__photo"
                            src="<?= htmlspecialchars($contactAssetBase) ?>/images/team/<?= htmlspecialchars($person['image']) ?>"
                            alt="Portrait of <?= htmlspecialchars($person['name']) ?>"
                            loading="lazy"
                            width="120"
                            height="120"
                        >
                        <div class="contact-card__body">
                            <h3><?= htmlspecialchars($person['name']) ?></h3>
                            <p class="contact-card__role"><?= $person['role'] ?></p>
                            <p class="contact-card__bio"><?= htmlspecialchars($person['bio']) ?></p>
                            <ul class="contact-card__meta">
                                <li>
                                    <span aria-hidden="true">&#9993;</span>
                                    <a href="mailto:<?= htmlspecialchars($person['email']) ?>"><?= htmlspecialchars($person['email']) ?></a>
                                </li>
                                <li>
                                    <span aria-hidden="true">&#128222;</span>
                                    <a href="tel:<?= htmlspecialchars(str_replace(' ', '', $person['phone'])) ?>"><?= htmlspecialchars($person['phone']) ?></a>
                                </li>
                            </ul>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section section-sm contact-form-section" aria-labelledby="contact-form-title">
        <div class="container contact-form-section__layout">
            <div class="contact-form-section__intro">
                <p class="section-eyebrow">Send a message</p>
                <h2 class="section-title" id="contact-form-title">Write to us directly</h2>
                <p class="section-description">
                    Prefer to send a message instead? Fill in the form and a member of our team will get back to you as soon as possible.
                </p>
            </div>

            <form class="contact-form" method="post" action="#">
                <div class="contact-form__row">
                    <label class="contact-form__field">
                        <span>Full name</span>
                        <input type="text" name="name" placeholder="Your full name" required>
                    </label>
                    <label class="contact-form__field">
                        <span>Email address</span>
                        <input type="email" name="email" placeholder="you@example.com" required>
                    </label>
                </div>
                <label class="contact-form__field">
                    <span>Subject</span>
                    <input type="text" name="subject" placeholder="How can we help?">
                </label>
                <label class="contact-form__field">
                    <span>Message</span>
                    <textarea name="message" rows="5" placeholder="Write your message here..." required></textarea>
                </label>
                <button class="contact-form__submit" type="submit">Send message</button>
            </form>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
