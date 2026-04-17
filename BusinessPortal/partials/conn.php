<?php
$host = "localhost"; // استخدام IP بدلاً من localhost لتجنب مشاكل socket
$port = 3306;
$dbname = "u557236614_gr";
$username = "u557236614_gr";
$password = "C#$>x/Vg!3Pr";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
