<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/helpers/functions.php';
require_once __DIR__ . '/../../app/helpers/seo.php';
require_once __DIR__ . '/../../app/models/Partner.php';

$pageTitle = "Our Partners - Busitema University";
$pageDescription = "Universities and institutions Busitema University partners with for student and staff exchange, joint research and collaboration.";
$pageCanonical = seo_absolute_url('/partnerships/partners');
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navbar.php';

$partnersSiteBase = $siteBase ?? '';
$partnersAssetBase = $assetBase ?? '/assets';

try {
    $partnerRegions = Partner::allGroupedByRegion();
} catch (Throwable $e) {
    $partnerRegions = [];
}
?>

<main>
    <section class="page-hero" aria-labelledby="partners-hero-title">
        <div class="page-hero__inner">
            <p class="page-hero__eyebrow">Our Network</p>
            <h1 id="partners-hero-title">Partner institutions around the world</h1>
            <p class="page-hero__lead">
                From Eastern Africa to Europe, Asia and North America, our partner network gives Busitema students and
                staff genuine international opportunities.
            </p>
        </div>
    </section>

    <section class="section" aria-label="Partner institutions by region">
        <div class="container">
            <p class="section-description partner-intro">
                The directory below is a sample of the institutions we collaborate with. It is updated regularly as new
                agreements are confirmed &mdash; if you don't see a region you're interested in, please
                <a href="<?= htmlspecialchars($partnersSiteBase . '/contact/') ?>">get in touch</a>.
            </p>

            <?php if (!$partnerRegions): ?>
                <p class="section-description">Partner institutions will appear here shortly.</p>
            <?php else: ?>
                <div class="partner-tabs" data-partner-tabs>
                    <div class="partner-tabs__nav" role="tablist" aria-label="Partner regions">
                        <?php foreach (array_keys($partnerRegions) as $index => $region): ?>
                            <button
                                class="partner-tabs__btn<?= $index === 0 ? ' is-active' : '' ?>"
                                type="button"
                                role="tab"
                                id="partner-tab-<?= $index ?>"
                                aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                                aria-controls="partner-panel-<?= $index ?>"
                                data-partner-tab-target="<?= $index ?>"
                            ><?= htmlspecialchars($region) ?></button>
                        <?php endforeach; ?>
                    </div>

                    <?php $regionIndex = 0; ?>
                    <?php foreach ($partnerRegions as $region => $institutions): ?>
                        <div
                            class="partner-tabs__panel<?= $regionIndex === 0 ? ' is-active' : '' ?>"
                            id="partner-panel-<?= $regionIndex ?>"
                            role="tabpanel"
                            aria-labelledby="partner-tab-<?= $regionIndex ?>"
                            data-partner-tab-panel="<?= $regionIndex ?>"
                            <?= $regionIndex === 0 ? '' : 'hidden' ?>
                        >
                            <div class="partner-table-wrap">
                                <table class="partner-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Partner Institution</th>
                                            <th scope="col">Country</th>
                                            <th scope="col"><span class="u-visually-hidden">Details</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($institutions as $institution): ?>
                                            <?php $slug = slugify($institution['name']); ?>
                                            <tr>
                                                <td><?= htmlspecialchars($institution['name']) ?></td>
                                                <td><?= htmlspecialchars($institution['country']) ?></td>
                                                <td class="partner-table__action">
                                                    <a
                                                        class="partner-table__link"
                                                        href="<?= htmlspecialchars($partnersSiteBase . '/partnerships/partner?slug=' . urlencode($slug)) ?>"
                                                    >View more <span aria-hidden="true">&rarr;</span></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php $regionIndex++; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
