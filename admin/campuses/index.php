<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Campus.php';

require_admin();

$projectBase = resolve_project_base('/admin/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && Campus::find($id)) {
            Campus::delete($id);
        }
    }

    header('Location: ' . $projectBase . '/admin/campuses/?deleted=1');
    exit;
}

$campuses = Campus::all();

$pageTitle = "Campuses - Busitema Admin";
$pageHeading = "Campuses";
$activeNav = '/campuses/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These cards appear on the public About &gt; About Busitema page.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/campuses/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Campus added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Campus updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Campus removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That campus could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$campuses): ?>
        <p class="admin-table__empty">No campuses yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">District / City</th>
                    <th scope="col">Focus</th>
                    <th scope="col">Flags</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campuses as $campus): ?>
                    <tr>
                        <td><?= htmlspecialchars($campus['name']) ?></td>
                        <td><?= htmlspecialchars($campus['district']) ?></td>
                        <td><?= htmlspecialchars($campus['focus']) ?></td>
                        <td>
                            <?php if ($campus['is_featured']): ?><span class="admin-card__badge">Featured</span><?php endif; ?>
                            <?php if ($campus['is_new']): ?><span class="admin-card__badge">New</span><?php endif; ?>
                        </td>
                        <td><?= (int) $campus['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/campuses/form?id=' . $campus['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/campuses/') ?>" onsubmit="return confirm('Remove this campus?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $campus['id'] ?>">
                                <button class="admin-btn admin-btn--danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
