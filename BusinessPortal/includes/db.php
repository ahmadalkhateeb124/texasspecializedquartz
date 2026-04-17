<?php
/**
 * includes/db.php
 * ─────────────────────────────────────────────────────────────────────────────
 * Database connection wrapper.
 * Bootstraps $pdo once and re-uses the existing partials/conn.php config.
 *
 * Usage:
 *   require_once __DIR__ . '/../includes/db.php';
 *   // $pdo is now available
 */

if (!isset($pdo)) {
    require_once __DIR__ . '/../partials/conn.php';
}
