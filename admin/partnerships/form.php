<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/security.php';
require_once __DIR__ . '/../../app/helpers/url.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/middleware/auth.php';
require_once __DIR__ . '/../../app/models/Partner.php';

require_admin();

$projectBase = resolve_project_base('/admin/');
$partnersAdminBase = $projectBase . '/admin/partnerships/';
$partnerUploadDir = __DIR__ . '/../../public/assets/images/partners';
$partnerSeoUploadDir = __DIR__ . '/../../public/assets/images/partners';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$partner = $id ? Partner::find($id) : null;

if ($id && !$partner) {
    header('Location: ' . $partnersAdminBase . '?missing=1');
    exit;
}

$isEdit = (bool) $partner;
$errors = [];
$values = $partner ?: [
    'name' => '',
    'region' => '',
    'country' => '',
    'about' => '',
    'logo' => null,
    'sort_order' => Partner::nextSortOrder(),
    'seo_title' => '',
    'seo_description' => '',
    'seo_image' => null,
    'is_indexed' => 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    }

    $values['name'] = trim((string) ($_POST['name'] ?? ''));
    $values['region'] = trim((string) ($_POST['region'] ?? ''));
    $values['country'] = trim((string) ($_POST['country'] ?? ''));
    $values['about'] = trim((string) ($_POST['about'] ?? ''));
    $values['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));
    $values['seo_title'] = trim((string) ($_POST['seo_title'] ?? ''));
    $values['seo_description'] = trim((string) ($_POST['seo_description'] ?? ''));
    $values['is_indexed'] = isset($_POST['is_indexed']) ? 1 : 0;

    if ($values['name'] === '') {
        $errors[] = 'Name is required.';
    }

    if ($values['region'] === '') {
        $errors[] = 'Region is required.';
    }

    if ($values['country'] === '') {
        $errors[] = 'Country is required.';
    }

    if ($values['about'] === '') {
        $errors[] = 'About text is required.';
    }

    // Logo is optional - only validated/uploaded if a file was actually chosen.
    $uploadResult = store_uploaded_image($_FILES['logo'] ?? [], $partnerUploadDir);

    if (!$uploadResult['ok']) {
        $errors[] = $uploadResult['error'];
    }

    // SEO image override is also optional.
    $seoUploadResult = store_uploaded_image($_FILES['seo_image'] ?? [], $partnerSeoUploadDir);

    if (!$seoUploadResult['ok']) {
        $errors[] = $seoUploadResult['error'];
    }

    if (!$errors) {
        $values['slug'] = Partner::uniqueSlug($values['name'], $isEdit ? $id : null);

        $newLogo = $uploadResult['filename'] ?? null;

        if ($newLogo) {
            $oldLogo = $values['logo'] ?? null;
            $values['logo'] = $newLogo;

            if ($isEdit && $oldLogo && $oldLogo !== $newLogo) {
                $oldPath = $partnerUploadDir . '/' . $oldLogo;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $newSeoImage = $seoUploadResult['filename'] ?? null;

        if ($newSeoImage) {
            $oldSeoImage = $values['seo_image'] ?? null;
            $values['seo_image'] = 'partners/' . $newSeoImage;

            if ($isEdit && $oldSeoImage && $oldSeoImage !== $values['seo_image']) {
                $oldPath = __DIR__ . '/../../public/assets/images/' . $oldSeoImage;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        if ($isEdit) {
            Partner::update($id, $values);
            header('Location: ' . $partnersAdminBase . '?updated=1');
        } else {
            Partner::create($values);
            header('Location: ' . $partnersAdminBase . '?created=1');
        }
        exit;
    }
}

$existingRegions = Partner::distinctRegions();

$pageTitle = ($isEdit ? 'Edit' : 'Add') . ' Partner - Busitema Admin';
$pageHeading = $isEdit ? 'Edit Partner' : 'Add Partner';
$activeNav = '/partnerships/';

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
            <label for="name">Institution name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($values['name']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="region">Region</label>
            <input type="text" id="region" name="region" list="region-options" value="<?= htmlspecialchars($values['region']) ?>" required>
            <datalist id="region-options">
                <?php foreach ($existingRegions as $region): ?>
                    <option value="<?= htmlspecialchars($region) ?>">
                <?php endforeach; ?>
            </datalist>
            <p class="admin-field__hint">Pick an existing region or type a new one (e.g. Middle East).</p>
        </div>

        <div class="admin-field">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" value="<?= htmlspecialchars($values['country']) ?>" required>
        </div>

        <div class="admin-field">
            <label for="about">About</label>
            <textarea id="about" name="about" rows="5" required><?= htmlspecialchars($values['about'] ?? '') ?></textarea>
        </div>

        <div class="admin-field">
            <label for="sort_order">Display order</label>
            <input type="number" id="sort_order" name="sort_order" min="0" value="<?= (int) $values['sort_order'] ?>">
        </div>

        <div class="admin-field">
            <label for="logo">Logo</label>
            <?php if (!empty($values['logo'])): ?>
                <div class="admin-field__current-photo">
                    <img src="<?= htmlspecialchars($projectBase . '/public/assets/images/partners/' . $values['logo']) ?>" alt="Current logo">
                    <span>Current logo &mdash; upload a new one to replace it</span>
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
            <p class="admin-field__hint">Optional. JPG, PNG or WEBP, up to 2MB. Leave blank if you don't have one yet &mdash; no placeholder image will be shown.</p>
        </div>

        <?php
        $seoValues = $values;
        $seoValues['seo_image_preview_url'] = !empty($values['seo_image'])
            ? $projectBase . '/public/assets/images/' . $values['seo_image']
            : null;
        $seoAutoTitle = $values['name'] !== '' ? $values['name'] . ' - Busitema University Partners' : '';
        $seoAutoDescription = $values['about'] ?? '';
        $seoAutoImageUrl = !empty($values['logo']) ? seo_image_url('partners/' . $values['logo']) : null;
        $seoPreviewUrl = seo_absolute_url('/partnerships/partner?slug=' . ($values['slug'] ?? Partner::uniqueSlug($values['name'] ?: 'new-partner', $isEdit ? $id : null)));
        $seoImageInputLabel = 'Social image override';
        $seoImageHint = 'Optional. Only needed if you want a different image than the logo above when this partner is shared on social media.';
        include __DIR__ . '/../includes/seo-fields.php';
        ?>

        <div class="admin-form-actions">
            <button class="admin-btn" type="submit"><?= $isEdit ? 'Save changes' : 'Add partner' ?></button>
            <a class="admin-btn admin-btn--ghost" href="<?= htmlspecialchars($partnersAdminBase) ?>">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
