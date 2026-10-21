<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/HeroSlide.php';

$heroAssetBase = $assetBase ?? '/assets';
$heroSiteBase = $siteBase ?? '';

try {
    $heroSlides = HeroSlide::all();
} catch (Throwable $e) {
    $heroSlides = [];
}
?>

<?php if ($heroSlides): ?>
<section class="hero-flow" aria-label="Busitema international highlights">
    <div class="hero-flow__viewport" data-hero-flow>
        <?php foreach ($heroSlides as $index => $slide): ?>
            <?php
            $imageUrl = $heroAssetBase . '/images/' . optimized_image_path('hero/' . $slide['image']);
            $isActive = $index === 0;
            ?>
            <article
                class="hero-flow__slide<?= $isActive ? ' is-active' : '' ?>"
                data-image="<?= htmlspecialchars($imageUrl) ?>"
                aria-hidden="<?= $isActive ? 'false' : 'true' ?>"
            >
                <div class="hero-flow__image" style="background-image: url('<?= htmlspecialchars($imageUrl) ?>');"></div>

                <div class="hero-flow__shade" aria-hidden="true"></div>

                <div class="hero-flow__content">
                    <h1><?= htmlspecialchars($slide['title']) ?></h1>
                    <div class="hero-flow__actions">
                        <a class="hero-flow__button hero-flow__button--primary" href="<?= htmlspecialchars(public_url($slide['primary_url'], $heroSiteBase)) ?>">
                            <?= htmlspecialchars($slide['primary_label']) ?>
                        </a>
                        <?php if (!empty($slide['secondary_label']) && !empty($slide['secondary_url'])): ?>
                            <a class="hero-flow__button hero-flow__button--secondary" href="<?= htmlspecialchars(public_url($slide['secondary_url'], $heroSiteBase)) ?>">
                                <?= htmlspecialchars($slide['secondary_label']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="hero-flow__pagination" aria-label="Hero slides">
            <?php foreach ($heroSlides as $index => $slide): ?>
                <button
                    class="hero-flow__dot<?= $index === 0 ? ' is-active' : '' ?>"
                    type="button"
                    data-hero-slide="<?= $index ?>"
                    aria-label="Show <?= htmlspecialchars($slide['eyebrow'] ?: $slide['title']) ?>"
                    aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                ></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
