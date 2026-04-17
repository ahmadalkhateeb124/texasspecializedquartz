<?php
$host = 'localhost';
$db = 'u557236614_comment';
$user = 'u557236614_Comment';
$pass = 'Comment@@2025';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    $errorMessage = "فشل الاتصال: " . $e->getMessage();
    echo "<script>console.error(" . json_encode($errorMessage) . ");</script>";
    exit;
}
?>
