<?php
session_start();
require_once __DIR__ . '/../partials/conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit();
}

$user_id = intval($_SESSION['user_id']);
$title = trim($_POST['product_title'] ?? '');
$quantity = max(1, intval($_POST['Quantity'] ?? 1));
$color_name = trim($_POST['ColorName'] ?? '');
$size = trim($_POST['Size'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$availability = $_POST['availability'] ?? 'Available in store';

// التحقق من البيانات
$errors = [];
if (empty($title)) $errors['product_title'] = 'Title is required';
if ($quantity < 1) $errors['Quantity'] = 'Quantity must be at least 1';
if (empty($color_name)) $errors['ColorName'] = 'Color is required';
if (empty($size)) $errors['Size'] = 'Size is required';
if ($price <= 0) $errors['price'] = 'Price must be greater than 0';

if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    $errors['product_image'] = 'Image is required';
} else {
    $image_file = $_FILES['product_image'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($image_file['type'], $allowed_types)) {
        $errors['product_image'] = 'Invalid image type';
    }
    if ($image_file['size'] > 5 * 1024 * 1024) {
        $errors['product_image'] = 'Image size exceeds 5 MB';
    }
}

if (!empty($errors)) {
    $response['errors'] = $errors;
    echo json_encode($response);
    exit();
}

// إنشاء مجلد الصور
$upload_dir = __DIR__ . '/../assets/products/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// رفع الصورة
$image_extension = pathinfo($image_file['name'], PATHINFO_EXTENSION);
$image_filename = 'product_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($image_extension);
$destination = $upload_dir . $image_filename;

if (!move_uploaded_file($image_file['tmp_name'], $destination)) {
    $response['errors']['product_image'] = 'Failed to upload image';
    echo json_encode($response);
    exit();
}

try {
    // إدراج في قاعدة البيانات - الترتيب الصحيح مع السعر
    $stmt = $pdo->prepare("
        INSERT INTO products (user_id, title, quantity, color_name, size, price, availability, image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $title, $quantity, $color_name, $size, $price, $availability, $image_filename]);

    $product_id = $pdo->lastInsertId();

    $response['success'] = true;
    $response['message'] = 'Product added successfully';
    $response['product_id'] = $product_id;
    $response['image_url'] = '/assets/products/' . $image_filename;
} catch (PDOException $e) {
    // حذف الصورة في حالة فشل قاعدة البيانات
    if (file_exists($destination)) unlink($destination);
    $response['message'] = 'Database error';
    $response['errors']['database'] = $e->getMessage();
}

echo json_encode($response);
exit();
