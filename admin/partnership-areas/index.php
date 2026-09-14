<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/PartnershipArea.php';

require_admin();

$projectBase = resolve_project_base('/admin/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && PartnershipArea::find($id)) {
            PartnershipArea::delete($id);
        }
    }

    header('Location: ' . $projectBase . '/admin/partnership-areas/?deleted=1');
    exit;
}

$areas = PartnershipArea::all();

$pageTitle = "Partnership Areas - Busitema Admin";
$pageHeading = "Partnership Areas";
$activeNav = '/partnership-areas/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These cards appear on the public Partnerships &gt; Partnership Areas page.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/partnership-areas/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Partnership area added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Partnership area updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Partnership area removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That partnership area could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$areas): ?>
        <p class="admin-table__empty">No partnership areas yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($areas as $area): ?>
                    <tr>
                        <td><?= htmlspecialchars($area['title']) ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth((string) $area['description'], 0, 80, '...')) ?></td>
                        <td><?= (int) $area['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/partnership-areas/form?id=' . $area['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/partnership-areas/') ?>" onsubmit="return confirm('Remove this partnership area?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $area['id'] ?>">
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
