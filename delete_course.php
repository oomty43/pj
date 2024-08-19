<?php
// เริ่มต้น session
session_start(); 

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['s_id'])) {
    header('Location: login.php');
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "project");
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่ามี c_id ถูกส่งมาหรือไม่
if (isset($_GET['c_id'])) {
    $c_id = $_GET['c_id'];

    // ลบข้อมูลจากตาราง course
    $sql = "DELETE FROM course WHERE c_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $c_id);

    if ($stmt->execute()) {
        echo "ลบข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $conn->error;
    }

    // เปลี่ยนเส้นทางกลับไปยังหน้าเดิม
    header("Location: stdprofile.php");
    exit();
} else {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
}

$conn->close();
?>
