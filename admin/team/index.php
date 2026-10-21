<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/ContactPerson.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$teamAssetBase = $projectBase . '/assets';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        $person = $id > 0 ? ContactPerson::find($id) : null;

        if ($person) {
            ContactPerson::delete($id);

            if (!empty($person['photo'])) {
                $photoPath = __DIR__ . '/../../public/assets/images/team/' . $person['photo'];
                if (is_file($photoPath)) {
                    @unlink($photoPath);
                }
            }
        }
    }

    header('Location: ' . $projectBase . '/admin/team/?deleted=1');
    exit;
}

$people = ContactPerson::all();

$pageTitle = "Contact People - Busitema Admin";
$pageHeading = "Contact People";
$activeNav = '/team/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These are the staff cards shown on the public Contact page.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/team/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Contact person added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Contact person updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Contact person removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That contact person could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$people): ?>
        <p class="admin-table__empty">No contact people yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Email</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($people as $person): ?>
                    <tr>
                        <td>
                            <?php if ($person['photo']): ?>
                                <img
                                    class="admin-table__photo"
                                    src="<?= htmlspecialchars($teamAssetBase . '/images/team/' . $person['photo']) ?>"
                                    alt="Photo of <?= htmlspecialchars($person['name']) ?>"
                                >
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($person['name']) ?></td>
                        <td><?= htmlspecialchars($person['role']) ?></td>
                        <td><?= htmlspecialchars($person['email']) ?></td>
                        <td><?= (int) $person['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/team/form?id=' . $person['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/team/') ?>" onsubmit="return confirm('Remove this contact person?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $person['id'] ?>">
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
