<?php
$footerBase = $siteBase ?? '';

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Setting.php';

$footerOfficeDefaults = [
    'office_address_line1' => 'Jinja - Malaba Road',
    'office_address_line2' => 'P.O. Box 236, Tororo, Uganda',
    'office_phone' => '+256 454 448 808',
    'office_email' => 'vc.adm@busitema.ac.ug',
];

try {
    $footerOffice = array_merge($footerOfficeDefaults, array_filter(Setting::all()));
} catch (Throwable $e) {
    $footerOffice = $footerOfficeDefaults;
}

$footerPhoneHref = preg_replace('/[^0-9+]/', '', $footerOffice['office_phone']);
?>
<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__identity">
            <a class="site-footer__brand" href="<?= htmlspecialchars($footerBase) ?>/" aria-label="Busitema International home">
                <img
                    src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/images/icons/Busitema_University_Logo.png"
                    alt="Busitema University"
                >
            </a>
            <p>Connecting Busitema University with students, scholars and institutions around the world.</p>
        </div>

        <div class="site-footer__columns">
            <section class="site-footer__column" aria-labelledby="footer-study-title">
                <h2 id="footer-study-title">Study at Busitema</h2>
                <ul>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/study/programmes">Programmes</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/study/admissions">Admissions</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/study/how-to-apply">How to apply</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/study/scholarships">Scholarships</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/about/campuses">Our campuses</a></li>
                </ul>
            </section>

            <section class="site-footer__column" aria-labelledby="footer-international-title">
                <h2 id="footer-international-title">International</h2>
                <ul>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/international-students/before-you-arrive">Before you arrive</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/international-students/visa-immigration">Visa and immigration</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/international-students/student-support">Student support</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/mobility/">Mobility and exchange</a></li>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/partnerships/become-a-partner">Become a partner</a></li>
                </ul>
            </section>

            <section class="site-footer__column site-footer__contact" aria-labelledby="footer-contact-title">
                <h2 id="footer-contact-title">Contact us</h2>
                <address>
                    <span><?= htmlspecialchars($footerOffice['office_address_line1']) ?></span>
                    <span><?= htmlspecialchars($footerOffice['office_address_line2']) ?></span>
                    <a href="tel:<?= htmlspecialchars($footerPhoneHref) ?>"><?= htmlspecialchars($footerOffice['office_phone']) ?></a>
                    <a href="mailto:<?= htmlspecialchars($footerOffice['office_email']) ?>"><?= htmlspecialchars($footerOffice['office_email']) ?></a>
                </address>
                <a class="site-footer__contact-link" href="<?= htmlspecialchars($footerBase) ?>/contact/">Contact details <span aria-hidden="true">&rarr;</span></a>
            </section>

            <section class="site-footer__column" aria-labelledby="footer-connect-title">
                <h2 id="footer-connect-title">Connect</h2>
                <ul>
                    <li><a href="<?= htmlspecialchars($footerBase) ?>/news/">News and events</a></li>
                    <li><a href="https://busitema.ac.ug/" target="_blank" rel="noopener noreferrer">University website</a></li>
                    <li><a href="https://x.com/BusitemaUni" target="_blank" rel="noopener noreferrer">X / Twitter</a></li>
                </ul>
            </section>
        </div>

        <div class="site-footer__bottom">
            <p>&copy; <?= date('Y') ?> Busitema University. All rights reserved.</p>
            <div class="site-footer__legal">
                <a href="<?= htmlspecialchars($footerBase) ?>/resources/">Resources</a>
                <a href="<?= htmlspecialchars($footerBase) ?>/contact/">Accessibility</a>
            </div>
        </div>
    </div>
</footer>
<script src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/js/main.js<?= isset($assetVersion) ? $assetVersion('js/main.js') : '' ?>"></script>
<script src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/js/navigation.js<?= isset($assetVersion) ? $assetVersion('js/navigation.js') : '' ?>"></script>
<script src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/js/sliders.js<?= isset($assetVersion) ? $assetVersion('js/sliders.js') : '' ?>"></script>
</body>
</html>
