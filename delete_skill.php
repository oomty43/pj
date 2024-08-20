<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

// ตรวจสอบว่ามีการส่งค่า sk_id มาหรือไม่
if (isset($_GET['sk_id'])) {
    $sk_id = $_GET['sk_id'];

    // ลบข้อมูลทักษะพิเศษจากฐานข้อมูล
    $sql = "DELETE FROM skill WHERE sk_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sk_id);

    if ($stmt->execute()) {
        echo "ลบข้อมูลสำเร็จ!";
        header("Location: mainstd.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบข้อมูล";
    }
} else {
    echo "ไม่ได้รับค่าที่ต้องการ";
    exit();
}
?>
