<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/Setting.php';
require_once __DIR__ . '/../../app/models/ContactPerson.php';

$pageTitle = "Contact Us - Busitema University";
$pageDescription = "Get in touch with the Busitema University Internationalization & Partnerships Office - office address, phone, email and our contact team.";
$pageCanonical = seo_absolute_url('/contact/');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$contactAssetBase = $assetBase ?? '/assets';

$officeDefaults = [
    'office_address_line1' => 'Jinja - Malaba Road',
    'office_address_line2' => 'P.O. Box 236, Tororo, Uganda',
    'office_phone' => '+256 454 448 808',
    'office_email' => 'vc.adm@busitema.ac.ug',
    'office_hours' => 'Mon - Fri, 8:00am - 5:00pm',
];

try {
    $office = array_merge($officeDefaults, array_filter(Setting::all()));
    $contactPersons = ContactPerson::all();
} catch (Throwable $e) {
    $office = $officeDefaults;
    $contactPersons = [];
}

$officePhoneHref = preg_replace('/[^0-9+]/', '', $office['office_phone']);
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
                    <p><?= htmlspecialchars($office['office_address_line1']) ?><br><?= htmlspecialchars($office['office_address_line2']) ?></p>
                </div>
            </div>
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#128222;</span>
                <div>
                    <h2>Call us</h2>
                    <p><a href="tel:<?= htmlspecialchars($officePhoneHref) ?>"><?= htmlspecialchars($office['office_phone']) ?></a></p>
                </div>
            </div>
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#9993;</span>
                <div>
                    <h2>Email us</h2>
                    <p><a href="mailto:<?= htmlspecialchars($office['office_email']) ?>"><?= htmlspecialchars($office['office_email']) ?></a></p>
                </div>
            </div>
            <div class="contact-info__item">
                <span class="contact-info__icon" aria-hidden="true">&#128337;</span>
                <div>
                    <h2>Office hours</h2>
                    <p><?= htmlspecialchars($office['office_hours']) ?></p>
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

            <?php if (!$contactPersons): ?>
                <p class="section-description">Contact details for our team will appear here shortly.</p>
            <?php else: ?>
                <div class="contact-people__grid">
                    <?php foreach ($contactPersons as $person): ?>
                        <article class="contact-card">
                            <?php if ($person['photo']): ?>
                                <img
                                    class="contact-card__photo"
                                    src="<?= htmlspecialchars($contactAssetBase) ?>/images/<?= htmlspecialchars(optimized_image_path('team/' . $person['photo'])) ?>"
                                    alt="Portrait of <?= htmlspecialchars($person['name']) ?>"
                                    loading="lazy"
                                    width="120"
                                    height="120"
                                >
                            <?php endif; ?>
                            <div class="contact-card__body">
                                <h3><?= htmlspecialchars($person['name']) ?></h3>
                                <p class="contact-card__role"><?= htmlspecialchars($person['role']) ?></p>
                                <?php if ($person['bio']): ?>
                                    <p class="contact-card__bio"><?= htmlspecialchars($person['bio']) ?></p>
                                <?php endif; ?>
                                <ul class="contact-card__meta">
                                    <li>
                                        <span aria-hidden="true">&#9993;</span>
                                        <a href="mailto:<?= htmlspecialchars($person['email']) ?>"><?= htmlspecialchars($person['email']) ?></a>
                                    </li>
                                    <?php if ($person['phone']): ?>
                                        <li>
                                            <span aria-hidden="true">&#128222;</span>
                                            <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $person['phone'])) ?>"><?= htmlspecialchars($person['phone']) ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
