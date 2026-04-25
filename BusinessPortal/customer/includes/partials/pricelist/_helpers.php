<?php

/**
 * Returns icon + color scheme for a given file extension (pdf, xlsx, docx, etc).
 */
if (!function_exists('priceListFileTypeStyle')) {
    function priceListFileTypeStyle(string $type): array
    {
        return match (strtolower($type)) {
            'pdf'          => ['icon' => 'bx-file',        'color' => '#b91c1c', 'bg' => '#fee2e2'],
            'xlsx', 'xls'  => ['icon' => 'bx-spreadsheet', 'color' => '#3d6b4f', 'bg' => '#dff0e5'],
            'doc', 'docx'  => ['icon' => 'bx-file-doc',    'color' => '#2563eb', 'bg' => '#dbeafe'],
            default        => ['icon' => 'bx-image',       'color' => '#b45309', 'bg' => '#fef3c7'],
        };
    }
}
