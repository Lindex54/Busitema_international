<?php
// Reusable SEO helpers shared by every public page and admin content form.
//
// Two halves:
//   - seo_meta()   resolves a content record's title/description/image/robots,
//                   applying admin overrides over the record's own fields,
//                   with global site defaults as the final fallback. Modules
//                   call this before including includes/header.php.
//   - includes/header.php then renders whatever ends up in $pageTitle,
//     $pageDescription, $pageImage, $pageCanonical, $pageNoindex, $pageType
//     and $pageStructuredData - pages that never call seo_meta() still work,
//     they just fall back to the site-wide defaults below.

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/url.php';

if (!function_exists('seo_site_origin')) {
    // The scheme+host part of the site's public URL. Reads APP_URL from
    // .env when set (this is the ONE place to change once a production
    // domain exists); otherwise auto-detects the current request so local
    // development just works.
    function seo_site_origin(): string
    {
        $configured = env('APP_URL');

        if ($configured) {
            return rtrim($configured, '/');
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host;
    }
}

if (!function_exists('seo_base_url')) {
    // Origin + the current deployment's /public base path, so links work
    // whether the project root or /public itself is the document root.
    function seo_base_url(): string
    {
        return seo_site_origin() . site_base_path();
    }
}

if (!function_exists('seo_absolute_url')) {
    function seo_absolute_url(string $path): string
    {
        return seo_base_url() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('seo_image_url')) {
    // $relativePath is relative to /assets/images/, e.g. "partners/logo.png"
    // or (news items already store it this way) "news/story.jpg". Returns
    // null when there is nothing to show, rather than a fake placeholder.
    function seo_image_url(?string $relativePath): ?string
    {
        $relativePath = trim((string) $relativePath);

        if ($relativePath === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $relativePath)) {
            return $relativePath;
        }

        return seo_base_url() . '/assets/images/' . optimized_image_path($relativePath);
    }
}

if (!function_exists('seo_current_url')) {
    // Unlike seo_absolute_url(), REQUEST_URI already includes the site's
    // base path (e.g. /busitema-international/public/news/), so only the
    // origin needs to be prefixed here.
    function seo_current_url(): string
    {
        $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

        return rtrim(seo_site_origin(), '/') . $path;
    }
}

if (!function_exists('seo_excerpt')) {
    // Builds a safe meta-description length excerpt from existing content -
    // never invents text, only trims what is already there at a word
    // boundary and adds an ellipsis if it had to cut mid-sentence.
    function seo_excerpt(string $text, int $maxLength = 160): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');

        if ($text === '' || mb_strlen($text) <= $maxLength) {
            return $text;
        }

        $truncated = mb_substr($text, 0, $maxLength);
        $lastSpace = mb_strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return rtrim($truncated, " .,;:-") . '...';
    }
}

if (!function_exists('seo_settings')) {
    // Site-wide SEO defaults, stored in the existing `settings` key/value
    // table (see admin/settings/) alongside office contact details -
    // reuses that module instead of introducing a second settings system.
    function seo_settings(): array
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        $defaults = [
            'seo_site_name' => 'Busitema University Internationalization & Partnerships',
            'seo_default_description' => 'Busitema University\'s Internationalization & Partnerships Office - international partnerships, scholarships, exchange opportunities and support for international students.',
            'seo_default_image' => '',
            'seo_site_indexable' => '1',
        ];

        try {
            $stored = array_filter(Setting::all(), static fn ($value) => $value !== null && $value !== '');
        } catch (Throwable $e) {
            $stored = [];
        }

        $cached = array_merge($defaults, $stored);

        return $cached;
    }
}

if (!function_exists('seo_meta')) {
    // Resolves the final SEO fields for one page/content record.
    //
    // $options:
    //   title            (string)      the content's own title - the automatic SEO title
    //   description      (string|null) the content's own summary/body - excerpted automatically
    //   image            (string|null) path relative to /assets/images/, the content's own image
    //   canonical_path   (string|null) e.g. '/news/article?slug=foo' - defaults to the current URL
    //   type             (string)      Open Graph type, defaults to 'website'
    //   overrides        (array|null)  the DB record, read for seo_title/seo_description/seo_image/is_indexed
    //     when those columns exist on it - only used when actually present and non-empty.
    function seo_meta(array $options): array
    {
        $overrides = $options['overrides'] ?? [];

        $overrideTitle = trim((string) ($overrides['seo_title'] ?? ''));
        $title = $overrideTitle !== '' ? $overrideTitle : (string) ($options['title'] ?? '');

        $overrideDescription = trim((string) ($overrides['seo_description'] ?? ''));
        $rawDescription = $overrideDescription !== '' ? $overrideDescription : (string) ($options['description'] ?? '');
        $description = $rawDescription !== '' ? seo_excerpt($rawDescription) : null;

        $overrideImage = trim((string) ($overrides['seo_image'] ?? ''));
        $imagePath = $overrideImage !== '' ? $overrideImage : ($options['image'] ?? null);

        $noindex = array_key_exists('is_indexed', $overrides) && $overrides['is_indexed'] !== null
            ? !((bool) $overrides['is_indexed'])
            : false;

        return [
            'title' => $title,
            'description' => $description,
            'image' => seo_image_url($imagePath),
            'canonical' => isset($options['canonical_path'])
                ? seo_absolute_url($options['canonical_path'])
                : null,
            'noindex' => $noindex,
            'type' => $options['type'] ?? 'website',
            'title_is_override' => $overrideTitle !== '',
            'description_is_override' => $overrideDescription !== '',
            'image_is_override' => $overrideImage !== '',
        ];
    }
}

if (!function_exists('seo_json_ld')) {
    // Safely serialises one or more JSON-LD structured data blocks.
    // Accepts a single associative array or a list of them.
    function seo_json_ld(array $data): string
    {
        if (array_is_list($data)) {
            $blocks = $data;
        } else {
            $blocks = [$data];
        }

        $blocks = array_values(array_filter($blocks));

        if (!$blocks) {
            return '';
        }

        $html = '';
        foreach ($blocks as $block) {
            $json = json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($json === false) {
                continue;
            }
            // Defend against premature </script> break-out in stored content.
            $json = str_replace('</', '<\\/', $json);
            $html .= '<script type="application/ld+json">' . $json . "</script>\n";
        }

        return $html;
    }
}
