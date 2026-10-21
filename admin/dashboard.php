<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/security.php';
require_once __DIR__ . '/../app/middleware/auth.php';
require_once __DIR__ . '/../app/models/ActivityLog.php';
require_once __DIR__ . '/../app/models/User.php';

require_admin();

$admin = current_admin();
$pageTitle = "Admin Dashboard - Busitema University";
$pageHeading = "Dashboard";
$activeNav = '/dashboard';
$loginSummary = ActivityLog::dashboardSummary();
$recentAdmins = User::recentlyLoggedIn(6);
$adminCount = count(User::all());
$previousLoginAt = $admin['previous_login_at'] ?? null;

$contentModules = [
    ['title' => 'Hero Slides', 'description' => 'Manage the rotating banner at the top of the homepage.', 'status' => 'Live'],
    ['title' => 'Campuses', 'description' => 'Manage campus cards on the About Busitema page.', 'status' => 'Live'],
    ['title' => 'Partners', 'description' => 'Manage partner institutions, regions and logos.', 'status' => 'Live'],
    ['title' => 'Partnership Areas', 'description' => 'Manage the ways Busitema collaborates with partners.', 'status' => 'Live'],
    ['title' => 'Contact People', 'description' => 'Update the staff shown on the Contact page.', 'status' => 'Live'],
    ['title' => 'News & Events', 'description' => 'Publish office news and events shown on the News page and homepage.', 'status' => 'Live'],
    ['title' => 'Scholarships', 'description' => 'Manage scholarships, exchange and mobility listings.', 'status' => 'Live'],
    ['title' => 'Manage Admins', 'description' => 'Create and maintain administrator accounts and roles.', 'status' => 'Live'],
    ['title' => 'Activity Logs', 'description' => 'Review login attempts and administrator-account changes.', 'status' => 'Live'],
];

include __DIR__ . '/includes/header.php';
?>

<p class="admin-welcome">
    Welcome back, <?= htmlspecialchars($admin['name']) ?>. Use the modules below to manage website content and administration.
</p>

<section class="admin-overview" aria-labelledby="login-overview-title">
    <div class="admin-overview__heading">
        <div>
            <p class="admin-overview__eyebrow">Security overview</p>
            <h2 id="login-overview-title">Administrator login activity</h2>
        </div>
        <a href="<?= htmlspecialchars($adminBase . '/logs/?type=login') ?>">View all login attempts &rarr;</a>
    </div>

    <div class="admin-metrics">
        <article class="admin-metric">
            <span>Previous login</span>
            <strong><?= $previousLoginAt ? htmlspecialchars(date('j M Y, H:i', strtotime($previousLoginAt))) : 'First recorded login' ?></strong>
        </article>
        <article class="admin-metric">
            <span>Administrators</span>
            <strong><?= $adminCount ?></strong>
        </article>
        <article class="admin-metric">
            <span>Successful logins &middot; 24h</span>
            <strong><?= (int) $loginSummary['successful_logins'] ?></strong>
        </article>
        <article class="admin-metric admin-metric--warning">
            <span>Failed attempts &middot; 24h</span>
            <strong><?= (int) $loginSummary['failed_logins'] ?></strong>
        </article>
    </div>

    <div class="admin-recent-logins">
        <h3>When administrators last logged in</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Last login</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentAdmins as $recentAdmin): ?>
                        <tr>
                            <td><?= htmlspecialchars($recentAdmin['name']) ?></td>
                            <td><?= htmlspecialchars($recentAdmin['username']) ?></td>
                            <td><?= htmlspecialchars($recentAdmin['role'] ?: 'admin') ?></td>
                            <td>
                                <span class="admin-status admin-status--<?= (int) $recentAdmin['is_active'] === 1 ? 'success' : 'failure' ?>">
                                    <?= (int) $recentAdmin['is_active'] === 1 ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td><?= $recentAdmin['last_login_at'] ? htmlspecialchars(date('j M Y, H:i:s', strtotime($recentAdmin['last_login_at']))) : 'Never' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

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
