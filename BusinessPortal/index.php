<?php
/**
 * index.php — Smart entry point
 * Redirects to the correct dashboard based on session role.
 */
require_once __DIR__ . '/includes/auth.php';

if (isAdmin()) {
    header('Location: admin/index.php');
    exit;
}

if (isCustomer()) {
    header('Location: customer/index.php');
    exit;
}

/* Not logged in */
header('Location: auth-login-minimal.php');
exit;
