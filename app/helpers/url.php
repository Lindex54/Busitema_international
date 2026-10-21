<?php
// URL helper functions.

if (!function_exists('site_base_path')) {
    // Returns the request path up to and including '/public', so absolute
    // URLs (canonical, sitemap, Open Graph, ...) work the same whether the
    // whole project sits under a subdirectory (local XAMPP) or the document
    // root points straight at /public (a typical production layout).
    function site_base_path(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $publicMarkerPosition = strpos($scriptName, '/public/');

        if ($publicMarkerPosition === false) {
            return '';
        }

        $publicBase = substr($scriptName, 0, $publicMarkerPosition + strlen('/public'));
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        // With the production root rewrite, SCRIPT_NAME points inside /public
        // while the visitor-facing URL does not. Preserve a project subfolder
        // (useful for XAMPP) but keep /public out of canonical production URLs.
        if (!str_starts_with($requestPath, $publicBase . '/') && $requestPath !== $publicBase) {
            return substr($scriptName, 0, $publicMarkerPosition);
        }

        return $publicBase;
    }
}

if (!function_exists('resolve_project_base')) {
    // Finds where a known marker segment (e.g. '/admin/') sits in the current
    // script's path and returns everything before it, so links and asset
    // paths work regardless of how deep the current page is nested or what
    // subdirectory the project is deployed under.
    function resolve_project_base(string $marker): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $markerPosition = strpos($scriptName, $marker);

        return $markerPosition === false ? '' : substr($scriptName, 0, $markerPosition);
    }
}

if (!function_exists('optimized_image_path')) {
    // Prefer an optimized JPEG generated alongside a photographic PNG. This
    // also upgrades records already stored in an older database without a
    // destructive data migration; transparent PNGs such as the logo remain.
    function optimized_image_path(?string $relativePath): string
    {
        $relativePath = ltrim(trim((string) $relativePath), '/');

        if ($relativePath === '' || preg_match('#^https?://#i', $relativePath)) {
            return $relativePath;
        }

        if (strtolower(pathinfo($relativePath, PATHINFO_EXTENSION)) === 'png') {
            $jpegPath = preg_replace('/\.png$/i', '.jpg', $relativePath);
            $jpegFile = __DIR__ . '/../../public/assets/images/' . $jpegPath;

            if (is_file($jpegFile)) {
                return $jpegPath;
            }
        }

        return $relativePath;
    }
}

if (!function_exists('is_safe_public_url')) {
    function is_safe_public_url(string $url): bool
    {
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https'], true) && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

if (!function_exists('public_url')) {
    function public_url(string $url, string $siteBase = ''): string
    {
        if (!is_safe_public_url($url)) {
            return '#';
        }

        return str_starts_with($url, '/') ? $siteBase . $url : $url;
    }
}
