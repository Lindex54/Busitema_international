<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Opportunity.php';

require_admin();

$projectBase = resolve_project_base('/admin/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && Opportunity::find($id)) {
            Opportunity::delete($id);
        }
    }

    header('Location: ' . $projectBase . '/admin/scholarships/?deleted=1');
    exit;
}

$opportunities = Opportunity::all();

$pageTitle = "Scholarships - Busitema Admin";
$pageHeading = "Scholarships";
$activeNav = '/scholarships/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These appear on the public Study &gt; Scholarships page.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/scholarships/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Opportunity added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Opportunity updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Opportunity removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That opportunity could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$opportunities): ?>
        <p class="admin-table__empty">No opportunities yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Status</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($opportunities as $opportunity): ?>
                    <tr>
                        <td><?= htmlspecialchars($opportunity['title']) ?></td>
                        <td><?= htmlspecialchars($opportunity['category']) ?></td>
                        <td><?= htmlspecialchars($opportunity['status']) ?></td>
                        <td><?= htmlspecialchars($opportunity['deadline']) ?></td>
                        <td><?= (int) $opportunity['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/scholarships/form?id=' . $opportunity['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/scholarships/') ?>" onsubmit="return confirm('Remove this opportunity?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $opportunity['id'] ?>">
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
