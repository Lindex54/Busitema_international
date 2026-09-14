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

        return $publicMarkerPosition === false
            ? ''
            : substr($scriptName, 0, $publicMarkerPosition + strlen('/public'));
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
