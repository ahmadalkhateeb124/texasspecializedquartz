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
