<?php
require_once __DIR__ . '/../auth/auth-check.php';
require_once __DIR__ . '/../partials/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// جلب الحقول من الفورم
$reservation_id = $_POST['reservation_id'] ?? null;

if (!$reservation_id) {
    die("Reservation ID is missing!");
}

// الحقول الرئيسية
$pickup_location = $_POST['pickup_location'] ?? null;
$dropoff_location = $_POST['dropoff_location'] ?? null;
$different_dropoff = isset($_POST['different_dropoff']) ? 1 : 0;
$new_dropoff_location = $_POST['new_dropoff_location'] ?? null;
$pickup_datetime = $_POST['pickup_datetime'] ?? null;
$dropoff_datetime = $_POST['dropoff_datetime'] ?? null;
$vehicle_id = $_POST['vehicle_id'] ?? null;
$customer_name = $_POST['customer_name'] ?? null;
$paid_amount = $_POST['paid_amount'] ?? 0;
$payment_method = $_POST['payment_method'] ?? null;

try {
    // تحديث الحجز
    $sql = "UPDATE reservations SET
    pickup_location = :pickup_location,
    dropoff_location = :dropoff_location,
    different_dropoff = :different_dropoff,
    new_dropoff_location = :new_dropoff_location,
    pickup_datetime = :pickup_datetime,
    dropoff_datetime = :dropoff_datetime,
    vehicle_id = :vehicle_id,
    customer_name = :customer_name,
    amount_paid = :amount_paid,
    payment_method = :payment_method
WHERE id = :reservation_id";


    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':pickup_location' => $pickup_location,
        ':dropoff_location' => $dropoff_location,
        ':different_dropoff' => $different_dropoff,
        ':new_dropoff_location' => $different_dropoff ? $new_dropoff_location : null,
        ':pickup_datetime' => $pickup_datetime,
        ':dropoff_datetime' => $dropoff_datetime,
        ':vehicle_id' => $vehicle_id,
        ':customer_name' => $customer_name,
        ':amount_paid' => $paid_amount,
        ':payment_method' => $payment_method,
        ':reservation_id' => $reservation_id
    ]);

    // إعادة التوجيه بعد التحديث
    header("Location: ../Reservations.php?id=" . urlencode($reservation_id));
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
