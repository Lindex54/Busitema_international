<?php
// Expects $pageTitle, $pageHeading and $activeNav to be set by the including page,
// and require_admin() to have already run.

require_once __DIR__ . '/../../app/helpers/url.php';

$adminProjectBase = resolve_project_base('/admin/');
$adminBase = $adminProjectBase . '/admin';
$adminAssetBase = $adminProjectBase . '/assets';
$adminAssetVersion = static function (string $relativePath): string {
    $assetPath = __DIR__ . '/../../public/assets/' . ltrim($relativePath, '/');

    return is_file($assetPath) ? '?v=' . filemtime($assetPath) : '';
};

$adminNav = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Hero Slides', 'href' => '/hero-slides/'],
    ['label' => 'Campuses', 'href' => '/campuses/'],
    ['label' => 'Partners', 'href' => '/partnerships/'],
    ['label' => 'Partnership Areas', 'href' => '/partnership-areas/'],
    ['label' => 'Contact People', 'href' => '/team/'],
    ['label' => 'News & Events', 'href' => '/news/'],
    ['label' => 'Scholarships', 'href' => '/scholarships/'],
    ['label' => 'Settings', 'href' => '/settings/'],
];

$admin = current_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin - Busitema University') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($adminAssetBase) ?>/css/index.css<?= $adminAssetVersion('css/index.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($adminAssetBase) ?>/css/admin.css<?= $adminAssetVersion('css/admin.css') ?>">
</head>
<body class="app">

<div class="admin-shell">
    <aside class="admin-shell__sidebar">
        <div class="admin-shell__brand">
            <a
                class="admin-logo-badge"
                href="<?= htmlspecialchars($adminProjectBase . '/') ?>"
                aria-label="Go to the Busitema International home page"
            >
                <img src="<?= htmlspecialchars($adminAssetBase) ?>/images/icons/Busitema_University_Logo.png" alt="Busitema University">
            </a>
            <span>Admin Panel</span>
        </div>

        <nav class="admin-shell__nav">
            <?php foreach ($adminNav as $navItem): ?>
                <a
                    class="<?= ($activeNav ?? '') === $navItem['href'] ? 'is-active' : '' ?>"
                    href="<?= $navItem['href'] === '#' ? '#' : htmlspecialchars($adminBase . $navItem['href']) ?>"
                ><?= htmlspecialchars($navItem['label']) ?></a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <div class="admin-shell__main">
        <header class="admin-shell__topbar">
            <h1><?= htmlspecialchars($pageHeading ?? 'Dashboard') ?></h1>
            <div class="admin-shell__user">
                <span><?= htmlspecialchars($admin['name'] ?? '') ?></span>
                <form method="post" action="<?= htmlspecialchars($adminBase . '/logout') ?>">
                    <?= csrf_field() ?>
                    <button class="admin-shell__logout" type="submit">Log out</button>
                </form>
            </div>
        </header>

        <main class="admin-shell__content">
