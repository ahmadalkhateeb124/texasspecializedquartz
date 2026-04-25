<?php
// update_product.php - Handle product updates with image uploads
require_once __DIR__ . '/../src/session.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/auth-check.php';

header('Content-Type: application/json');

try {
    // Validate product ID
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($product_id <= 0) throw new Exception("Invalid product ID");

    // Check if product exists
    $check_stmt = $pdo->prepare("SELECT id, image FROM products WHERE id = ?");
    $check_stmt->execute([$product_id]);
    $product = $check_stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) throw new Exception("Product not found");

    $pdo->beginTransaction();


    // Handle image upload
    $image_path = $product['image'];
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        // Validate image type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($_FILES['product_image']['tmp_name']);

        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("Invalid image format. Allowed: JPG, PNG, GIF, WebP");
        }

        // Validate file size (5MB max)
        $max_size = 5 * 1024 * 1024;
        if ($_FILES['product_image']['size'] > $max_size) {
            throw new Exception("Image too large (max 5MB)");
        }

        // Create unique filename
        $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $new_filename = 'product_' . time() . '_' . uniqid() . '.' . $ext;
        $upload_dir = __DIR__ . '/../assets/products/';

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_path = $upload_dir . $new_filename;

        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
            throw new Exception("Failed to upload image");
        }

        // Delete old image if exists
        if (!empty($product['image']) && file_exists($upload_dir . $product['image'])) {
            unlink($upload_dir . $product['image']);
        }

        $image_path = $new_filename;
    }

    // Collect form data
    $title = $_POST['product_title'] ?? '';
    $quantity = intval($_POST['Quantity'] ?? 0);
    $color_name = $_POST['ColorName'] ?? '';
    $size = $_POST['Size'] ?? '';
    $availability = $_POST['availability'] ?? 'Available in store';

    // Validation
    if (empty($title)) throw new Exception("Title is required");
    if ($quantity < 0) throw new Exception("Valid quantity is required");

    $valid_availability = ['Available in store', 'Reserved', 'Sold Out'];
    if (!in_array($availability, $valid_availability)) throw new Exception("Invalid availability status");

    // Update product
    $update_stmt = $pdo->prepare("
        UPDATE products SET
            title = ?,
            quantity = ?,
            color_name = ?,
            size = ?,
      
            image = ?,
            availability = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    $update_stmt->execute([
        $title,
        $quantity,
        $color_name,
        $size,

        $image_path,
        $availability,
        $product_id
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully',
        'product_id' => $product_id
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
