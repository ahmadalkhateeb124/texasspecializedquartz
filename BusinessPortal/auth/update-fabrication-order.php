<?php
// update-fabrication-order.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

if (!isset($_POST['order_id']) || empty(trim($_POST['order_id']))) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required']);
    exit;
}

$order_id = intval($_POST['order_id']);

try {
    // Start transaction
    $pdo->beginTransaction();

    // Check if order exists
    $check_sql = "SELECT id, image FROM fabrication_orders WHERE id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$order_id]);

    if ($check_stmt->rowCount() === 0) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    $existing_order = $check_stmt->fetch(PDO::FETCH_ASSOC);

    // Handle image upload
    $image_filename = $existing_order['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Remove old image if exists
        if (!empty($existing_order['image'])) {
            $old_image_path = __DIR__ . '/../assets/products/' . $existing_order['image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }

        // Upload new image
        $upload_dir = __DIR__ . '/../assets/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_filename = 'order_' . $order_id . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $image_filename;

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (!in_array(strtolower($file_extension), $allowed_types)) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }

        // Validate file size (20MB max)
        if ($_FILES['image']['size'] > 20 * 1024 * 1024) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'File size too large (max 20MB)']);
            exit;
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }
    } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] === 'on') {
        // Remove image if checkbox is checked
        if (!empty($existing_order['image'])) {
            $old_image_path = __DIR__ . '/../uploads/' . $existing_order['image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        $image_filename = null;
    }

    // Update fabrication order
    $update_order_sql = "UPDATE fabrication_orders SET 
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
        WHERE id = ?";

    $update_order_stmt = $pdo->prepare($update_order_sql);
    $update_order_stmt->execute([
        $_POST['customer_name'],
        $_POST['phone'] ?? null,
        $_POST['address'],
        $_POST['sales_rep'],
        $_POST['sales_rep_phone'],
        $_POST['city'],
        $_POST['ZipCode'],
        $_POST['po_number'] ?? null,
        $_POST['notes'] ?? null,
        $image_filename,
        $order_id
    ]);

    // Handle job sections
    if (isset($_POST['job_ids'])) {
        $job_ids = $_POST['job_ids'];
        $job_types = $_POST['job_type'] ?? [];
        $job_type_others = $_POST['job_type_other'] ?? [];
        $material_types = $_POST['material_type'] ?? [];
        $material_others = $_POST['material_other'] ?? [];
        $material_colors = $_POST['material_color'] ?? [];
        $thicknesses = $_POST['thickness'] ?? [];
        $thickness_customs = $_POST['thickness_custom'] ?? [];
        $sink_providers = $_POST['sink_provider'] ?? [];
        $sink_types = $_POST['sink_type'] ?? [];
        $sink_styles = $_POST['sink_style'] ?? [];
        $sink_style_others = $_POST['sink_style_other'] ?? [];
        $edge_profiles = $_POST['edge_profile'] ?? [];
        $edge_profile_customs = $_POST['edge_profile_custom'] ?? [];
        $tear_outs = $_POST['tear_out'] ?? [];

        // Delete job sections marked for deletion
        if (isset($_POST['delete_job_ids'])) {
            $delete_ids = array_map('intval', $_POST['delete_job_ids']);
            if (!empty($delete_ids)) {
                $placeholders = str_repeat('?,', count($delete_ids) - 1) . '?';
                $delete_sql = "DELETE FROM job_sections WHERE id IN ($placeholders)";
                $delete_stmt = $pdo->prepare($delete_sql);
                $delete_stmt->execute($delete_ids);
            }
        }

        // Update existing job sections and insert new ones
        for ($i = 0; $i < count($job_ids); $i++) {
            if ($job_ids[$i] === 'new') {
                // Insert new job section
                $insert_sql = "INSERT INTO job_sections (
                    order_id, job_type, job_type_other, material_type, material_other, material_color,
                    thickness, thickness_custom, sink_provider, sink_type, sink_style,
                    sink_style_other, edge_profile, edge_profile_custom, tear_out
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $insert_stmt = $pdo->prepare($insert_sql);
                $insert_stmt->execute([
                    $order_id,
                    $job_types[$i] ?? null,
                    !empty($job_type_others[$i]) ? $job_type_others[$i] : null,
                    $material_types[$i] ?? null,
                    !empty($material_others[$i]) ? $material_others[$i] : null,
                    $material_colors[$i] ?? null,
                    $thicknesses[$i] ?? null,
                    !empty($thickness_customs[$i]) ? $thickness_customs[$i] : null,
                    $sink_providers[$i] ?? null,
                    $sink_types[$i] ?? null,
                    $sink_styles[$i] ?? null,
                    !empty($sink_style_others[$i]) ? $sink_style_others[$i] : null,
                    $edge_profiles[$i] ?? null,
                    !empty($edge_profile_customs[$i]) ? $edge_profile_customs[$i] : null,
                    $tear_outs[$i] ?? null
                ]);
            } else {
                // Update existing job section
                $update_sql = "UPDATE job_sections SET 
                    job_type = ?, 
                    job_type_other = ?,  
                    material_type = ?, 
                    material_other = ?, 
                    material_color = ?, 
                    thickness = ?, 
                    thickness_custom = ?, 
                    sink_provider = ?, 
                    sink_type = ?, 
                    sink_style = ?, 
                    sink_style_other = ?, 
                    edge_profile = ?, 
                    edge_profile_custom = ?, 
                    tear_out = ?,
                    updated_at = NOW()
                    WHERE id = ?";

                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([
                    $job_types[$i] ?? null,
                    !empty($job_type_others[$i]) ? $job_type_others[$i] : null,
                    $material_types[$i] ?? null,
                    !empty($material_others[$i]) ? $material_others[$i] : null,
                    $material_colors[$i] ?? null,
                    $thicknesses[$i] ?? null,
                    !empty($thickness_customs[$i]) ? $thickness_customs[$i] : null,
                    $sink_providers[$i] ?? null,
                    $sink_types[$i] ?? null,
                    $sink_styles[$i] ?? null,
                    !empty($sink_style_others[$i]) ? $sink_style_others[$i] : null,
                    $edge_profiles[$i] ?? null,
                    !empty($edge_profile_customs[$i]) ? $edge_profile_customs[$i] : null,
                    $tear_outs[$i] ?? null,
                    $job_ids[$i]
                ]);
            }
        }
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order updated successfully'
    ]);

} catch (Exception $e) {
    // Rollback on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("Update order error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while updating the order. Please try again.'
    ]);
}