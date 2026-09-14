<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Partner.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$partnersAssetBase = $projectBase . '/public/assets';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        $partner = $id > 0 ? Partner::find($id) : null;

        if ($partner) {
            Partner::delete($id);

            if (!empty($partner['logo'])) {
                $logoPath = __DIR__ . '/../../public/assets/images/partners/' . $partner['logo'];
                if (is_file($logoPath)) {
                    @unlink($logoPath);
                }
            }
        }
    }

    header('Location: ' . $projectBase . '/admin/partnerships/?deleted=1');
    exit;
}

$partners = Partner::all();

$pageTitle = "Partners - Busitema Admin";
$pageHeading = "Partners";
$activeNav = '/partnerships/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These institutions appear on the public Partnerships &gt; Our Partners page.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/partnerships/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Partner added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Partner updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Partner removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That partner could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$partners): ?>
        <p class="admin-table__empty">No partners yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Name</th>
                    <th scope="col">Region</th>
                    <th scope="col">Country</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partners as $partner): ?>
                    <tr>
                        <td>
                            <?php if ($partner['logo']): ?>
                                <img
                                    class="admin-table__photo"
                                    src="<?= htmlspecialchars($partnersAssetBase . '/images/partners/' . $partner['logo']) ?>"
                                    alt="Logo of <?= htmlspecialchars($partner['name']) ?>"
                                >
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($partner['name']) ?></td>
                        <td><?= htmlspecialchars($partner['region']) ?></td>
                        <td><?= htmlspecialchars($partner['country']) ?></td>
                        <td><?= (int) $partner['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/partnerships/form?id=' . $partner['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/partnerships/') ?>" onsubmit="return confirm('Remove this partner?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $partner['id'] ?>">
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
