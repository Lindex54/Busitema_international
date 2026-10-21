<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Opportunity.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$opportunitiesAdminBase = $projectBase . '/admin/scholarships/';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$opportunity = $id ? Opportunity::find($id) : null;

if ($id && !$opportunity) {
    header('Location: ' . $opportunitiesAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $opportunity;
$errors = [];
$statusOptions = ['Open', 'Upcoming', 'Closed'];
$values = $opportunity ?: [
    'title' => '',
    'category' => '',
    'status' => 'Open',
    'deadline' => '',
    'summary' => '',
    'overview' => '',
    'eligibility' => '',
    'how_to_apply' => '',
    'sort_order' => Opportunity::nextSortOrder(),
    'seo_title' => '',
    'seo_description' => '',
    'seo_image' => null,
    'is_indexed' => 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['title'] = trim((string) ($_POST['title'] ?? ''));
    $values['category'] = trim((string) ($_POST['category'] ?? ''));
    $values['status'] = in_array($_POST['status'] ?? '', $statusOptions, true) ? $_POST['status'] : 'Open';
    $values['deadline'] = trim((string) ($_POST['deadline'] ?? ''));
    $values['summary'] = trim((string) ($_POST['summary'] ?? ''));
    $values['overview'] = trim((string) ($_POST['overview'] ?? ''));
    $values['eligibility'] = trim((string) ($_POST['eligibility'] ?? ''));
    $values['how_to_apply'] = trim((string) ($_POST['how_to_apply'] ?? ''));
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));

    if ($values['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if ($values['category'] === '') {
        $errors[] = 'Category is required.';
    }

    if ($values['deadline'] === '') {
        $errors[] = 'Deadline is required.';
    }

    if ($values['summary'] === '') {
        $errors[] = 'Summary is required.';
    }

    if ($values['overview'] === '') {
        $errors[] = 'Overview is required.';
    }

    if (!$errors) {
        $values['slug'] = Opportunity::uniqueSlug($values['title'], $isEdit ? $id : null);

        if ($isEdit) {
            Opportunity::update($id, $values);
            header('Location: ' . $opportunitiesAdminBase . '?updated=1');
        } else {
            Opportunity::create($values);
            header('Location: ' . $opportunitiesAdminBase . '?created=1');
        }
        exit;
    }
}

$existingCategories = Opportunity::distinctCategories();

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Opportunity - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Opportunity' : 'Add Opportunity';
$activeNav = '/scholarships/';

include __DIR__ . '/../includes/header.php';
?>

<?php if ($errors): ?>
    <div class="admin-alert admin-alert--error" role="alert">
        <span aria-hidden="true">&#9888;</span>
        <span><?= htmlspecialchars(implode(' ', $errors)) ?></span>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <form method="post" action="" enctype="multipart/form-data" novalidate>
        <?= csrf_field() ?>

        <div class="admin-field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($values['title']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" list="category-options" value="<?= htmlspecialchars($values['category']) ?>" required>
            <datalist id="category-options">
                <?php foreach ($existingCategories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>">
                <?php endforeach; ?>
            </datalist>
            <p class="admin-field__hint">Pick an existing category or type a new one (e.g. Fellowship).</p>
        </div>

        <div class="admin-field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach ($statusOptions as $option): ?>
                    <option value="<?= htmlspecialchars($option) ?>" <?= $values['status'] === $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="admin-field">
            <label for="deadline">Deadline</label>
            <input type="text" id="deadline" name="deadline" value="<?= htmlspecialchars($values['deadline']) ?>" placeholder="e.g. 31 October 2026, or Rolling admissions" required>
        </div>

        <div class="admin-field">
            <label for="summary">Short summary</label>
            <textarea id="summary" name="summary" rows="2" required><?= htmlspecialchars($values['summary']) ?></textarea>
            <p class="admin-field__hint">Shown on the opportunity card in the listing.</p>
        </div>

        <div class="admin-field">
            <label for="overview">Overview</label>
            <textarea id="overview" name="overview" rows="4" required><?= htmlspecialchars($values['overview']) ?></textarea>
            <p class="admin-field__hint">Shown at the top of the opportunity's own page.</p>
        </div>

        <div class="admin-field">
            <label for="eligibility">Eligibility</label>
            <textarea id="eligibility" name="eligibility" rows="4"><?= htmlspecialchars($values['eligibility']) ?></textarea>
            <p class="admin-field__hint">One point per line. Leave blank to hide this section.</p>
        </div>

        <div class="admin-field">
            <label for="how_to_apply">How to apply</label>
            <textarea id="how_to_apply" name="how_to_apply" rows="4"><?= htmlspecialchars($values['how_to_apply']) ?></textarea>
            <p class="admin-field__hint">One step per line. Leave blank to hide this section.</p>
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add opportunity' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($opportunitiesAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
