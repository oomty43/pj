<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

try {
    // สร้างการเชื่อมต่อฐานข้อมูล
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

    // ตั้งค่า PDO ให้โยนข้อผิดพลาดในกรณีที่เกิดข้อผิดพลาด
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>