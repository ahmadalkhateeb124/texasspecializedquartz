<?php
// get_product.php
require_once __DIR__ . '/../src/session.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/../includes/db.php';

// التحقق من وجود معرف المنتج
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit();
}

$product_id = intval($_GET['id']);

try {
    // جلب بيانات المنتج فقط بالأعمدة المحددة
    $stmt = $pdo->prepare(" SELECT id, user_id, title, quantity, color_name, size, price, image, availability, created_at, updated_at
        FROM products
        WHERE id = ?
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    // إعداد البيانات مع القيم الافتراضية
    $productData = [
        'id' => $product['id'],
        'user_id' => $product['user_id'],
        'title' => $product['title'] ?? '',
        'quantity' => $product['quantity'] ?? 1,
        'color_name' => $product['color_name'] ?? '',
        'size' => $product['size'] ?? '',
        'price' => $product['price'] ?? 0,
        'image' => $product['image'] ?? '',
        'availability' => $product['availability'] ?? 'Available in store',
        'created_at' => $product['created_at'] ?? '',
        'updated_at' => $product['updated_at'] ?? '',
    ];

    // إعداد الاستجابة
    $response = [
        'success' => true,
        'product' => $productData
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
}

// إرجاع الاستجابة كـ JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
