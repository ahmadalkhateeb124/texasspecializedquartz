<?php

/**
 * admin/employees.php — Employees Index
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Employees';
$breadcrumb  = [['label' => 'Employees']];

$dbError = null;
try {
    $repo      = new EmployeeRepository($pdo);
    $employees = $repo->allWithAssignmentCounts();
    $counts    = ['active' => 0, 'inactive' => 0];
    foreach ($employees as $e) {
        $counts[($e['status'] ?? 'Active') === 'Active' ? 'active' : 'inactive']++;
    }
} catch (PDOException $e) {
    $employees = [];
    $counts    = ['active' => 0, 'inactive' => 0];
    $dbError   = htmlspecialchars($e->getMessage());
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Employees</h1>
                    <p class="page-subtitle">
                        <?= count($employees) ?> total employee<?= count($employees) !== 1 ? 's' : '' ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="employees-new" class="btn btn-primary">
                        <i class='bx bx-user-plus'></i> Add Employee
                    </a>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/employees/stat-bar.php'; ?>
            <?php include __DIR__ . '/includes/partials/employees/table.php'; ?>

        </main>

        <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <script src="../assets/js/admin/employees.js"></script>

        <?php include __DIR__ . '/includes/footer.php'; ?>
