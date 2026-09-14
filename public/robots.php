<?php
// Dynamic robots.txt. Blocks the admin panel and login always; blocks the
// whole site while seo_site_indexable is off in Settings (useful before the
// site has a real domain / is ready to be found publicly). References the
// sitemap only when a real base URL is actually resolvable.

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/seo.php';

header('Content-Type: text/plain; charset=utf-8');

$settings = seo_settings();
$siteIndexable = ($settings['seo_site_indexable'] ?? '1') !== '0';

if (!$siteIndexable) {
    echo "User-agent: *\n";
    echo "Disallow: /\n";
    exit;
}

echo "User-agent: *\n";
echo "Disallow: " . site_base_path() . "/admin/\n";
echo "Allow: " . site_base_path() . "/\n";
echo "\n";
echo "Sitemap: " . seo_absolute_url('/sitemap.xml') . "\n";
