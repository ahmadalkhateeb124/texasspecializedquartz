<?php

/**
 * customer/order-view.php — Customer: View a single order.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../includes/partials/orders/view/_helpers.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$id          = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: orders');
    exit;
}

try {
    $order = (new OrderRepository($pdo))->findWithJobs($id);
    if (!$order || (int)$order['account_id'] !== (int)$accountId) {
        header('Location: orders');
        exit;
    }
    $job_sections = $order['jobs'];

    $assignedEmployee = (new EmployeeRepository($pdo))->employeeForOrder($id);
    $signoff          = (new SignoffRepository($pdo))->findByOrder($id);

    $scheduleRepo = new JobScheduleRepository($pdo);
    foreach ($job_sections as &$job) {
        $job['schedule_events'] = $scheduleRepo->forSection((int)$job['id']);
    }
    unset($job);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

$pageTitle  = 'Order #' . $order['id'];
$breadcrumb = [
    ['label' => 'My Orders', 'url' => 'orders.php'],
    ['label' => 'Order #' . $order['id']],
];
$extraCss   = '<link rel="stylesheet" href="../assets/css/shared/order-view.css">';

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Order #<?= $order['id'] ?></h1>
                    <p class="page-subtitle">
                        Created <?= date('M j, Y', strtotime($order['created_at'])) ?>
                        &middot; Last updated <?= date('M j, Y g:i A', strtotime($order['updated_at'])) ?>
                    </p>
                </div>
                <div class="page-actions">
                    <?php if ($signoff): ?>
                        <a href="../signoff-download.php?order_id=<?= $id ?>" target="_blank" class="btn btn-outline">
                            <i class='bx bx-file-blank'></i> View Sign-Off PDF
                        </a>
                    <?php endif; ?>
                    <a href="orders" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Orders
                    </a>
                </div>
            </div>

            <?php if (($order['admin_status'] ?? '') === 'completed'): ?>
                <div class="alert alert-secondary d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-lock-alt'></i> This order is complete and closed for further changes.
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-id-card text-primary'></i> Responsible Employee</h6>
                        </div>
                        <div class="card-section">
                            <?php if ($assignedEmployee): ?>
                                <div class="d-flex align-items-center gap-2" style="padding:6px 10px;background:var(--bg, #f7f4ef);border-radius:8px;">
                                    <div style="width:28px;height:28px;border-radius:50%;background:#000000;color:#fff;
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:11px;font-weight:700;flex-shrink:0;">
                                        <?= htmlspecialchars(strtoupper(substr($assignedEmployee['fullname'], 0, 2))) ?>
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-weight:600;font-size:13px;">
                                            <?= htmlspecialchars($assignedEmployee['fullname']) ?>
                                            <?php if (!empty($assignedEmployee['designation'])): ?>
                                                <span style="font-weight:400;color:var(--text-sub);">— <?= htmlspecialchars($assignedEmployee['designation']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="font-size:11.5px;color:var(--text-sub);display:flex;gap:12px;flex-wrap:wrap;">
                                            <?php if (!empty($assignedEmployee['email'])): ?>
                                                <span><i class='bx bx-envelope'></i> <?= htmlspecialchars($assignedEmployee['email']) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($assignedEmployee['phone'])): ?>
                                                <span><i class='bx bx-phone'></i> <?= htmlspecialchars($assignedEmployee['phone']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span style="color:var(--text-sub);font-size:13px;">Not yet assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php include __DIR__ . '/../includes/partials/orders/view/customer-info.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/sales-rep.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/notes.php'; ?>
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/../includes/partials/orders/view/job-sections.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/attachment.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>
