<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/HeroSlide.php';

require_admin();

$projectBase = resolve_project_base('/admin/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (csrf_verify($_POST['csrf_token'] ?? null)) {
        $id = (int) ($_POST['id'] ?? 0);
        $slide = $id > 0 ? HeroSlide::find($id) : null;

        if ($slide) {
            if (!empty($slide['image'])) {
                $imagePath = __DIR__ . '/../../public/assets/images/hero/' . $slide['image'];
                if (is_file($imagePath)) {
                    @unlink($imagePath);
                }
            }
            HeroSlide::delete($id);
        }
    }

    header('Location: ' . $projectBase . '/admin/hero-slides/?deleted=1');
    exit;
}

$slides = HeroSlide::all();

$pageTitle = "Homepage Hero Slides - Busitema Admin";
$pageHeading = "Homepage Hero Slides";
$activeNav = '/hero-slides/';

include __DIR__ . '/../includes/header.php';
?>

<div class="admin-toolbar">
    <p>These appear as the rotating banner at the top of the homepage.</p>
    <a class="admin-btn" href="<?= htmlspecialchars($adminBase . '/hero-slides/form') ?>">+ Add new</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Slide added.</span></div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Slide updated.</span></div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="admin-alert admin-alert--success"><span aria-hidden="true">&#10003;</span><span>Slide removed.</span></div>
<?php elseif (isset($_GET['missing'])): ?>
    <div class="admin-alert admin-alert--error"><span aria-hidden="true">&#9888;</span><span>That slide could not be found.</span></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <?php if (!$slides): ?>
        <p class="admin-table__empty">No slides yet. Add the first one above.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Buttons</th>
                    <th scope="col">Order</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($slides as $slide): ?>
                    <tr>
                        <td>
                            <img
                                src="<?= htmlspecialchars($adminAssetBase . '/images/hero/' . $slide['image']) ?>"
                                alt=""
                                style="width:64px;height:40px;object-fit:cover;border-radius:4px;"
                            >
                        </td>
                        <td><?= htmlspecialchars($slide['title']) ?></td>
                        <td><?= htmlspecialchars($slide['primary_label']) ?><?= $slide['secondary_label'] ? ' / ' . htmlspecialchars($slide['secondary_label']) : '' ?></td>
                        <td><?= (int) $slide['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($adminBase . '/hero-slides/form?id=' . $slide['id']) ?>">Edit</a>
                            <form method="post" action="<?= htmlspecialchars($adminBase . '/hero-slides/') ?>" onsubmit="return confirm('Remove this slide?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $slide['id'] ?>">
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
