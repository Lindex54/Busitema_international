<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/models/ActivityLog.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$adminsBase = $projectBase . '/admin/admins/';
$currentAdmin = current_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'set_status') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        header('Location: ' . $adminsBase . '?invalid_token=1');
        exit;
    }

    $id = (int) ($_POST['id'] ?? 0);
    $requestedStatus = (string) ($_POST['is_active'] ?? '');

    if (!in_array($requestedStatus, ['0', '1'], true)) {
        header('Location: ' . $adminsBase . '?status_error=1');
        exit;
    }

    $isActive = $requestedStatus === '1';

    if ($id === (int) $currentAdmin['id'] && !$isActive) {
        header('Location: ' . $adminsBase . '?self_status=1');
        exit;
    }

    $user = $id > 0 ? User::find($id) : null;

    if (!$user) {
        header('Location: ' . $adminsBase . '?missing=1');
        exit;
    }

    try {
        User::setActive($id, $isActive);
        ActivityLog::record(
            $isActive ? 'admin_activated' : 'admin_deactivated',
            'success',
            (int) $currentAdmin['id'],
            $currentAdmin['username'],
            ($isActive ? 'Activated' : 'Deactivated') . ' administrator: ' . $user['username']
        );
        header('Location: ' . $adminsBase . ($isActive ? '?activated=1' : '?deactivated=1'));
    } catch (Throwable $exception) {
        error_log('Admin status update failed: ' . $exception->getMessage());
        header('Location: ' . $adminsBase . '?status_error=1');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        header('Location: ' . $adminsBase . '?invalid_token=1');
        exit;
    }

    $id = (int) ($_POST['id'] ?? 0);

    if ($id === (int) $currentAdmin['id']) {
        header('Location: ' . $adminsBase . '?self_delete=1');
        exit;
    }

    $user = $id > 0 ? User::find($id) : null;

    if (!$user) {
        header('Location: ' . $adminsBase . '?missing=1');
        exit;
    }

    try {
        User::delete($id);
        ActivityLog::record(
            'admin_deleted',
            'success',
            (int) $currentAdmin['id'],
            $currentAdmin['username'],
            'Deleted administrator: ' . $user['username']
        );
        header('Location: ' . $adminsBase . '?deleted=1');
    } catch (Throwable $exception) {
        error_log('Admin deletion failed: ' . $exception->getMessage());
        header('Location: ' . $adminsBase . '?delete_error=1');
    }
    exit;
}

$admins = User::all();
$pageTitle = 'Manage Administrators - Busitema Admin';
$pageHeading = 'Manage Admins';
$activeNav = '/admins/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>Create and maintain accounts that can access this administration panel.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/admins/form') ?>">+ Add administrator</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Administrator added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Administrator updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Administrator removed.</span></div>
<?php elseif (isset($_GET['activated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Administrator activated.</span></div>
<?php elseif (isset($_GET['deactivated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Administrator deactivated.</span></div>
<?php elseif (isset($_GET['self_delete'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>You cannot delete the account you are currently using.</span></div>
<?php elseif (isset($_GET['self_status'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>You cannot deactivate the account you are currently using.</span></div>
<?php elseif (isset($_GET['delete_error'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>The administrator could not be removed. Please try again.</span></div>
<?php elseif (isset($_GET['status_error'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>The administrator status could not be changed. Please try again.</span></div>
<?php elseif (isset($_GET['invalid_token'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>Your session expired. Please try again.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That administrator could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Status</th>
                <th scope="col">Last login</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $user): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($user['name']) ?>
                        <?php if ((int) $user['id'] === (int) $currentAdmin['id']): ?>
                            <span class="admin-table__tag">You</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($user['role'] ?: 'admin') ?></td>
                    <td>
                        <?php if ((int) $user['is_active'] === 1): ?>
                            <span class="admin-status admin-status--success">Active</span>
                        <?php else: ?>
                            <span class="admin-status admin-status--failure">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $user['last_login_at'] ? htmlspecialchars(date('j M Y, H:i', strtotime($user['last_login_at']))) : 'Never' ?></td>
                    <td class="admin-table__actions">
                        <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/admins/form?id=' . $user['id']) ?>">Edit</a>
                        <?php if ((int) $user['id'] !== (int) $currentAdmin['id']): ?>
                            <form method="post" action="<?= htmlspecialchars($adminsBase) ?>" <?= (int) $user['is_active'] === 1 ? 'onsubmit="return confirm(\'Deactivate this administrator?\');"' : '' ?>>
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="set_status">
                                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                                <input type="hidden" name="is_active" value="<?= (int) $user['is_active'] === 1 ? '0' : '1' ?>">
                                <button class="admin-btn <?= (int) $user['is_active'] === 1 ? 'admin-btn--danger' : 'admin-btn--ghost' ?>" type="submit">
                                    <?= (int) $user['is_active'] === 1 ? 'Deactivate' : 'Activate' ?>
                                </button>
                            </form>
                            <form method="post" action="<?= htmlspecialchars($adminsBase) ?>" onsubmit="return confirm('Remove this administrator?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                                <button class="admin-btn admin-btn--danger" type="submit">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
