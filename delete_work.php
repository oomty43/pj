<?php
session_start();
include 'std_con.php';

if (isset($_GET['w_id'])) {
    $w_id = $_GET['w_id'];

    // Delete the record from the Work History table
    $sql = "DELETE FROM wk WHERE w_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $w_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลการทำงานสำเร็จ'); window.location='mainstd.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location='mainstd.php';</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลที่ต้องการลบ'); window.location='mainstd.php';</script>";
}

$conn->close();
?>
