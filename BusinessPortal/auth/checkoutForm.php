<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include database connection
require_once __DIR__ . '/../partials/conn.php';
require_once __DIR__ . '/../auth/auth-check.php';
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set JSON header
header('Content-Type: application/json');



// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Only POST allowed.'
    ]);
    exit;
}

try {
    // Connect to database


    // Required fields
    $required_fields = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'address',
        'zip_code',
        'payment_method',
        'cart_data',
        'cart_total',
        'cart_items_count'
    ];

    // Check for missing fields
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $missing_fields[] = $field;
        }
    }
    if ($missing_fields) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: ' . implode(', ', $missing_fields)
        ]);
        exit;
    }

    // Sanitize data
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name  = htmlspecialchars(trim($_POST['last_name']));
    $customer_name = $first_name . ' ' . $last_name;
    $email      = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone      = htmlspecialchars(trim($_POST['phone']));
    $country    = htmlspecialchars(trim($_POST['country']));
    $state      = htmlspecialchars(trim($_POST['state']));
    $city       = htmlspecialchars(trim($_POST['city']));
    $address    = htmlspecialchars(trim($_POST['address']));
    $apartment  = isset($_POST['apartment']) ? htmlspecialchars(trim($_POST['apartment'])) : '';
    $company    = isset($_POST['company']) ? htmlspecialchars(trim($_POST['company'])) : '';
    $zip_code   = htmlspecialchars(trim($_POST['zip_code']));
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));
    $cart_items = $_POST['cart_data'];
    $cart_total = floatval($_POST['cart_total']);
    $cart_items_count = intval($_POST['cart_items_count']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email format'
        ]);
        exit;
    }

    // Validate cart JSON
    if (!is_array(json_decode($cart_items, true))) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid cart data format'
        ]);
        exit;
    }

    // Insert order
    $stmt = $pdo->prepare("
        INSERT INTO orders
        (customer_name, email, phone, country, state, city, address,
         apartment, company, zip_code, payment_method, cart_items,
         cart_total, cart_items_count, created_at)
        VALUES
        (:customer_name, :email, :phone, :country, :state, :city, :address,
         :apartment, :company, :zip_code, :payment_method, :cart_items,
         :cart_total, :cart_items_count, NOW())
    ");

    $stmt->execute([
        ':customer_name' => $customer_name,
        ':email' => $email,
        ':phone' => $phone,
        ':country' => $country,
        ':state' => $state,
        ':city' => $city,
        ':address' => $address,
        ':apartment' => $apartment,
        ':company' => $company,
        ':zip_code' => $zip_code,
        ':payment_method' => $payment_method,
        ':cart_items' => $cart_items,
        ':cart_total' => $cart_total,
        ':cart_items_count' => $cart_items_count
    ]);

    $order_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $order_id
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Unexpected error: ' . $e->getMessage()
    ]);
}
