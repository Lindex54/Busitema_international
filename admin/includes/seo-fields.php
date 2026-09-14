<?php
// Reusable "Search Engine Optimization" fieldset, included from a module's
// admin/<module>/form.php. The including page must set, before including
// this file:
//
//   $seoValues        array   current record's seo_title/seo_description/seo_image/is_indexed
//   $seoAutoTitle     string  the automatic SEO title (usually the content's own title)
//   $seoAutoDescription string the automatic SEO description source (summary/body excerpt)
//   $seoAutoImageUrl  string|null  absolute URL of the content's own image, or null
//   $seoPreviewUrl    string  the URL this content will be published at
//   $seoImageInputLabel string optional, defaults to "Social image override"
//   $seoImageHint     string optional extra hint line for the image field
?>
<div class="admin-seo-fieldset">
    <h2 class="admin-form-card__subheading">Search Engine Optimization (SEO)</h2>
    <p class="admin-field__hint">
        These fields are optional. By default this page's title, summary and image are used automatically -
        only fill these in if you want to show something different in search results or when shared on social media.
    </p>

    <div class="admin-seo-preview" data-seo-preview>
        <p class="admin-seo-preview__label">Search preview <span>(approximate - not exactly how Google will display it)</span></p>
        <div class="admin-seo-preview__url" data-seo-preview-url><?= htmlspecialchars($seoPreviewUrl ?? '') ?></div>
        <div class="admin-seo-preview__title" data-seo-preview-title><?= htmlspecialchars($seoValues['seo_title'] !== '' ? $seoValues['seo_title'] : ($seoAutoTitle ?? '')) ?></div>
        <div class="admin-seo-preview__description" data-seo-preview-description><?= htmlspecialchars($seoValues['seo_description'] !== '' ? $seoValues['seo_description'] : ($seoAutoDescription ?? '')) ?></div>
    </div>

    <div class="admin-field">
        <label for="seo_title">SEO title override</label>
        <input
            type="text"
            id="seo_title"
            name="seo_title"
            value="<?= htmlspecialchars($seoValues['seo_title'] ?? '') ?>"
            placeholder="Automatically using: <?= htmlspecialchars($seoAutoTitle ?? '') ?>"
            data-seo-input="title"
            maxlength="200"
        >
        <p class="admin-field__hint" data-seo-status="title">
            <?= ($seoValues['seo_title'] ?? '') !== '' ? 'CUSTOM OVERRIDE' : 'AUTO-GENERATED from the title above' ?>
        </p>
    </div>

    <div class="admin-field">
        <label for="seo_description">Meta description override</label>
        <textarea
            id="seo_description"
            name="seo_description"
            rows="2"
            maxlength="300"
            placeholder="Automatically using: <?= htmlspecialchars($seoAutoDescription ?? '') ?>"
            data-seo-input="description"
        ><?= htmlspecialchars($seoValues['seo_description'] ?? '') ?></textarea>
        <p class="admin-field__hint" data-seo-status="description">
            <?= ($seoValues['seo_description'] ?? '') !== '' ? 'CUSTOM OVERRIDE' : 'AUTO-GENERATED from the summary above' ?>
        </p>
    </div>

    <div class="admin-field">
        <label for="seo_image"><?= htmlspecialchars($seoImageInputLabel ?? 'Social image override') ?></label>
        <?php if (!empty($seoValues['seo_image'])): ?>
            <div class="admin-field__current-photo">
                <img src="<?= htmlspecialchars($seoValues['seo_image_preview_url'] ?? '') ?>" alt="Current SEO image override">
                <span>Current override &mdash; upload a new one to replace it</span>
            </div>
        <?php elseif (!empty($seoAutoImageUrl)): ?>
            <div class="admin-field__current-photo">
                <img src="<?= htmlspecialchars($seoAutoImageUrl) ?>" alt="Automatic social image">
                <span>AUTO-GENERATED &mdash; currently using this page's own image</span>
            </div>
        <?php endif; ?>
        <input type="file" id="seo_image" name="seo_image" accept="image/jpeg,image/png,image/webp">
        <p class="admin-field__hint">
            <?= htmlspecialchars($seoImageHint ?? 'Optional. Only needed if you want a different image shown when this page is shared on social media. JPG, PNG or WEBP, up to 2MB.') ?>
        </p>
    </div>

    <div class="admin-field">
        <label class="admin-inline-checkbox">
            <input type="checkbox" name="is_indexed" value="1" <?= ($seoValues['is_indexed'] ?? 1) ? 'checked' : '' ?>>
            Allow search engines to show this page (index it)
        </label>
        <p class="admin-field__hint">Untick this to keep this specific page out of Google and other search results.</p>
    </div>
</div>

<script>
(() => {
    const preview = document.querySelector('[data-seo-preview]');
    if (!preview) {
        return;
    }

    const titleInput = document.querySelector('[data-seo-input="title"]');
    const descInput = document.querySelector('[data-seo-input="description"]');
    const previewTitle = preview.querySelector('[data-seo-preview-title]');
    const previewDescription = preview.querySelector('[data-seo-preview-description]');
    const titleStatus = document.querySelector('[data-seo-status="title"]');
    const descStatus = document.querySelector('[data-seo-status="description"]');

    const autoTitle = <?= json_encode($seoAutoTitle ?? '') ?>;
    const autoDescription = <?= json_encode($seoAutoDescription ?? '') ?>;

    if (titleInput && previewTitle && titleStatus) {
        titleInput.addEventListener('input', () => {
            const value = titleInput.value.trim();
            previewTitle.textContent = value !== '' ? value : autoTitle;
            titleStatus.textContent = value !== '' ? 'CUSTOM OVERRIDE' : 'AUTO-GENERATED from the title above';
        });
    }

    if (descInput && previewDescription && descStatus) {
        descInput.addEventListener('input', () => {
            const value = descInput.value.trim();
            previewDescription.textContent = value !== '' ? value : autoDescription;
            descStatus.textContent = value !== '' ? 'CUSTOM OVERRIDE' : 'AUTO-GENERATED from the summary above';
        });
    }
})();
</script>
