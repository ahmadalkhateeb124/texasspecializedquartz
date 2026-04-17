<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check customer account status
$account_id = isCustomer() ? ($_SESSION['account_id'] ?? null) : null;
if ($account_id) {
    $stmt = $pdo->prepare("SELECT status FROM accounts WHERE id = ?");
    $stmt->execute([$account_id]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$account || $account['status'] !== 'Active') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Account access denied.']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$order_id = $_POST['order_id'] ?? 0;
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

$customer_name   = trim($_POST['customer_name'] ?? '');
$phone           = trim($_POST['phone'] ?? '');
$address         = trim($_POST['address'] ?? '');
$sales_rep       = trim($_POST['sales_rep'] ?? '');
$sales_rep_phone = trim($_POST['sales_rep_phone'] ?? '');
$city            = trim($_POST['city'] ?? '');
$zip_code        = trim($_POST['ZipCode'] ?? '');
$po_number       = trim($_POST['po_number'] ?? '');
$notes           = trim($_POST['notes'] ?? '');
$delete_image    = $_POST['delete_image'] ?? '0';

$errors = [];
if (empty($customer_name)) $errors[] = 'Customer name is required';
if (empty($phone)) $errors[] = 'Phone number is required';
if (empty($address)) $errors[] = 'Address is required';
if (empty($city)) $errors[] = 'City is required';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
    exit;
}

try {
    // Load existing order to get current image name
    $stmt = $pdo->prepare("SELECT image FROM fabrication_orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Handle file upload and delete flag
    $image_path = $order['image'];
    if ($delete_image === '1') {
        $image_path = null;
        if (!empty($order['image']) && file_exists(__DIR__ . '/../assets/products/' . $order['image'])) {
            @unlink(__DIR__ . '/../assets/products/' . $order['image']);
        }
    }

    if (!empty($_FILES['image']['name'])) {
        // New file uploaded
        $file_name  = time() . '_' . basename($_FILES['image']['name']);
        $target_dir = __DIR__ . '/../assets/products/';

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if ($_FILES['image']['size'] > 20 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File too large. Max 20MB.']);
            exit;
        }

        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/zip'
        ];

        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
            exit;
        }

        $target_file = $target_dir . $file_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
            exit;
        }

        // Delete old file if exists
        if (!empty($order['image']) && file_exists($target_dir . $order['image'])) {
            unlink($target_dir . $order['image']);
        }

        $image_path = $file_name;
    }

    $stmt = $pdo->prepare("
        UPDATE fabrication_orders SET
            customer_name = ?,
            phone = ?,
            address = ?,
            sales_rep = ?,
            sales_rep_phone = ?,
            city = ?,
            zip_code = ?,
            po_number = ?,
            notes = ?,
            image = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([
        $customer_name,
        $phone,
        $address,
        $sales_rep,
        $sales_rep_phone,
        $city,
        $zip_code,
        $po_number,
        $notes,
        $image_path,
        $order_id
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

/* ===================== JOB SECTIONS UPDATE ===================== */
if (!empty($_POST['job_types']) && is_array($_POST['job_types'])) {
    try {
        // Delete existing job sections
        $delete_stmt = $pdo->prepare("DELETE FROM job_sections WHERE order_id = ?");
        $delete_stmt->execute([$order_id]);

        // Insert updated job sections
        $job_stmt = $pdo->prepare("
            INSERT INTO job_sections
            (order_id, job_type, job_type_other, material_type, material_other,
             thickness, thickness_custom, material_color,
             sink_provider, sink_type, sink_style, sink_style_other,
             edge_profile, edge_profile_custom, tear_out)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($_POST['job_types'] as $index => $job_type) {
            $job_type = trim($job_type);
            if (empty($job_type)) continue;

            $job_type_other = '';
            if ($job_type === 'other') {
                $job_type_other = trim($_POST['job_type_other'][$index] ?? '');
                if (empty($job_type_other)) continue;
            }

            $material_type = trim($_POST['material_type'][$index] ?? '');
            $material_other = trim($_POST['material_other'][$index] ?? '');
            $thickness = trim($_POST['thickness'][$index] ?? '');
            $thickness_custom = trim($_POST['thickness_custom'][$index] ?? '');
            $material_color = trim($_POST['material_color'][$index] ?? '');
            $sink_provider = trim($_POST['sink_provider'][$index] ?? '');
            $sink_type = trim($_POST['sink_type'][$index] ?? '');
            $sink_style = trim($_POST['sink_style'][$index] ?? '');
            $sink_style_other = trim($_POST['sink_style_other'][$index] ?? '');
            $edge_profile = trim($_POST['edge_profile'][$index] ?? '');
            $edge_profile_custom = trim($_POST['edge_profile_custom'][$index] ?? '');
            $tear_out = (isset($_POST['tear_out'][$index]) && $_POST['tear_out'][$index] === 'yes') ? 'yes' : 'no';

            $job_stmt->execute([
                $order_id,
                $job_type,
                $job_type_other ?: null,
                $material_type,
                $material_other,
                $thickness,
                $thickness_custom,
                $material_color,
                $sink_provider,
                $sink_type,
                $sink_style,
                $sink_style_other,
                $edge_profile,
                $edge_profile_custom,
                $tear_out
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error updating job sections']);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
