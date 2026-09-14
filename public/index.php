<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/seo.php';
require_once __DIR__ . '/../app/models/Setting.php';

$pageTitle = "Busitema University Internationalization & Partnerships";
$pageCanonical = seo_absolute_url('/');

try {
    $homeSettings = array_merge(seo_settings(), Setting::all());
} catch (Throwable $e) {
    $homeSettings = seo_settings();
}

$pageStructuredData = array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'CollegeOrUniversity',
    'name' => $homeSettings['seo_site_name'] ?? null,
    'url' => $pageCanonical,
    'description' => $homeSettings['seo_default_description'] ?? null,
    'address' => (!empty($homeSettings['office_address_line1']) || !empty($homeSettings['office_address_line2'])) ? array_filter([
        '@type' => 'PostalAddress',
        'streetAddress' => $homeSettings['office_address_line1'] ?? null,
        'addressLocality' => $homeSettings['office_address_line2'] ?? null,
        'addressCountry' => 'Uganda',
    ], static fn ($value) => $value !== null) : null,
    'telephone' => $homeSettings['office_phone'] ?? null,
    'email' => $homeSettings['office_email'] ?? null,
], static fn ($value) => $value !== null);

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<main>
    <?php include __DIR__ . '/../components/hero.php'; ?>
    <?php include __DIR__ . '/../components/welcome-message.php'; ?>
    <?php include __DIR__ . '/../components/about-busitema.php'; ?>
    <?php include __DIR__ . '/../components/uganda-showcase.php'; ?>
    <?php include __DIR__ . '/../components/office-news.php'; ?>
    <?php include __DIR__ . '/../components/latest-news.php'; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
