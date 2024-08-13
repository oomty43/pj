<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ MySQL
$password = "";      // รหัสผ่าน MySQL (ถ้ามี)
$dbname = "project"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งค่า id มา
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // เตรียมคำสั่ง SQL เพื่อทำการลบข้อมูล
    $sql = "DELETE FROM information WHERE i_id = ?";
    
    // เตรียม statement เพื่อป้องกัน SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); // ผูกตัวแปร $id เป็นประเภท integer
        if ($stmt->execute()) {
            // หากลบสำเร็จ
            echo "<script>alert('ลบข้อมูลสำเร็จ'); window.location.href='display_information.php';</script>";
        } else {
            // หากลบไม่สำเร็จ
            echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location.href='display_information.php';</script>";
        }
        $stmt->close();
    }
} else {
    // หากไม่มี id ส่งมา
    echo "<script>alert('ไม่พบข้อมูลที่ต้องการลบ'); window.location.href='display_information.php';</script>";
}

$conn->close();
?>