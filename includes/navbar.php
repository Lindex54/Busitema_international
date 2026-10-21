<?php
$siteBase = $siteBase ?? '';
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$currentSectionPath = $currentPath;

if ($siteBase !== '' && strpos($currentSectionPath, $siteBase) === 0) {
    $currentSectionPath = substr($currentSectionPath, strlen($siteBase)) ?: '/';
}

$currentSectionPath = '/' . ltrim($currentSectionPath, '/');
$currentSectionPath = preg_replace('#/index\.php$#', '/', $currentSectionPath);

$navItems = [
    ['label' => 'Home', 'href' => '/'],
    [
        'label' => 'About',
        'href' => '/about/',
        'children' => [
            ['label' => 'Internationalization Office', 'href' => '/about/internationalization-office'],
            ['label' => 'About Busitema', 'href' => '/about/busitema'],
            ['label' => 'Why Busitema', 'href' => '/about/why-busitema'],
            ['label' => 'Campuses', 'href' => '/about/campuses'],
        ],
    ],
    [
        'label' => 'Study',
        'href' => '/study/',
        'children' => [
            ['label' => 'Programmes', 'href' => '/study/programmes'],
            ['label' => 'Admissions', 'href' => '/study/admissions'],
            ['label' => 'How to Apply', 'href' => '/study/how-to-apply'],
            ['label' => 'Fees', 'href' => '/study/fees'],
            ['label' => 'Scholarships', 'href' => '/study/scholarships'],
        ],
    ],
    [
        'label' => 'International',
        'href' => '/international-students/',
        'children' => [
            ['label' => 'Before You Arrive', 'href' => '/international-students/before-you-arrive'],
            ['label' => 'Visa & Immigration', 'href' => '/international-students/visa-immigration'],
            ['label' => 'Student Support', 'href' => '/international-students/student-support'],
            ['label' => 'Mobility & Exchange', 'href' => '/mobility/'],
        ],
    ],
    ['label' => 'Life in Uganda', 'href' => '/life-in-uganda/'],
    [
        'label' => 'Partnerships',
        'href' => '/partnerships/',
        'children' => [
            ['label' => 'Partners', 'href' => '/partnerships/partners'],
            ['label' => 'Partnership Areas', 'href' => '/partnerships/partnership-areas'],
            ['label' => 'Become a Partner', 'href' => '/partnerships/become-a-partner'],
        ],
    ],
    ['label' => 'News & Events', 'href' => '/news/'],
    ['label' => 'Contact', 'href' => '/contact/'],
];
?>

<header class="site-header">
    <div class="site-header__inner">
        <a class="brand" href="<?= htmlspecialchars($siteBase . '/') ?>" aria-label="Busitema International home">
            <img
                class="brand__logo"
                src="<?= htmlspecialchars(($assetBase ?? '/assets') . '/images/icons/Busitema_University_Logo.png') ?>"
                alt="Busitema University"
                width="828"
                height="301"
            >
            <span class="brand__text">
                <span class="brand__name">Busitema</span>
                <span class="brand__sub">Internationalization</span>
            </span>
        </a>

        <button
            class="nav-toggle"
            type="button"
            aria-label="Open navigation menu"
            aria-controls="primary-navigation"
            aria-expanded="false"
        >
            <span class="nav-toggle__bar"></span>
            <span class="nav-toggle__bar"></span>
            <span class="nav-toggle__bar"></span>
        </button>

        <nav class="primary-nav" id="primary-navigation" aria-label="Primary navigation">
            <?php foreach ($navItems as $item): ?>
                <?php
                $hasChildren = !empty($item['children']);
                $isActive = $item['href'] === '/'
                    ? $currentSectionPath === '/'
                    : strpos($currentSectionPath, $item['href']) === 0;

                if ($hasChildren) {
                    foreach ($item['children'] as $child) {
                        if (strpos($currentSectionPath, $child['href']) === 0) {
                            $isActive = true;
                            break;
                        }
                    }
                }
                ?>
                <?php if ($hasChildren): ?>
                    <div class="primary-nav__item has-menu<?= $isActive ? ' is-active' : '' ?>">
                        <button
                            class="primary-nav__link primary-nav__trigger"
                            type="button"
                            aria-expanded="false"
                        >
                            <span><?= htmlspecialchars($item['label']) ?></span>
                            <span class="primary-nav__chevron" aria-hidden="true"></span>
                        </button>

                        <div class="primary-nav__menu">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php
                                $childIsActive = $child['href'] === '/'
                                    ? $currentSectionPath === '/'
                                    : strpos($currentSectionPath, $child['href']) === 0;
                                ?>
                                <a
                                    class="primary-nav__menu-link<?= $childIsActive ? ' is-active' : '' ?>"
                                    href="<?= htmlspecialchars($siteBase . $child['href']) ?>"
                                    <?= $childIsActive ? 'aria-current="page"' : '' ?>
                                >
                                    <?= htmlspecialchars($child['label']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a
                        class="primary-nav__link<?= $isActive ? ' is-active' : '' ?>"
                        href="<?= htmlspecialchars($siteBase . $item['href']) ?>"
                        <?= $isActive ? 'aria-current="page"' : '' ?>
                    >
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
