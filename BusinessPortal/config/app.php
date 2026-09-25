<?php

/**
 * Application-wide path/URL helpers.
 * Works on both local XAMPP and live hosting by deriving the web base URL
 * from the real filesystem location of BusinessPortal vs DOCUMENT_ROOT.
 */

if (!function_exists('bp_root_path')) {
    /**
     * Absolute filesystem path of BusinessPortal/ (no trailing slash).
     */
    function bp_root_path(): string
    {
        return realpath(__DIR__ . '/..') ?: dirname(__DIR__);
    }
}

if (!function_exists('bp_web_base')) {
    /**
     * Web base path for BusinessPortal (starts with '/', no trailing slash).
     *   Local: "/texasspecializedquartz/BusinessPortal"
     *   Live:  "/BusinessPortal"  (or empty if BusinessPortal is docroot)
     */
    function bp_web_base(): string
    {
        $root = bp_root_path();
        $doc  = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
        $root = str_replace('\\', '/', $root);

        if ($doc && str_starts_with($root, $doc)) {
            return substr($root, strlen($doc)) ?: '';
        }
        // Fallback: infer from current script
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $pos    = strpos($script, '/BusinessPortal');
        return $pos !== false ? substr($script, 0, $pos + strlen('/BusinessPortal')) : '';
    }
}

if (!function_exists('bp_url')) {
    /**
     * Absolute URL under the BusinessPortal web root.
     *   bp_url('/auth/uploads/pricelist/foo.pdf')
     */
    function bp_url(string $path): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . $host . bp_web_base() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('site_web_base')) {
    /**
     * Web base for the SITE root (one level above BusinessPortal).
     *   Local: "/texasspecializedquartz"
     *   Live:  ""  (empty when site lives at domain root)
     */
    function site_web_base(): string
    {
        return preg_replace('~/BusinessPortal$~', '', bp_web_base());
    }
}

if (!function_exists('site_asset')) {
    /**
     * Root-relative URL for an asset under the site root (images/, css/, etc.).
     * Works in both local (subfolder) and production (domain root) layouts.
     *   site_asset('images/blog/foo.jpg')
     *     → /texasspecializedquartz/images/blog/foo.jpg  (local)
     *     → /images/blog/foo.jpg                          (prod)
     */
    function site_asset(string $path): string
    {
        return site_web_base() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('site_path')) {
    /**
     * Absolute filesystem path for an asset under the site root.
     *   site_path('images/blog/foo.jpg')
     *     → /Applications/.../texasspecializedquartz/images/blog/foo.jpg
     */
    function site_path(string $path): string
    {
        return realpath(__DIR__ . '/../..') . '/' . ltrim($path, '/');
    }
}
