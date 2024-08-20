<?php
session_start();
include 'std_con.php';

if (isset($_GET['ce_id'])) {
    $ce_id = $_GET['ce_id'];

    // Delete the record from the Certificates table
    $sql = "DELETE FROM certi WHERE ce_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ce_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลใบรับรองสำเร็จ'); window.location='mainstd.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location='mainstd.php';</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลที่ต้องการลบ'); window.location='mainstd.php';</script>";
}

$conn->close();
?>
