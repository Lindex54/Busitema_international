<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/security.php';
require_once __DIR__ . '/../app/middleware/auth.php';

require_admin();

$admin = current_admin();
$pageTitle = "Admin Dashboard - Busitema University";
$adminAssetBase = '../public/assets';

$contentModules = [
    ['title' => 'Partners', 'description' => 'Manage partner institutions, regions and agreements.', 'status' => 'Coming soon'],
    ['title' => 'Contact People', 'description' => 'Update the staff shown on the Contact page.', 'status' => 'Coming soon'],
    ['title' => 'News & Events', 'description' => 'Publish news articles and upcoming events.', 'status' => 'Coming soon'],
    ['title' => 'Opportunities', 'description' => 'Manage scholarships, exchange and mobility listings.', 'status' => 'Coming soon'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($adminAssetBase) ?>/css/index.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($adminAssetBase) ?>/css/admin.css">
</head>
<body class="app">

<div class="admin-shell">
    <aside class="admin-shell__sidebar">
        <div class="admin-shell__brand">
            <span class="admin-logo-badge">
                <img src="<?= htmlspecialchars($adminAssetBase) ?>/images/icons/Busitema_University_Logo.png" alt="Busitema University">
            </span>
            <span>Admin Panel</span>
        </div>

        <nav class="admin-shell__nav">
            <a class="is-active" href="dashboard">Dashboard</a>
            <a href="#">Partners</a>
            <a href="#">Contact People</a>
            <a href="#">News &amp; Events</a>
            <a href="#">Opportunities</a>
        </nav>
    </aside>

    <div class="admin-shell__main">
        <header class="admin-shell__topbar">
            <h1>Dashboard</h1>
            <div class="admin-shell__user">
                <span><?= htmlspecialchars($admin['name']) ?></span>
                <form method="post" action="logout">
                    <button class="admin-shell__logout" type="submit">Log out</button>
                </form>
            </div>
        </header>

        <main class="admin-shell__content">
            <p class="admin-welcome">
                Welcome back, <?= htmlspecialchars($admin['name']) ?>. Content management tools will appear here as they're built.
            </p>

            <div class="admin-cards">
                <?php foreach ($contentModules as $module): ?>
                    <article class="admin-card">
                        <span class="admin-card__badge"><?= htmlspecialchars($module['status']) ?></span>
                        <h3><?= htmlspecialchars($module['title']) ?></h3>
                        <p><?= htmlspecialchars($module['description']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

</body>
</html>
