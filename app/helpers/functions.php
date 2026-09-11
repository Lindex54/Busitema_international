<?php
// Shared helper functions.

if (!function_exists('slugify')) {
    function slugify(string $value): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($value)), '-');
    }
}
