<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/ActivityLog.php';

require_admin();

$category = in_array($_GET['type'] ?? '', ['login', 'admins'], true) ? $_GET['type'] : null;
$requestedPage = max(1, (int) ($_GET['page'] ?? 1));
$pagination = ActivityLog::paginate($requestedPage, 25, $category);
$logs = $pagination['items'];
$pageTitle = 'Activity Logs - Busitema Admin';
$pageHeading = 'Activity Logs';
$activeNav = '/logs/';
$actionLabels = [
    'login_success' => 'Login successful',
    'login_failed' => 'Login failed',
    'logout' => 'Logged out',
    'admin_created' => 'Admin created',
    'admin_updated' => 'Admin updated',
    'admin_deleted' => 'Admin deleted',
    'admin_activated' => 'Admin activated',
    'admin_deactivated' => 'Admin deactivated',
];

include __DIR__ . '/../includes/header.php';

$pageUrl = static function (int $page) use ($adminBase, $category): string {
    $query = array_filter([
        'type' => $category,
        'page' => $page > 1 ? $page : null,
    ], static fn ($value) => $value !== null && $value !== '');

    return $adminBase . '/logs/' . ($query ? '?' . http_build_query($query) : '');
};

$firstItem = $pagination['total'] ? (($pagination['page'] - 1) * $pagination['per_page']) + 1 : 0;
$lastItem = min($pagination['page'] * $pagination['per_page'], $pagination['total']);
?>

<div class="admin-toolbar admin-toolbar--logs">
    <p>Login attempts and administrator-account changes, newest first.</p>
    <div class="admin-filter-links" aria-label="Filter activity logs">
        <a class="<?= $category === null ? 'is-active' : '' ?>" href="<?= htmlspecialchars($adminBase . '/logs/') ?>">All</a>
        <a class="<?= $category === 'login' ? 'is-active' : '' ?>" href="<?= htmlspecialchars($adminBase . '/logs/?type=login') ?>">Login attempts</a>
        <a class="<?= $category === 'admins' ? 'is-active' : '' ?>" href="<?= htmlspecialchars($adminBase . '/logs/?type=admins') ?>">Admin changes</a>
    </div>
</div>

<div class="admin-table-wrap">
    <?php if (!$logs): ?>
        <p class="admin-table__empty">No activity has been recorded yet.</p>
    <?php else: ?>
        <table class="admin-table admin-table--logs">
            <thead>
                <tr>
                    <th scope="col">Date &amp; time</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Status</th>
                    <th scope="col">Username</th>
                    <th scope="col">IP address</th>
                    <th scope="col">Browser / device</th>
                    <th scope="col">Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><time datetime="<?= htmlspecialchars($log['created_at']) ?>"><?= htmlspecialchars(date('j M Y, H:i:s', strtotime($log['created_at']))) ?></time></td>
                        <td><?= htmlspecialchars($actionLabels[$log['action']] ?? ucwords(str_replace('_', ' ', $log['action']))) ?></td>
                        <td><span class="admin-status admin-status--<?= htmlspecialchars($log['status']) ?>"><?= htmlspecialchars(ucfirst($log['status'])) ?></span></td>
                        <td><?= htmlspecialchars($log['actor_username'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($log['ip_address'] ?? 'Unknown') ?></td>
                        <td class="admin-log-agent" title="<?= htmlspecialchars($log['user_agent'] ?? '') ?>"><?= htmlspecialchars($log['user_agent'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($log['description'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php if ($pagination['total']): ?>
    <div class="admin-pagination" aria-label="Activity log pagination">
        <p>Showing <?= $firstItem ?>-<?= $lastItem ?> of <?= (int) $pagination['total'] ?> entries</p>
        <nav>
            <?php if ($pagination['page'] > 1): ?>
                <a href="<?= htmlspecialchars($pageUrl($pagination['page'] - 1)) ?>" rel="prev">&larr; Previous</a>
            <?php else: ?>
                <span class="is-disabled">&larr; Previous</span>
            <?php endif; ?>

            <?php
            $visiblePages = [1, $pagination['total_pages']];
            for ($page = max(1, $pagination['page'] - 2); $page <= min($pagination['total_pages'], $pagination['page'] + 2); $page++) {
                $visiblePages[] = $page;
            }
            $visiblePages = array_values(array_unique($visiblePages));
            sort($visiblePages);
            $previousVisiblePage = null;
            ?>
            <?php foreach ($visiblePages as $page): ?>
                <?php if ($previousVisiblePage !== null && $page > $previousVisiblePage + 1): ?>
                    <span class="admin-pagination__ellipsis" aria-hidden="true">&hellip;</span>
                <?php endif; ?>
                <a
                    class="<?= $page === $pagination['page'] ? 'is-current' : '' ?>"
                    href="<?= htmlspecialchars($pageUrl($page)) ?>"
                    <?= $page === $pagination['page'] ? 'aria-current="page"' : '' ?>
                ><?= $page ?></a>
                <?php $previousVisiblePage = $page; ?>
            <?php endforeach; ?>

            <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="<?= htmlspecialchars($pageUrl($pagination['page'] + 1)) ?>" rel="next">Next &rarr;</a>
            <?php else: ?>
                <span class="is-disabled">Next &rarr;</span>
            <?php endif; ?>
        </nav>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
