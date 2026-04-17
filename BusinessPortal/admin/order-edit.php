<?php

/**
 * admin/order-edit.php — Edit Fabrication Order
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: orders.php');
    exit;
}

try {
    // Get order
    $stmt = $pdo->prepare("SELECT * FROM fabrication_orders WHERE id = ?");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        header('Location: orders.php');
        exit;
    }

    // Prepare attachment info for preview
    $attachmentUrl = '';
    $orderImagePath = __DIR__ . '/../assets/products/' . ($order['image'] ?? '');
    if (!empty($order['image']) && file_exists($orderImagePath)) {
        $attachmentUrl = '../assets/products/' . $order['image'];
    }

    // Get job sections
    $stmt = $pdo->prepare("SELECT * FROM job_sections WHERE order_id = ? ORDER BY id");
    $stmt->execute([$id]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Precompute selected job types for initial UI state
    $existingJobTypes = [];
    foreach ($jobs as $job) {
        $existingJobTypes[$job['job_type']] = ($existingJobTypes[$job['job_type']] ?? 0) + 1;
    }
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

$currentUser = currentUser();
$pageTitle   = 'Edit Order';
$breadcrumb  = [
    ['label' => 'Orders', 'url' => 'orders.php'],
    ['label' => 'Edit Order #' . str_pad($id, 4, '0', STR_PAD_LEFT)]
];

include __DIR__ . '/includes/head.php';
?>



<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Edit Fabrication Order</h1>
                    <p class="page-subtitle">Order #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?> - <?= htmlspecialchars($order['customer_name']) ?></p>
                </div>
                <div class="page-actions">
                    <a href="orders.php" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Orders
                    </a>
                </div>
            </div>

            <form method="POST" id="orderForm" action="../auth/UpdateFabricationOrders.php"
                enctype="multipart/form-data" novalidate>

                <input type="hidden" name="order_id" value="<?= $id ?>">

                <div class="row g-4">

                    <!-- ── LEFT COLUMN ─────────────────────────────── -->
                    <div class="col-lg-8">

                        <!-- Customer Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-user text-primary'></i> Customer Information
                                </h6>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" class="form-control"
                                            placeholder="Full name" maxlength="100" required
                                            value="<?= htmlspecialchars($order['customer_name']) ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Customer Phone</label>
                                        <input type="tel" name="phone" class="form-control"
                                            placeholder="(123) 456-7890"
                                            value="<?= htmlspecialchars($order['phone']) ?>">
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Full street address" required
                                            value="<?= htmlspecialchars($order['address']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control"
                                            placeholder="City" required
                                            value="<?= htmlspecialchars($order['city']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">ZIP Code <span class="text-danger">*</span></label>
                                        <input type="text" name="ZipCode" class="form-control"
                                            placeholder="e.g. 78201" required
                                            value="<?= htmlspecialchars($order['zip_code']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">PO Number</label>
                                        <input type="text" name="po_number" class="form-control"
                                            placeholder="Purchase order (optional)"
                                            value="<?= htmlspecialchars($order['po_number']) ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <!-- spacer on sm -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Rep -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-briefcase text-primary'></i> Sales Representative
                                </h6>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">Sales Rep Name <span class="text-danger">*</span></label>
                                        <input type="text" name="sales_rep" class="form-control"
                                            placeholder="Representative name" required
                                            value="<?= htmlspecialchars($order['sales_rep']) ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Sales Rep Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="sales_rep_phone" class="form-control"
                                            placeholder="Phone number" required
                                            value="<?= htmlspecialchars($order['sales_rep_phone']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Area Selection -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-layer text-primary'></i> Select Job Areas
                                </h6>
                                <span class="form-text" style="font-size:12px;color:var(--color-text-sub);">
                                    Choose one or more job types
                                </span>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <!-- Kitchen -->
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card <?= isset($existingJobTypes['kitchen']) ? 'active' : '' ?>" id="card-kitchen" onclick="toggleJobCard('kitchen')">
                                            <input type="checkbox" id="jobKitchen" name="job_types[]"
                                                value="kitchen" class="visually-hidden" onchange="toggleJobSections()" <?= isset($existingJobTypes['kitchen']) ? 'checked' : '' ?>>
                                            <div class="job-card-icon">
                                                <i class='bx bx-restaurant'></i>
                                            </div>
                                            <div class="job-card-label">Kitchen</div>
                                            <div class="job-card-desc">Countertops, islands</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                    <!-- Bathroom -->
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card <?= isset($existingJobTypes['bathroom']) ? 'active' : '' ?>" id="card-bathroom" onclick="toggleJobCard('bathroom')">
                                            <input type="checkbox" id="jobBathroom" name="job_types[]"
                                                value="bathroom" class="visually-hidden" onchange="toggleJobSections()" <?= isset($existingJobTypes['bathroom']) ? 'checked' : '' ?>>
                                            <div class="job-card-icon">
                                                <i class='bx bx-bath'></i>
                                            </div>
                                            <div class="job-card-label">Bathroom</div>
                                            <div class="job-card-desc">Vanities, shower walls</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                    <!-- Master Bathroom -->
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card <?= isset($existingJobTypes['MasterBathroom']) ? 'active' : '' ?>" id="card-MasterBathroom" onclick="toggleJobCard('MasterBathroom')">
                                            <input type="checkbox" id="jobMasterBathroom" name="job_types[]"
                                                value="MasterBathroom" class="visually-hidden" onchange="toggleJobSections()" <?= isset($existingJobTypes['MasterBathroom']) ? 'checked' : '' ?>>
                                            <div class="job-card-icon">
                                                <i class='bx bxs-bath'></i>
                                            </div>
                                            <div class="job-card-label">Master Bath</div>
                                            <div class="job-card-desc">Tub decks, large vanities</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                    <!-- Other -->
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card <?= isset($existingJobTypes['other']) ? 'active' : '' ?>" id="card-other" onclick="toggleJobCard('other')">
                                            <input type="checkbox" id="jobOther" name="job_types[]"
                                                value="other" class="visually-hidden" onchange="toggleJobSections()" <?= isset($existingJobTypes['other']) ? 'checked' : '' ?>>
                                            <div class="job-card-icon">
                                                <i class='bx bx-plus-circle'></i>
                                            </div>
                                            <div class="job-card-label">Other</div>
                                            <div class="job-card-desc">Custom, commercial</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selection feedback -->
                                <div id="jobSelectionAlert" class="mt-3 d-none"
                                    style="padding:10px 14px;background:var(--color-primary-l, #e8f0fb);
                                    border-radius:var(--radius-sm);border-left:3px solid var(--color-primary);
                                    font-size:13px;color:var(--color-primary);">
                                    <i class='bx bx-info-circle me-1'></i>
                                    <span id="jobAlertText"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic job sections injected here -->
                        <div id="jobSectionsContainer">
                            <?php if (!empty($jobs)): ?>
                                <?php
                                $jobLabels = [
                                    'kitchen' => 'Kitchen',
                                    'bathroom' => 'Bathroom',
                                    'MasterBathroom' => 'Master Bath',
                                    'other' => 'Other'
                                ];
                                ?>
                                <?php foreach ($jobs as $job): ?>
                                    <?php
                                    $jobType = $job['job_type'];
                                    $jobLabel = $jobLabels[$jobType] ?? ucfirst($jobType);
                                    $materialType = $job['material_type'] ?? '';
                                    $thickness = $job['thickness'] ?? '';
                                    $edgeProfile = $job['edge_profile'] ?? '';
                                    $sinkProvider = $job['sink_provider'] ?? '';
                                    $sinkStyle = $job['sink_style'] ?? '';
                                    ?>
                                    <div class="card mb-3 job-section" data-job-type="<?= htmlspecialchars($jobType) ?>">
                                        <div class="card-header">
                                            <h6 class="card-title">
                                                <i class='bx bx-layer text-primary'></i>
                                                <span class="job-type-label"><?= htmlspecialchars($jobLabel) ?></span> Details
                                            </h6>
                                        </div>
                                        <div class="card-section">

                                            <div class="job-type-other-field mb-4" style="<?= $jobType === 'other' ? 'display:block;' : 'display:none;' ?>">
                                                <label class="form-label">Specify Job Type <span class="text-danger">*</span></label>
                                                <input type="text" name="job_type_other[]" class="form-control job-type-other-input"
                                                    placeholder="e.g. Laundry Room, Bar, Fireplace"
                                                    value="<?= htmlspecialchars($job['job_type_other'] ?? '') ?>" <?= $jobType === 'other' ? 'required' : '' ?>>
                                            </div>

                                            <p class="form-section-label">Material Information</p>
                                            <div class="row g-3 mb-4">
                                                <div class="col-sm-4">
                                                    <label class="form-label">Material Type <span class="text-danger">*</span></label>
                                                    <select name="material_type[]" class="form-select material-select" required>
                                                        <option value="">Select Material</option>
                                                        <option value="granite" <?= $materialType === 'granite' ? 'selected' : '' ?>>Granite</option>
                                                        <option value="marble" <?= $materialType === 'marble' ? 'selected' : '' ?>>Marble</option>
                                                        <option value="quartz" <?= $materialType === 'quartz' ? 'selected' : '' ?>>Quartz</option>
                                                        <option value="other" <?= $materialType === 'other' ? 'selected' : '' ?>>Other (specify)</option>
                                                    </select>
                                                    <div class="other-material-input mt-2" style="display:<?= $materialType === 'other' ? 'block' : 'none' ?>;">
                                                        <input type="text" name="material_other[]" class="form-control"
                                                            placeholder="Custom material type"
                                                            value="<?= htmlspecialchars($job['material_other'] ?? '') ?>" <?= $materialType === 'other' ? 'required' : '' ?>>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Thickness <span class="text-danger">*</span></label>
                                                    <select name="thickness[]" class="form-select thickness-select" required>
                                                        <option value="">Select Thickness</option>
                                                        <option value="2cm" <?= $thickness === '2cm' ? 'selected' : '' ?>>2 Cm</option>
                                                        <option value="3cm" <?= $thickness === '3cm' ? 'selected' : '' ?>>3 Cm</option>
                                                        <option value="custom" <?= $thickness === 'custom' ? 'selected' : '' ?>>Custom (specify)</option>
                                                    </select>
                                                    <div class="custom-thickness-input mt-2" style="display:<?= $thickness === 'custom' ? 'block' : 'none' ?>;">
                                                        <div class="input-group">
                                                            <input type="text" name="thickness_custom[]" class="form-control"
                                                                placeholder="Enter cm"
                                                                value="<?= htmlspecialchars($job['thickness_custom'] ?? '') ?>" <?= $thickness === 'custom' ? 'required' : '' ?>>
                                                            <span class="input-group-text">Cm</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Manufacture / Color <span class="text-danger">*</span></label>
                                                    <input type="text" name="material_color[]" class="form-control" required
                                                        placeholder="e.g. Bianco Carrara"
                                                        value="<?= htmlspecialchars($job['material_color'] ?? '') ?>">
                                                </div>
                                            </div>

                                            <p class="form-section-label">Sink Information</p>
                                            <div class="row g-3 mb-4">
                                                <div class="col-sm-4">
                                                    <label class="form-label">Sink Provider <span class="text-danger">*</span></label>
                                                    <select name="sink_provider[]" class="form-select sink-provider-select" required>
                                                        <option value="">Select Provider</option>
                                                        <option value="customer" <?= $sinkProvider === 'customer' ? 'selected' : '' ?>>Customer Sink</option>
                                                        <option value="aa_granite" <?= $sinkProvider === 'aa_granite' ? 'selected' : '' ?>>AA Granite Sink</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label">Sink Type <span class="text-danger">*</span></label>
                                                    <select name="sink_type[]" class="form-select sink-type-select" required>
                                                        <option value="">Select Sink Type</option>
                                                        <option value="undermount" <?= ($job['sink_type'] ?? '') === 'undermount' ? 'selected' : '' ?>>Under Mount</option>
                                                        <option value="dropin" <?= ($job['sink_type'] ?? '') === 'dropin' ? 'selected' : '' ?>>Drop-in</option>
                                                        <option value="farmhouse" <?= ($job['sink_type'] ?? '') === 'farmhouse' ? 'selected' : '' ?>>Farmhouse / Apron</option>
                                                        <option value="vessel" <?= ($job['sink_type'] ?? '') === 'vessel' ? 'selected' : '' ?>>Vessel</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4 sink-style-field" style="display:<?= $sinkProvider === 'aa_granite' ? 'block' : 'none' ?>;">
                                                    <label class="form-label">Sink Style <span class="text-danger">*</span></label>
                                                    <select name="sink_style[]" class="form-select sink-style-select" <?= $sinkProvider === 'aa_granite' ? 'required' : '' ?>>
                                                        <option value="">Select Style</option>
                                                        <option value="standard_single_bowl" <?= ($sinkStyle === 'standard_single_bowl') ? 'selected' : '' ?>>Standard Single Bowl</option>
                                                        <option value="standard_single_50_50" <?= ($sinkStyle === 'standard_single_50_50') ? 'selected' : '' ?>>Standard Single 50/50</option>
                                                        <option value="standard_single_60_40" <?= ($sinkStyle === 'standard_single_60_40') ? 'selected' : '' ?>>Standard Single 60/40</option>
                                                        <option value="zero_radius_single_bowl" <?= ($sinkStyle === 'zero_radius_single_bowl') ? 'selected' : '' ?>>Zero Radius Single Bowl</option>
                                                        <option value="zero_radius_single_50_50" <?= ($sinkStyle === 'zero_radius_single_50_50') ? 'selected' : '' ?>>Zero Radius 50/50</option>
                                                        <option value="zero_radius_single_60_40" <?= ($sinkStyle === 'zero_radius_single_60_40') ? 'selected' : '' ?>>Zero Radius 60/40</option>
                                                        <option value="bathroom_rectangle_white" <?= ($sinkStyle === 'bathroom_rectangle_white') ? 'selected' : '' ?>>Bathroom Rectangle White</option>
                                                        <option value="bathroom_rectangle_bisque" <?= ($sinkStyle === 'bathroom_rectangle_bisque') ? 'selected' : '' ?>>Bathroom Rectangle Bisque</option>
                                                        <option value="bathroom_oval_white" <?= ($sinkStyle === 'bathroom_oval_white') ? 'selected' : '' ?>>Bathroom Oval White</option>
                                                        <option value="bathroom_oval_bisque" <?= ($sinkStyle === 'bathroom_oval_bisque') ? 'selected' : '' ?>>Bathroom Oval Bisque</option>
                                                        <option value="other" <?= ($sinkStyle === 'other') ? 'selected' : '' ?>>Other (specify)</option>
                                                    </select>
                                                    <div class="other-sink-style-input mt-2" style="display:<?= ($sinkStyle === 'other') ? 'block' : 'none' ?>;">
                                                        <input type="text" name="sink_style_other[]" class="form-control"
                                                            placeholder="Custom sink style"
                                                            value="<?= htmlspecialchars($job['sink_style_other'] ?? '') ?>" <?= ($sinkStyle === 'other') ? 'required' : '' ?>>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="form-section-label">Additional Details</p>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="form-label">Edge Profile</label>
                                                    <select name="edge_profile[]" class="form-select edge-select">
                                                        <option value="">Select Edge</option>
                                                        <option value="eased" <?= ($edgeProfile === 'eased') ? 'selected' : '' ?>>Flat "Ease"</option>
                                                        <option value="bullnose" <?= ($edgeProfile === 'bullnose') ? 'selected' : '' ?>>Demi</option>
                                                        <option value="bevel" <?= ($edgeProfile === 'bevel') ? 'selected' : '' ?>>3/8 Bevel</option>
                                                        <option value="radius" <?= ($edgeProfile === 'radius') ? 'selected' : '' ?>>3/8 Radius</option>
                                                        <option value="custom" <?= ($edgeProfile === 'custom') ? 'selected' : '' ?>>Custom (specify)</option>
                                                    </select>
                                                    <div class="custom-edge-input mt-2" style="display:<?= ($edgeProfile === 'custom') ? 'block' : 'none' ?>;">
                                                        <input type="text" name="edge_profile_custom[]" class="form-control"
                                                            placeholder="Describe edge profile"
                                                            value="<?= htmlspecialchars($job['edge_profile_custom'] ?? '') ?>" <?= ($edgeProfile === 'custom') ? 'required' : '' ?>>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label">Tear Out <span class="text-danger">*</span></label>
                                                    <select name="tear_out[]" class="form-select" required>
                                                        <option value="">Select</option>
                                                        <option value="yes" <?= ($job['tear_out'] ?? '') === 'yes' ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no" <?= ($job['tear_out'] ?? '') === 'no' ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Notes & Attachment -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-note text-primary'></i> Notes &amp; Attachment
                                </h6>
                            </div>
                            <div class="card-section">
                                <div class="mb-3">
                                    <label class="form-label">Special Instructions</label>
                                    <textarea name="notes" rows="3" class="form-control"
                                        placeholder="Any special requirements, delivery instructions…"
                                        maxlength="500"><?= htmlspecialchars($order['notes']) ?></textarea>
                                    <div style="font-size:11px;color:var(--color-text-sub);text-align:right;margin-top:4px;">
                                        <span id="notesCounter">0</span>/500
                                    </div>
                                </div>

                                <!-- File upload zone -->
                                <label class="form-label">Attachment <span style="font-size:11px;color:var(--color-text-sub);">(optional)</span></label>
                                <div class="image-upload-zone" id="fileUploadZone" style="cursor:pointer;position:relative;">
                                    <div id="filePlaceholder" <?= !empty($attachmentUrl) ? 'style="display:none;"' : '' ?>>
                                        <i class='bx bx-cloud-upload' style="font-size:36px;color:var(--color-text-sub);"></i>
                                        <p style="margin:8px 0 4px;font-weight:500;">Click to upload image or document</p>
                                        <p style="font-size:12px;color:var(--color-text-sub);">JPG, PNG, WebP, PDF, Word, Excel — max 20 MB</p>
                                    </div>
                                    <div id="filePreview" <?= empty($attachmentUrl) ? 'style="display:none;"' : '' ?>>
                                        <?php if (!empty($attachmentUrl)): ?>
                                            <?php
                                            $ext = strtolower(pathinfo($order['image'] ?? '', PATHINFO_EXTENSION));
                                            $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                            ?>
                                            <div style="position:relative;">
                                                <?php if (in_array($ext, $imageExts)): ?>
                                                    <img src="<?= htmlspecialchars($attachmentUrl) ?>" style="max-height:220px;max-width:100%;border-radius:var(--radius-sm);object-fit:contain;">
                                                <?php else: ?>
                                                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--color-bg);border-radius:var(--radius-sm);">
                                                        <i class='bx bx-file' style="font-size:32px;color:var(--color-primary);"></i>
                                                        <div>
                                                            <div style="font-weight:600;font-size:13px;"><a href="<?= htmlspecialchars($attachmentUrl) ?>" download style="color:inherit;text-decoration:none;"><?= htmlspecialchars($order['image']) ?></a></div>
                                                            <div style="font-size:11px;color:var(--color-text-sub);">Attached document</div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <button type="button" class="file-remove-btn" onclick="removeCurrentFile(event)" style="position:absolute;top:8px;right:8px;background:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);z-index:2;">
                                                    <i class='bx bx-x' style="font-size:16px;color:#666;"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <input type="file" name="image" id="fileInput"
                                        accept="image/*,.pdf,.doc,.docx,.xls,.xlsx"
                                        style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;z-index:0;pointer-events:auto;">
                                    <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
                                </div>

                                <?php if (!empty($attachmentUrl)): ?>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', () => {
                                            const filePreview = document.getElementById('filePreview');
                                            const attachmentUrl = '<?= addslashes($attachmentUrl) ?>';
                                            const fileName = '<?= addslashes($order['image']) ?>';
                                            const ext = fileName.split('.').pop().toLowerCase();
                                            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                                            let content = '';
                                            if (imageExts.includes(ext)) {
                                                content = `<img src="${attachmentUrl}" style="max-height:220px;max-width:100%;border-radius:var(--radius-sm);object-fit:contain;">`;
                                            } else {
                                                content = `<div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--color-bg);border-radius:var(--radius-sm);"><i class='bx bx-file' style="font-size:32px;color:var(--color-primary);"></i><div><div style="font-weight:600;font-size:13px;"><a href="${attachmentUrl}" download style="color:inherit;text-decoration:none;">${fileName}</a></div><div style="font-size:11px;color:var(--color-text-sub);">Attached document</div></div></div>`;
                                            }
                                            filePreview.innerHTML = `<div style="position:relative;margin-top:8px;">${content}<button type="button" class="file-remove-btn" style="position:absolute;top:8px;right:8px;background:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);z-index:2;"><i class='bx bx-x' style="font-size:16px;color:#666;"></i></button></div>`;
                                            filePreview.style.display = 'block';
                                            document.querySelector('.file-remove-btn').addEventListener('click', (e) => removeCurrentFile(e));
                                        });
                                    </script>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- ── RIGHT COLUMN ────────────────────────────── -->
                    <div class="col-lg-4">
                        <div class="card" style="position:sticky;top:calc(var(--header-h) + 16px);">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-check-shield text-primary'></i> Update Order</h6>
                            </div>
                            <div class="card-section">
                                <p style="font-size:13px;color:var(--color-text-sub);margin-bottom:16px;">
                                    Review all sections before updating. Required fields are marked
                                    <span class="text-danger">*</span>.
                                </p>

                                <!-- Summary chips -->
                                <div id="summaryChips" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;"></div>

                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                    <i class='bx bx-save' id="submitIcon"></i>
                                    Update Order
                                </button>
                                <a href="orders.php" class="btn btn-default w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>



    </div><!-- /.row -->
    </form>

    <!-- ── JOB SECTION TEMPLATE ─────────────────────────── -->
    <template id="jobSectionTemplate">
        <div class="card mb-3 job-section" data-job-type="">
            <div class="card-header">
                <h6 class="card-title">
                    <i class='bx bx-layer text-primary'></i>
                    <span class="job-type-label"></span> Details
                </h6>
            </div>
            <div class="card-section">

                <!-- Other job type specify -->
                <div class="job-type-other-field mb-4" style="display:none;">
                    <label class="form-label">Specify Job Type <span class="text-danger">*</span></label>
                    <input type="text" name="job_type_other[]" class="form-control job-type-other-input"
                        placeholder="e.g. Laundry Room, Bar, Fireplace">
                </div>

                <!-- Material Information -->
                <p class="form-section-label">Material Information</p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <label class="form-label">Material Type <span class="text-danger">*</span></label>
                        <select name="material_type[]" class="form-select material-select" required>
                            <option value="">Select Material</option>
                            <option value="granite">Granite</option>
                            <option value="marble">Marble</option>
                            <option value="quartz">Quartz</option>
                            <option value="other">Other (specify)</option>
                        </select>
                        <div class="other-material-input mt-2" style="display:none;">
                            <input type="text" name="material_other[]" class="form-control"
                                placeholder="Custom material type">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Thickness <span class="text-danger">*</span></label>
                        <select name="thickness[]" class="form-select thickness-select" required>
                            <option value="">Select Thickness</option>
                            <option value="20">2 Cm</option>
                            <option value="30">3 Cm</option>
                            <option value="custom">Custom (specify)</option>
                        </select>
                        <div class="custom-thickness-input mt-2" style="display:none;">
                            <div class="input-group">
                                <input type="text" name="thickness_custom[]" class="form-control"
                                    placeholder="Enter cm">
                                <span class="input-group-text">Cm</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Manufacture / Color <span class="text-danger">*</span></label>
                        <input type="text" name="material_color[]" class="form-control" required
                            placeholder="e.g. Bianco Carrara">
                    </div>
                </div>

                <!-- Sink Information -->
                <p class="form-section-label">Sink Information</p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <label class="form-label">Sink Provider <span class="text-danger">*</span></label>
                        <select name="sink_provider[]" class="form-select sink-provider-select" required>
                            <option value="">Select Provider</option>
                            <option value="customer">Customer Sink</option>
                            <option value="aa_granite">AA Granite Sink</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Sink Type <span class="text-danger">*</span></label>
                        <select name="sink_type[]" class="form-select sink-type-select" required>
                            <option value="">Select Sink Type</option>
                            <option value="undermount">Under Mount</option>
                            <option value="dropin">Drop-in</option>
                            <option value="farmhouse">Farmhouse / Apron</option>
                            <option value="vessel">Vessel</option>
                        </select>
                    </div>
                    <div class="col-sm-4 sink-style-field" style="display:none;">
                        <label class="form-label">Sink Style <span class="text-danger">*</span></label>
                        <select name="sink_style[]" class="form-select sink-style-select">
                            <option value="">Select Style</option>
                            <option value="standard_single_bowl">Standard Single Bowl</option>
                            <option value="standard_single_50_50">Standard Single 50/50</option>
                            <option value="standard_single_60_40">Standard Single 60/40</option>
                            <option value="zero_radius_single_bowl">Zero Radius Single Bowl</option>
                            <option value="zero_radius_single_50_50">Zero Radius 50/50</option>
                            <option value="zero_radius_single_60_40">Zero Radius 60/40</option>
                            <option value="bathroom_rectangle_white">Bathroom Rectangle White</option>
                            <option value="bathroom_rectangle_bisque">Bathroom Rectangle Bisque</option>
                            <option value="bathroom_oval_white">Bathroom Oval White</option>
                            <option value="bathroom_oval_bisque">Bathroom Oval Bisque</option>
                            <option value="other">Other (specify)</option>
                        </select>
                        <div class="other-sink-style-input mt-2" style="display:none;">
                            <input type="text" name="sink_style_other[]" class="form-control"
                                placeholder="Custom sink style">
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <p class="form-section-label">Additional Details</p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Edge Profile</label>
                        <select name="edge_profile[]" class="form-select edge-select">
                            <option value="">Select Edge</option>
                            <option value="eased">Flat "Ease"</option>
                            <option value="bullnose">Demi</option>
                            <option value="bevel">3/8 Bevel</option>
                            <option value="radius">3/8 Radius</option>
                            <option value="custom">Custom (specify)</option>
                        </select>
                        <div class="custom-edge-input mt-2" style="display:none;">
                            <input type="text" name="edge_profile_custom[]" class="form-control"
                                placeholder="Describe edge profile">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Tear Out <span class="text-danger">*</span></label>
                        <select name="tear_out[]" class="form-select" required>
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </template>

    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <style>
        /* ── Job type selection cards ─────────────────────── */
        .job-type-card {
            position: relative;
            border: 2px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s, background .18s, box-shadow .18s;
            user-select: none;
            background: var(--color-surface);
        }

        .job-type-card:hover {
            border-color: var(--color-primary);
            background: #f0f5fb;
        }

        .job-type-card.active {
            border-color: var(--color-primary);
            background: #e8f0fb;
            box-shadow: 0 0 0 3px rgba(44, 110, 203, .12);
        }

        .job-card-icon {
            font-size: 28px;
            color: var(--color-primary);
            margin-bottom: 6px;
            line-height: 1;
        }

        .job-type-card:not(.active) .job-card-icon {
            color: var(--color-text-sub);
        }

        .job-card-label {
            font-weight: 600;
            font-size: 13px;
            color: var(--color-text);
        }

        .job-card-desc {
            font-size: 11px;
            color: var(--color-text-sub);
            margin-top: 2px;
        }

        .job-card-check {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--color-primary);
            color: #fff;
            font-size: 12px;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .job-type-card.active .job-card-check {
            display: flex;
        }

        /* ── Form sub-section label ────────────────────────── */
        .form-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--color-text-sub);
            margin: 0 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--color-border);
        }

        /* ── File upload zone override ─────────────────────── */
        #fileUploadZone {
            min-height: 100px;
        }

        /* ── Summary chips ────────────────────────────────── */
        .summary-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            background: var(--color-primary-l, #e8f0fb);
            color: var(--color-primary);
            font-size: 12px;
            font-weight: 500;
        }

        .image-upload-zone .file-remove-btn {
            z-index: 3;
            pointer-events: auto;
        }

        .image-upload-zone .file-remove-btn i {
            pointer-events: none;
        }
    </style>

    <script>
        let activeJobs = new Set();

        /* ══════════════════════════════════════════════════════
        JOB CARD TOGGLE
        ══════════════════════════════════════════════════════ */
        function toggleJobCard(jobKey) {
            const cbId = 'job' + jobKey.charAt(0).toUpperCase() + jobKey.slice(1);
            const cb = document.getElementById(cbId);
            const card = document.getElementById('card-' + jobKey);
            if (!cb) return;
            cb.checked = !cb.checked;
            card?.classList.toggle('active', cb.checked);
            toggleJobSections();
            updateSummaryChips();
        }

        /* ══════════════════════════════════════════════════════
        BUILD / DESTROY JOB SECTIONS
        ══════════════════════════════════════════════════════ */
        const JOB_META = [{
                id: 'jobKitchen',
                key: 'kitchen',
                label: 'Kitchen'
            },
            {
                id: 'jobBathroom',
                key: 'bathroom',
                label: 'Bathroom'
            },
            {
                id: 'jobMasterBathroom',
                key: 'MasterBathroom',
                label: 'Master Bath'
            },
            {
                id: 'jobOther',
                key: 'other',
                label: 'Other'
            },
        ];

        function toggleJobSections() {
            const container = document.getElementById('jobSectionsContainer');
            const template = document.getElementById('jobSectionTemplate');
            const alert = document.getElementById('jobSelectionAlert');
            const alertText = document.getElementById('jobAlertText');

            const selected = JOB_META.filter(j => document.getElementById(j.id)?.checked);

            /* Update info bar */
            if (selected.length) {
                alert.classList.remove('d-none');
                alertText.textContent = 'Selected: ' + selected.map(j => j.label).join(', ');
            } else {
                alert.classList.add('d-none');
            }

            /* Remove deselected sections */
            container.querySelectorAll('.job-section').forEach(sec => {
                const k = sec.getAttribute('data-job-type');
                if (!selected.some(j => j.key === k)) {
                    sec.remove();
                    activeJobs.delete(k);
                }
            });

            /* Add newly selected sections */
            selected.forEach(j => {
                if (!activeJobs.has(j.key)) {
                    const frag = template.content.cloneNode(true);
                    const div = frag.querySelector('.job-section');
                    div.setAttribute('data-job-type', j.key);
                    div.querySelector('.job-type-label').textContent = j.label;

                    /* Show / hide "other" text input */
                    if (j.key === 'other') {
                        const otherField = div.querySelector('.job-type-other-field');
                        const otherInput = div.querySelector('.job-type-other-input');
                        if (otherField) otherField.style.display = 'block';
                        if (otherInput) otherInput.required = true;
                    }

                    container.appendChild(div);
                    activeJobs.add(j.key);
                    initSectionHandlers(div);
                }
            });

            updateSummaryChips();
        }

        /* ══════════════════════════════════════════════════════
        PER-SECTION CHANGE HANDLERS
        ══════════════════════════════════════════════════════ */
        function initSectionHandlers(sec) {
            /* Material type → other */
            bindToggle(
                sec.querySelector('select[name="material_type[]"]'),
                sec.querySelector('input[name="material_other[]"]'),
                sec.querySelector('.other-material-input')
            );

            /* Thickness → custom */
            bindToggle(
                sec.querySelector('select[name="thickness[]"]'),
                sec.querySelector('input[name="thickness_custom[]"]'),
                sec.querySelector('.custom-thickness-input')
            );

            /* Edge profile → custom */
            bindToggle(
                sec.querySelector('select[name="edge_profile[]"]'),
                sec.querySelector('input[name="edge_profile_custom[]"]'),
                sec.querySelector('.custom-edge-input')
            );

            /* Sink style → other */
            bindToggle(
                sec.querySelector('select[name="sink_style[]"]'),
                sec.querySelector('input[name="sink_style_other[]"]'),
                sec.querySelector('.other-sink-style-input')
            );

            /* Sink provider → show/hide sink style column */
            const providerSel = sec.querySelector('select[name="sink_provider[]"]');
            if (providerSel) {
                providerSel.addEventListener('change', () => {
                    const styleCol = sec.querySelector('.sink-style-field');
                    const styleSel = sec.querySelector('select[name="sink_style[]"]');
                    const isAAGranite = providerSel.value === 'aa_granite';
                    if (styleCol) styleCol.style.display = isAAGranite ? 'block' : 'none';
                    if (styleSel) {
                        styleSel.required = isAAGranite;
                        if (!isAAGranite) {
                            styleSel.value = '';
                            const otherInput = sec.querySelector('input[name="sink_style_other[]"]');
                            const otherBox = sec.querySelector('.other-sink-style-input');
                            if (otherInput) {
                                otherInput.value = '';
                                otherInput.required = false;
                            }
                            if (otherBox) otherBox.style.display = 'none';
                        }
                    }
                });
            }
        }

        function bindToggle(selectEl, inputEl, containerEl) {
            if (!selectEl || !inputEl || !containerEl) return;
            selectEl.addEventListener('change', () => {
                const isOther = ['other', 'custom'].includes(selectEl.value);
                containerEl.style.display = isOther ? 'block' : 'none';
                inputEl.required = isOther;
                if (!isOther) inputEl.value = '';
                if (isOther) inputEl.focus();
            });
        }

        /* ══════════════════════════════════════════════════════
        FILE UPLOAD PREVIEW
        ══════════════════════════════════════════════════════ */
        document.getElementById('fileInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            if (file.size > 20 * 1024 * 1024) {
                showToast('File must be under 20 MB.', 'error');
                this.value = '';
                return;
            }

            const allowed = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
            if (!allowed.includes(file.type)) {
                showToast('Invalid file type. Allowed: images, PDF, Word, Excel.', 'error');
                this.value = '';
                return;
            }

            const placeholder = document.getElementById('filePlaceholder');
            const preview = document.getElementById('filePreview');
            placeholder.style.display = 'none';
            preview.style.display = 'block';
            document.getElementById('deleteImageInput').value = '0';
            document.getElementById('fileInput').style.zIndex = '0';

            const renderPreview = (contentHtml) => {
                preview.innerHTML = `<div style="position:relative;">${contentHtml}
        <button type="button" class="file-remove-btn"
            style="position:absolute;top:8px;right:8px;background:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);z-index:2;">
            <i class='bx bx-x' style="font-size:16px;color:#666;"></i>
        </button>
    </div>`;
                const removeBtn = preview.querySelector('.file-remove-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', removeCurrentFile);
                }
                adjustFileInputZIndex();
            };

            const ext = file.name.split('.').pop().toLowerCase();
            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (imageExts.includes(ext) && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    renderPreview(`<img src="${e.target.result}" style="max-height:220px;max-width:100%;border-radius:var(--radius-sm);object-fit:contain;">`);
                };
                reader.readAsDataURL(file);
            } else {
                renderPreview(`<div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--color-bg);border-radius:var(--radius-sm);">` +
                    `<i class='bx bx-file' style="font-size:32px;color:var(--color-primary);"></i>` +
                    `<div>
            <div style="font-weight:600;font-size:13px;">${file.name}</div>` +
                    `<div style="font-size:11px;color:var(--color-text-sub);">${ext.toUpperCase()} · ${(file.size/1024/1024).toFixed(2)} MB</div>
        </div>
    </div>`);
            }

            fileInput.style.zIndex = '0';
        });

        function markInvalid(el) {
            if (!el) return;
            el.classList.add('is-invalid');
            el.addEventListener('input', () => el.classList.remove('is-invalid'), {
                once: true
            });
        }

        /* ══════════════════════════════════════════════════════
        VALIDATION
        ══════════════════════════════════════════════════════ */
        function validateForm() {
            const errors = [];

            /* Basic required fields */
            [
                ['input[name="customer_name"]', 'Customer name is required'],
                ['input[name="address"]', 'Address is required'],
                ['input[name="sales_rep"]', 'Sales rep is required'],
                ['input[name="sales_rep_phone"]', 'Sales rep phone is required'],
                ['input[name="city"]', 'City is required'],
                ['input[name="ZipCode"]', 'ZIP code is required'],
            ].forEach(([sel, msg]) => {
                const el = document.querySelector(sel);
                if (!el || !el.value.trim()) {
                    errors.push(msg);
                    markInvalid(el);
                }
            });

            /* Job areas */
            if (activeJobs.size === 0) {
                errors.push('Select at least one job area.');
            } else {
                document.querySelectorAll('.job-section').forEach(sec => {
                    const label = sec.querySelector('.job-type-label')?.textContent || 'Job';

                    if (sec.getAttribute('data-job-type') === 'other') {
                        const inp = sec.querySelector('input[name="job_type_other[]"]');
                        if (inp && !inp.value.trim()) {
                            errors.push(`Specify the custom job type in "${label}" section`);
                            markInvalid(inp);
                        }
                    }

                    sec.querySelectorAll('[required]').forEach(inp => {
                        const hidden = inp.closest('[style*="display: none"]') || inp.closest('[style*="display:none"]');
                        if (!hidden && !inp.value.trim()) {
                            const lbl = inp.closest('.col-sm-4, .col-sm-6')
                                ?.querySelector('label')?.textContent?.trim() || 'Field';
                            errors.push(`"${lbl}" is required in ${label} section`);
                            markInvalid(inp);
                        }
                    });
                });
            }

            return errors;
        }

        /* ══════════════════════════════════════════════════════
        FORM SUBMIT
        ══════════════════════════════════════════════════════ */
        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const errors = validateForm();
            if (errors.length) {
                errors.forEach(msg => showToast(msg, 'error'));
                return;
            }

            const btn = document.getElementById('submitBtn');
            const spinner = document.getElementById('submitSpinner');
            const icon = document.getElementById('submitIcon');
            btn.disabled = true;
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');

            /* Build FormData with explicit job_types[] order */
            const fd = new FormData(this);
            fd.delete('job_types[]');
            fd.delete('job_type_other[]');

            document.querySelectorAll('.job-section').forEach(sec => {
                const k = sec.getAttribute('data-job-type');
                fd.append('job_types[]', k);
                const otherInput = sec.querySelector('input[name="job_type_other[]"]');
                fd.append('job_type_other[]', otherInput ? otherInput.value.trim() : '');
            });

            try {
                const res = await fetch(this.action, {
                    method: 'POST',
                    body: fd
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message || 'Order updated.', 'success');
                    setTimeout(() => window.location.href = 'orders.php', 1200);
                } else {
                    showToast(data.message || 'Could not update order.', 'error');
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    icon.classList.remove('d-none');
                }
            } catch {
                showToast('Network error. Please try again.', 'error');
                btn.disabled = false;
                spinner.classList.add('d-none');
                icon.classList.remove('d-none');
            }
        });

        /* ══════════════════════════════════════════════════════
        INIT
        ══════════════════════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', () => {
            /* Notes counter */
            const notes = document.querySelector('textarea[name="notes"]');
            const counter = document.getElementById('notesCounter');
            if (notes && counter) {
                counter.textContent = notes.value.length;
                notes.addEventListener('input', () => {
                    if (notes.value.length > 500) notes.value = notes.value.slice(0, 500);
                    counter.textContent = notes.value.length;
                });
            }

            /* Prevent Enter from submitting on text inputs */
            document.querySelectorAll('#orderForm input:not([type="submit"])').forEach(inp => {
                inp.addEventListener('keydown', e => {
                    if (e.key === 'Enter') e.preventDefault();
                });
            });

            /* Initialize active job sections rendered by server */
            activeJobs = new Set(Array.from(document.querySelectorAll('input[name="job_types[]"]:checked')).map(el => el.value));

            document.querySelectorAll('.job-section').forEach(sec => {
                initSectionHandlers(sec);

                const materialSel = sec.querySelector('[name="material_type[]"]');
                const thicknessSel = sec.querySelector('[name="thickness[]"]');
                const edgeSel = sec.querySelector('[name="edge_profile[]"]');
                const sinkProviderSel = sec.querySelector('[name="sink_provider[]"]');
                const sinkStyleSel = sec.querySelector('[name="sink_style[]"]');

                if (materialSel) materialSel.dispatchEvent(new Event('change'));
                if (thicknessSel) thicknessSel.dispatchEvent(new Event('change'));
                if (edgeSel) edgeSel.dispatchEvent(new Event('change'));
                if (sinkProviderSel) sinkProviderSel.dispatchEvent(new Event('change'));
                if (sinkStyleSel) sinkStyleSel.dispatchEvent(new Event('change'));
            });

            updateSummaryChips();
            toggleJobSections();
            adjustFileInputZIndex();
        });

        function adjustFileInputZIndex() {
            const filePreview = document.getElementById('filePreview');
            const fileInput = document.getElementById('fileInput');
            if (!filePreview || !fileInput) return;

            if (filePreview.style.display === 'none' || filePreview.innerHTML.trim() === '') {
                fileInput.style.zIndex = '1';
            } else {
                fileInput.style.zIndex = '0';
            }
        }

        function maybeAdjustFileInputZIndex() {
            setTimeout(adjustFileInputZIndex, 100);
        }

        function loadExistingJobData() {
            // Pre-filled via server-side rendering of existing job sections.
            // No client-side section injection required.
        }

        /* Remove current file */
        window.removeCurrentFile = function(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            document.getElementById('deleteImageInput').value = '1';
            document.getElementById('filePlaceholder').style.display = 'block';
            document.getElementById('filePreview').style.display = 'none';
            document.getElementById('filePreview').innerHTML = '';
            document.getElementById('fileInput').value = '';
            document.getElementById('fileInput').style.zIndex = '1';
        };
    </script>