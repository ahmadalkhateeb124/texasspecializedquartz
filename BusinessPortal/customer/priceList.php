<?php

/**
 * customer/priceList.php — Customer Price Lists View
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/pricelist-db.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'Price Lists';
$breadcrumb  = [['label' => 'Price Lists']];

/* ── Fetch price lists assigned to this customer ──────────────────────────────────── */
try {
    $priceListManager = new PriceListManager($pdo);
    $priceLists = $priceListManager->getPriceListsForAccount($accountId);
} catch (PDOException $e) {
    $priceLists = [];
    $dbError = htmlspecialchars($e->getMessage());
}

$fileCount = count($priceLists);

/**
 * Get file type icon and color
 */
function getFileTypeStyle(string $type): array
{
    return match (strtolower($type)) {
        'pdf'          => ['icon' => 'bx-file', 'color' => '#b91c1c', 'bg' => '#fee2e2'],
        'xlsx', 'xls'  => ['icon' => 'bx-spreadsheet', 'color' => '#3d6b4f', 'bg' => '#dff0e5'],
        'doc', 'docx'  => ['icon' => 'bx-file-doc', 'color' => '#2563eb', 'bg' => '#dbeafe'],
        default        => ['icon' => 'bx-image', 'color' => '#b45309', 'bg' => '#fef3c7'],
    };
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title"><i class='bx bx-file me-2'></i>Price Lists</h1>
                    <p class="page-desc">Access and download your assigned pricing documents</p>
                </div>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error-circle me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <!-- Summary strip -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:12px 10px;">
                        <div style="font-size:20px;font-weight:800;color:var(--text);"><?= $fileCount ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Available Documents</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:12px 10px;">
                        <?php
                        $pdfCount = count(array_filter($priceLists, fn($p) => strtolower($p['file_type']) === 'pdf'));
                        ?>
                        <div style="font-size:20px;font-weight:800;color:#b91c1c;"><?= $pdfCount ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">PDF Files</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:12px 10px;">
                        <?php
                        $excelCount = count(array_filter($priceLists, fn($p) => in_array(strtolower($p['file_type']), ['xlsx', 'xls'])));
                        ?>
                        <div style="font-size:20px;font-weight:800;color:#3d6b4f;"><?= $excelCount ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Excel Files</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:12px 10px;">
                        <?php
                        $otherCount = $fileCount - $pdfCount - $excelCount;
                        ?>
                        <div style="font-size:20px;font-weight:800;color:var(--primary);"><?= $otherCount ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Other Files</div>
                    </div>
                </div>
            </div>

            <!-- Price Lists Table -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-file text-primary'></i> My Price Lists</h6>
                    <input type="text" id="fileSearch" class="form-control form-control-sm"
                        placeholder="Search…" style="width:200px;">
                </div>

                <?php if ($fileCount > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="priceListTable">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Type</th>
                                    <th>File Size</th>
                                    <th>Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($priceLists as $priceList):
                                    $fStyle = getFileTypeStyle($priceList['file_type']);
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:34px; height:34px; border-radius:var(--radius-sm, 6px); background:<?= $fStyle['bg'] ?>;
                                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                    <i class='bx <?= $fStyle['icon'] ?>' style="font-size:1.1rem; color:<?= $fStyle['color'] ?>;"></i>
                                                </div>
                                                <span style="font-weight:600; color:var(--text);">
                                                    <?= htmlspecialchars($priceList['file_name']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background:<?= $fStyle['bg'] ?>; color:<?= $fStyle['color'] ?>; font-weight:600;">
                                                <?= strtoupper(htmlspecialchars($priceList['file_type'])) ?>
                                            </span>
                                        </td>
                                        <td style="color:var(--muted);"><?= PriceListManager::formatFileSize($priceList['file_size']) ?></td>
                                        <td style="color:var(--muted); white-space:nowrap;"><?= date('M j, Y', strtotime($priceList['created_at'])) ?></td>
                                        <td class="text-end">
                                            <a href="<?= htmlspecialchars($priceListManager->getFileUrl($priceList['file_path'])) ?>"
                                                class="btn btn-sm btn-primary" download>
                                                <i class='bx bx-download me-1'></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <div class="empty-state" style="padding:80px 20px;">
                            <i class='bx bx-file-blank empty-state-icon'></i>
                            <p class="empty-state-title">No price lists assigned</p>
                            <p class="empty-state-desc">
                                You don't have any price lists assigned to you yet.<br>
                                Please check back later.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </main>


    <?php include __DIR__ . '/includes/footer.php'; ?>


    <script>
        // Search
        (function() {
            const search = document.getElementById('fileSearch');
            if (!search) return;
            const tbody = document.querySelector('#priceListTable tbody');
            if (!tbody) return;

            search.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                tbody.querySelectorAll('tr').forEach(row => {
                    row.style.display = !q || row.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        })();
    </script>

