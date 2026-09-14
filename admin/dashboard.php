<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/security.php';
require_once __DIR__ . '/../app/middleware/auth.php';

require_admin();

$admin = current_admin();
$pageTitle = "Admin Dashboard - Busitema University";
$pageHeading = "Dashboard";
$activeNav = '/dashboard';

$contentModules = [
    ['title' => 'Campuses', 'description' => 'Manage campus cards on the About Busitema page.', 'status' => 'Live'],
    ['title' => 'Partners', 'description' => 'Manage partner institutions, regions and logos.', 'status' => 'Live'],
    ['title' => 'Partnership Areas', 'description' => 'Manage the ways Busitema collaborates with partners.', 'status' => 'Live'],
    ['title' => 'Contact People', 'description' => 'Update the staff shown on the Contact page.', 'status' => 'Live'],
    ['title' => 'News & Events', 'description' => 'Publish office news and events shown on the News page and homepage.', 'status' => 'Live'],
    ['title' => 'Scholarships', 'description' => 'Manage scholarships, exchange and mobility listings.', 'status' => 'Live'],
];

include __DIR__ . '/includes/header.php';
?>

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

<?php include __DIR__ . '/includes/footer.php'; ?>
