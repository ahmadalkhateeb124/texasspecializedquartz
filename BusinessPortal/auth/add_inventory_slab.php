<?php

/**
 * auth/add_inventory_slab.php — Create a new inventory slab (admin only).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'errors' => []];

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    $response['message'] = 'Access denied.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['message'] = 'Method not allowed.';
    echo json_encode($response);
    exit;
}

$name     = trim($_POST['name'] ?? '');
$material = in_array($_POST['material_type'] ?? 'granite', InventorySlabRepository::MATERIAL_TYPES, true)
                ? $_POST['material_type'] : 'granite';
$size     = trim($_POST['size']     ?? '3 CM');
$quantity = (int)($_POST['quantity'] ?? 0);
$sort     = (int)($_POST['sort_order'] ?? 0);
$status   = in_array($_POST['status'] ?? 'active', ['active', 'inactive'], true)
                ? $_POST['status'] : 'active';

$errors = [];
if (!$name) $errors[] = 'Slab name is required';

if ($errors) {
    $response['message'] = 'Validation failed';
    $response['errors']  = $errors;
    echo json_encode($response);
    exit;
}

try {
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $uploader = new FileUploader(
            __DIR__ . '/../../images/InventoryImag',
            10 * 1024 * 1024,
            ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        );
        $uploaded = $uploader->save($_FILES['image']);
        $image    = 'images/InventoryImag/' . $uploaded['stored_name'];
    }

    $id = (new InventorySlabRepository($pdo))->create([
        'name'          => $name,
        'material_type' => $material,
        'size'          => $size,
        'quantity'      => $quantity,
        'image'         => $image,
        'sort_order'    => $sort,
        'status'        => $status,
    ]);

    $response['success'] = true;
    $response['message'] = 'Inventory slab added successfully';
    $response['slab_id'] = $id;
    echo json_encode($response);
} catch (RuntimeException $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    echo json_encode($response);
}
