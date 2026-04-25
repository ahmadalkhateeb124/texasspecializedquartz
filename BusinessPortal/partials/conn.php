<?php

/**
 * Legacy connection bootstrapper.
 * Delegates to config/database.php so credentials live in one place.
 *
 * Keep this file for backward compatibility — existing code still does
 *   require_once __DIR__ . '/../partials/conn.php';
 */

$dbConfig = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['options']
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
