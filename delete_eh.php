<?php
session_start();
include 'std_con.php';

if (isset($_GET['eh_id'])) {
    $eh_id = $_GET['eh_id'];

    // Delete the record from the Education History table
    $sql = "DELETE FROM edu_history WHERE eh_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eh_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลประวัติการศึกษาสำเร็จ'); window.location='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location=stdaward.php';</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลที่ต้องการลบ'); window.location='stdaward.php';</script>";
}

$conn->close();
?>
