<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/News.php';

require_admin();

$projectBase = resolve_project_base('/admin/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        $item = $id > 0 ? News::find($id) : null;

        if ($item) {
            if (!empty($item['image'])) {
                $imagePath = __DIR__ . '/../../public/assets/images/' . $item['image'];
                if (is_file($imagePath)) {
                    @unlink($imagePath);
                }
            }
            News::delete($id);
        }
    }

    header('Location: ' . $projectBase . '/admin/news/?deleted=1');
    exit;
}

$newsItems = News::all();

$pageTitle = "News & Events - Busitema Admin";
$pageHeading = "News & Events";
$activeNav = '/news/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These appear on the public News &amp; Events page, and the latest items also feature on the homepage.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/news/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Item added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Item updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Item removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That item could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$newsItems): ?>
        <p class="admin-table__empty">No news or events yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Type</th>
                    <th scope="col">Published</th>
                    <th scope="col">Featured</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= htmlspecialchars($item['type']) ?></td>
                        <td><?= htmlspecialchars($item['published_at']) ?></td>
                        <td><?= $item['is_featured'] ? '<span class="admin-card__badge">Featured</span>' : '&mdash;' ?></td>
                        <td><?= (int) $item['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/news/form?id=' . $item['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/news/') ?>" onsubmit="return confirm('Remove this item?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
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
