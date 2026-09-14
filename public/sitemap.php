<?php
// Dynamic XML sitemap. Lists the pages that actually exist and are meant to
// be indexable - static pages that have real content (see the list below),
// plus every published record from the News, Partners and Scholarships
// modules that isn't marked "no index". Placeholder pages and admin/login
// routes are intentionally left out.

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/seo.php';
require_once __DIR__ . '/../app/models/News.php';
require_once __DIR__ . '/../app/models/Partner.php';
require_once __DIR__ . '/../app/models/Opportunity.php';

header('Content-Type: application/xml; charset=utf-8');

$settings = seo_settings();
$siteIndexable = ($settings['seo_site_indexable'] ?? '1') !== '0';

$urls = [];

if ($siteIndexable) {
    // Static pages with real, finished content.
    $staticPages = [
        '/',
        '/about/busitema',
        '/about/internationalization-office',
        '/partnerships/',
        '/partnerships/partners',
        '/partnerships/partnership-areas',
        '/partnerships/become-a-partner',
        '/study/scholarships',
        '/news/',
        '/contact/',
    ];

    foreach ($staticPages as $path) {
        $urls[] = ['loc' => seo_absolute_url($path), 'lastmod' => null];
    }

    try {
        foreach (News::all() as $item) {
            if (empty($item['is_indexed'])) {
                continue;
            }
            $urls[] = [
                'loc' => seo_absolute_url('/news/article?slug=' . $item['slug']),
                'lastmod' => $item['updated_at'] ?? null,
            ];
        }
    } catch (Throwable $e) {
        // Table may not exist yet on a fresh install - sitemap just omits it.
    }

    try {
        foreach (Partner::all() as $partner) {
            if (empty($partner['is_indexed'])) {
                continue;
            }
            $urls[] = [
                'loc' => seo_absolute_url('/partnerships/partner?slug=' . $partner['slug']),
                'lastmod' => $partner['updated_at'] ?? null,
            ];
        }
    } catch (Throwable $e) {
    }

    try {
        foreach (Opportunity::all() as $opportunity) {
            if (empty($opportunity['is_indexed'])) {
                continue;
            }
            $urls[] = [
                'loc' => seo_absolute_url('/study/scholarship?slug=' . $opportunity['slug']),
                'lastmod' => $opportunity['updated_at'] ?? null,
            ];
        }
    } catch (Throwable $e) {
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($urls as $url) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
    if (!empty($url['lastmod'])) {
        $timestamp = strtotime($url['lastmod']);
        if ($timestamp) {
            echo '    <lastmod>' . date('Y-m-d', $timestamp) . "</lastmod>\n";
        }
    }
    echo "  </url>\n";
}

echo '</urlset>';
