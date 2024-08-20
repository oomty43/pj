<?php
session_start();
include 'std_con.php';

if (isset($_GET['e_id'])) {
    $e_id = $_GET['e_id'];

    // Delete the record from the Events table
    $sql = "DELETE FROM ev WHERE e_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $e_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลกิจกรรมสำเร็จ'); window.location='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location='stdaward.php';</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลที่ต้องการลบ'); window.location='stdaward.php';</script>";
}

$conn->close();
?>
