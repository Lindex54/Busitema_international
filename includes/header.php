<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/helpers/url.php';
require_once __DIR__ . '/../app/helpers/seo.php';

if (!isset($pageTitle)) {
    $pageTitle = "Busitema Internationalization";
}

$siteBase = site_base_path();
$assetBase = $siteBase . '/assets';
$assetVersion = static function (string $relativePath): string {
    $assetPath = __DIR__ . '/../public/assets/' . ltrim($relativePath, '/');

    return is_file($assetPath) ? '?v=' . filemtime($assetPath) : '';
};

// SEO: pages may set $pageDescription, $pageImage, $pageCanonical, $pageType,
// $pageNoindex and $pageStructuredData before including this file (see
// app/helpers/seo.php - seo_meta()). Anything they don't set falls back to
// the site-wide defaults configured in Settings.
$seoSettings = seo_settings();
$seoSiteWideIndexable = ($seoSettings['seo_site_indexable'] ?? '1') !== '0';
$seoTitle = $pageTitle;
$seoDescription = $pageDescription ?? $seoSettings['seo_default_description'];
$seoImage = $pageImage ?? seo_image_url($seoSettings['seo_default_image'] ?: null);
$seoCanonical = $pageCanonical ?? seo_current_url();
$seoType = $pageType ?? 'website';
$seoNoindex = !$seoSiteWideIndexable || !empty($pageNoindex);
$seoRobots = $seoNoindex ? 'noindex, follow' : 'index, follow';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($seoTitle) ?></title>
    <meta name="robots" content="<?= htmlspecialchars($seoRobots) ?>">
    <?php if ($seoDescription): ?>
        <meta name="description" content="<?= htmlspecialchars($seoDescription) ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= htmlspecialchars($seoCanonical) ?>">

    <meta property="og:site_name" content="<?= htmlspecialchars($seoSettings['seo_site_name']) ?>">
    <meta property="og:type" content="<?= htmlspecialchars($seoType) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <?php if ($seoDescription): ?>
        <meta property="og:description" content="<?= htmlspecialchars($seoDescription) ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?= htmlspecialchars($seoCanonical) ?>">
    <?php if ($seoImage): ?>
        <meta property="og:image" content="<?= htmlspecialchars($seoImage) ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="<?= $seoImage ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <?php if ($seoDescription): ?>
        <meta name="twitter:description" content="<?= htmlspecialchars($seoDescription) ?>">
    <?php endif; ?>
    <?php if ($seoImage): ?>
        <meta name="twitter:image" content="<?= htmlspecialchars($seoImage) ?>">
    <?php endif; ?>

    <?php if (!empty($pageStructuredData)): ?>
        <?= seo_json_ld($pageStructuredData) ?>
    <?php endif; ?>

    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/tailwind.output.css<?= $assetVersion('css/tailwind.output.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/index.css<?= $assetVersion('css/index.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/main.css<?= $assetVersion('css/main.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/components.css<?= $assetVersion('css/components.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/responsive.css<?= $assetVersion('css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/app.css<?= $assetVersion('css/app.css') ?>">
</head>
<body class="app">
