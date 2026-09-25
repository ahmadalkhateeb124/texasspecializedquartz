<?php

/**
 * PDF (Dompdf) configuration.
 * Dompdf + its dependencies were installed via Composer once, into
 * /Dompdf (sibling to BusinessPortal) — the live server never needs
 * Composer itself, just the resulting vendor/autoload.php.
 */

return [
    'dompdf_autoload' => __DIR__ . '/../../Dompdf/vendor/autoload.php',
    'storage_dir'      => __DIR__ . '/../assets/signoffs',
    // Dompdf's font subsetting writes temp files via tempnam() — sys_get_temp_dir()
    // is not reliably writable by the web server user, so use our own directory.
    'tmp_dir'          => __DIR__ . '/../assets/dompdf-tmp',
    // Custom embedded fonts (not bundled with Dompdf) and the writable
    // directory Dompdf copies/caches them into on first use.
    'fonts_dir'        => __DIR__ . '/../assets/fonts/lato',
    'font_cache_dir'   => __DIR__ . '/../assets/dompdf-font-cache',
];
