<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/News.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$newsAdminBase = $projectBase . '/admin/news/';
$newsUploadDir = __DIR__ . '/../../public/assets/images/news';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$item = $id ? News::find($id) : null;

if ($id && !$item) {
    header('Location: ' . $newsAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $item;
$errors = [];
$typeOptions = ['News', 'Event'];
$values = $item ?: [
    'title' => '',
    'type' => 'News',
    'event_date' => '',
    'location' => '',
    'summary' => '',
    'body' => '',
    'image' => null,
    'published_at' => date('Y-m-d'),
    'is_featured' => 0,
    'sort_order' => News::nextSortOrder(),
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
    $values['type'] = in_array($_POST['type'] ?? '', $typeOptions, true) ? $_POST['type'] : 'News';
    $values['event_date'] = trim((string) ($_POST['event_date'] ?? ''));
    $values['location'] = trim((string) ($_POST['location'] ?? ''));
    $values['summary'] = trim((string) ($_POST['summary'] ?? ''));
    $values['body'] = trim((string) ($_POST['body'] ?? ''));
    $values['published_at'] = trim((string) ($_POST['published_at'] ?? ''));
    $values['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));

    if ($values['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if ($values['summary'] === '') {
        $errors[] = 'Summary is required.';
    }

    if ($values['body'] === '') {
        $errors[] = 'Body is required.';
    }

    if ($values['published_at'] === '') {
        $errors[] = 'Published date is required.';
    }

    if ($values['type'] === 'Event' && $values['event_date'] === '') {
        $errors[] = 'Event date is required for events.';
    }

    // Image is optional - only validated/uploaded if a file was actually chosen.
    $uploadResult = store_uploaded_image($_FILES['image'] ?? [], $newsUploadDir);

    if (!$uploadResult['ok']) {
        $errors[] = $uploadResult['error'];
    }

    if (!$errors) {
        $values['slug'] = News::uniqueSlug($values['title'], $isEdit ? $id : null);

        $newImage = $uploadResult['filename'] ?? null;

        if ($newImage) {
            $oldImage = $values['image'] ?? null;
            $values['image'] = 'news/' . $newImage;

            if ($isEdit && $oldImage && $oldImage !== $values['image']) {
                $oldPath = __DIR__ . '/../../public/assets/images/' . $oldImage;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        if ($isEdit) {
            News::update($id, $values);
            header('Location: ' . $newsAdminBase . '?updated=1');
        } else {
            News::create($values);
            header('Location: ' . $newsAdminBase . '?created=1');
        }
        exit;
    }
}

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' News / Event - Busitema Admin';
$pageHeading = $isEdit ? 'Edit News / Event' : 'Add News / Event';
$activeNav = '/news/';

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
            <label for="title">Title <span class="admin-field__required">*</span></label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($values['title']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="type">Type</label>
            <select id="type" name="type" data-news-type-select>
                <?php foreach ($typeOptions as $option): ?>
                    <option value="<?= htmlspecialchars($option) ?>" <?= $values['type'] === $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="admin-field">
            <label for="event_date" data-news-date-label>Event date</label>
            <input type="date" id="event_date" name="event_date" value="<?= htmlspecialchars($values['event_date'] ?? '') ?>">
            <p class="admin-field__hint" data-news-date-hint>Only needed for events. Leave blank for news items.</p>
        </div>

        <div class="admin-field">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($values['location'] ?? '') ?>" placeholder="e.g. Senate Conference Hall, Busitema Campus">
            <p class="admin-field__hint">Only needed for events. Leave blank for news items.</p>
        </div>

        <div class="admin-field">
            <label for="published_at">Published date</label>
            <input type="date" id="published_at" name="published_at" value="<?= htmlspecialchars($values['published_at']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="summary">Short summary</label>
            <textarea id="summary" name="summary" rows="2" required><?= htmlspecialchars($values['summary']) ?></textarea>
            <p class="admin-field__hint">Shown on the card in the listing and on the homepage.</p>
        </div>

        <div class="admin-field">
            <label for="body">Full story</label>
            <textarea id="body" name="body" rows="8" required><?= htmlspecialchars($values['body']) ?></textarea>
            <p class="admin-field__hint">Shown on the item's own page. Leave a blank line between paragraphs.</p>
        </div>

        <div class="admin-field">
            <label for="image">Image</label>
            <?php if (!empty($values['image'])): ?>
                <div class="admin-field__current-photo">
                    <img src="<?= htmlspecialchars($projectBase . '/public/assets/images/' . $values['image']) ?>" alt="Current image">
                    <span>Current image &mdash; upload a new one to replace it</span>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
            <p class="admin-field__hint">Optional. JPG, PNG or WEBP, up to 2MB.</p>
        </div>

        <div class="admin-field">
            <label class="admin-inline-checkbox">
                <input type="checkbox" name="is_featured" value="1" <?= $values['is_featured'] ? 'checked' : '' ?>>
                Featured (shown as the lead story)
            </label>
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add item' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($newsAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<script>
(() => {
    const typeSelect = document.querySelector('[data-news-type-select]');
    const dateLabel = document.querySelector('[data-news-date-label]');
    const dateHint = document.querySelector('[data-news-date-hint]');

    if (!typeSelect || !dateLabel || !dateHint) {
        return;
    }

    const updateDateLabel = () => {
        const isEvent = typeSelect.value === 'Event';
        dateLabel.textContent = isEvent ? 'Event date' : 'News date';
        dateHint.textContent = isEvent
            ? 'When this event takes place.'
            : 'Only needed for events. Leave blank for news items.';
    };

    typeSelect.addEventListener('change', updateDateLabel);
    updateDateLabel();
})();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
