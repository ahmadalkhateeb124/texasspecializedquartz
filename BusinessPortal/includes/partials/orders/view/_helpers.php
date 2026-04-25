<?php

/**
 * Small view helpers used by order detail partials.
 */

if (!function_exists('orderFieldOrNA')) {
    function orderFieldOrNA(?string $v): string
    {
        return $v ? htmlspecialchars($v) : '<span style="color:var(--color-text-sub)">—</span>';
    }
}

if (!function_exists('orderThicknessLabel')) {
    function orderThicknessLabel(string $val, ?string $custom): string
    {
        if ($val === 'custom' && $custom) return htmlspecialchars($custom) . ' cm (custom)';
        if ($val === '2cm' || $val === '20') return '2 cm';
        if ($val === '3cm' || $val === '30') return '3 cm';
        return htmlspecialchars($val ?: 'N/A');
    }
}
