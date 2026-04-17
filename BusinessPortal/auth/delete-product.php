<?php
// delete-product.php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/../partials/conn.php';

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// التحقق من وجود معرف المنتج
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit();
}

$product_id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

try {
    // التحقق من ملكية المنتج
    $check_stmt = $pdo->prepare("SELECT id, title, image, user_id FROM products WHERE id = ?");
    $check_stmt->execute([$product_id]);

    if ($check_stmt->rowCount() === 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    $product = $check_stmt->fetch(PDO::FETCH_ASSOC);

    // إذا أردت فقط السماح للمالك بحذف المنتج، يمكن التحقق من user_id
    if ($product['user_id'] != $user_id) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'No permission to delete this product']);
        exit();
    }

    $product_title = $product['title'];
    $product_image = $product['image'];

    // بدء المعاملة
    $pdo->beginTransaction();

    // حذف المنتج نفسه
    $delete_product_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $delete_product_stmt->execute([$product_id]);

    // حذف صورة المنتج من السيرفر
    if (!empty($product_image) && file_exists(__DIR__ . '/../assets/products/' . $product_image)) {
        unlink(__DIR__ . '/../assets/products/' . $product_image);
    }

    // تأكيد المعاملة
    $pdo->commit();

    // إرجاع الاستجابة الناجحة
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Product "' . $product_title . '" deleted successfully',
        'product_id' => $product_id
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
