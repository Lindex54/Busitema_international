<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/PartnershipArea.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$areasAdminBase = $projectBase . '/admin/partnership-areas/';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$area = $id ? PartnershipArea::find($id) : null;

if ($id && !$area) {
    header('Location: ' . $areasAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $area;
$errors = [];
$values = $area ?: [
    'title' => '',
    'description' => '',
    'sort_order' => PartnershipArea::nextSortOrder(),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['title'] = trim((string) ($_POST['title'] ?? ''));
    $values['description'] = trim((string) ($_POST['description'] ?? ''));
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));

    if ($values['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if ($values['description'] === '') {
        $errors[] = 'Description is required.';
    }

    if (!$errors) {
        try {
            if ($isEdit) {
                PartnershipArea::update($id, $values);
            } else {
                PartnershipArea::create($values);
            }
        } catch (Throwable $exception) {
            $errors[] = database_write_error($exception);
        }

        if (!$errors) {
            header('Location: ' . $areasAdminBase . ($isEdit ? '?updated=1' : '?created=1'));
            exit;
        }
    }
}

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Partnership Area - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Partnership Area' : 'Add Partnership Area';
$activeNav = '/partnership-areas/';

include __DIR__ . '/../includes/header.php';
?>

<?php if ($errors): ?>
    <div class="admin-alert admin-alert--error" role="alert">
        <span aria-hidden="true">&#9888;</span>
        <span><?= htmlspecialchars(implode(' ', $errors)) ?></span>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <form method="post" action="" novalidate>
        <?= csrf_field() ?>

        <div class="admin-field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($values['title']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($values['description'] ?? '') ?></textarea>
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add partnership area' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($areasAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
