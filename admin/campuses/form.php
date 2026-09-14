<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Campus.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$campusesAdminBase = $projectBase . '/admin/campuses/';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$campus = $id ? Campus::find($id) : null;

if ($id && !$campus) {
    header('Location: ' . $campusesAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $campus;
$errors = [];
$values = $campus ?: [
    'name' => '',
    'district' => '',
    'focus' => '',
    'description' => '',
    'link' => '',
    'is_featured' => 0,
    'is_new' => 0,
    'sort_order' => Campus::nextSortOrder(),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['name'] = trim((string) ($_POST['name'] ?? ''));
    $values['district'] = trim((string) ($_POST['district'] ?? ''));
    $values['focus'] = trim((string) ($_POST['focus'] ?? ''));
    $values['description'] = trim((string) ($_POST['description'] ?? ''));
    $values['link'] = trim((string) ($_POST['link'] ?? ''));
    $values['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
    $values['is_new'] = isset($_POST['is_new']) ? 1 : 0;
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));

    if ($values['name'] === '') {
        $errors[] = 'Name is required.';
    }

    if ($values['district'] === '') {
        $errors[] = 'District / city is required.';
    }

    if ($values['focus'] === '') {
        $errors[] = 'Focus is required.';
    }

    if ($values['description'] === '') {
        $errors[] = 'Description is required.';
    }

    if ($values['link'] !== '' && !filter_var($values['link'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Link must be a valid URL (e.g. https://example.com).';
    }

    if (!$errors) {
        if ($isEdit) {
            Campus::update($id, $values);
            header('Location: ' . $campusesAdminBase . '?updated=1');
        } else {
            Campus::create($values);
            header('Location: ' . $campusesAdminBase . '?created=1');
        }
        exit;
    }
}

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Campus - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Campus' : 'Add Campus';
$activeNav = '/campuses/';

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
            <label for="name">Campus name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($values['name']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="district">District / City</label>
            <input type="text" id="district" name="district" value="<?= htmlspecialchars($values['district']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="focus">Academic focus</label>
            <input type="text" id="focus" name="focus" value="<?= htmlspecialchars($values['focus']) ?>" placeholder="e.g. Engineering & Technology" required>
        </div>

        <div class="admin-field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($values['description']) ?></textarea>
        </div>

        <div class="admin-field">
            <label for="link">Campus link (optional)</label>
            <input type="url" id="link" name="link" value="<?= htmlspecialchars($values['link'] ?? '') ?>" placeholder="https://campus.busitema.ac.ug">
        </div>

        <div class="admin-field">
            <label class="admin-inline-checkbox">
                <input type="checkbox" name="is_featured" value="1" <?= $values['is_featured'] ? 'checked' : '' ?>>
                Featured (highlighted with a gold border)
            </label>
        </div>

        <div class="admin-field">
            <label class="admin-inline-checkbox">
                <input type="checkbox" name="is_new" value="1" <?= $values['is_new'] ? 'checked' : '' ?>>
                Show a "New" badge
            </label>
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add campus' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($campusesAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
