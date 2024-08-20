<?php
session_start(); 

include 'std_con.php';

if (!isset($_GET['pg_id'])) {
    echo "<script>alert('ไม่มี ID ที่ต้องการลบ!'); window.location.href='mainstd.php';</script>";
    exit();
}

$pg_id = $_GET['pg_id'];

// ลบข้อมูลโปรแกรม
$sql = "DELETE FROM program WHERE pg_id = ? AND s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $pg_id, $_SESSION['s_id']);

if ($stmt->execute()) {
    echo "<script>alert('ลบข้อมูลสำเร็จ!'); window.location.href='mainstd.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล! กรุณาลองใหม่'); window.location.href='mainstd.php';</script>";
}

$stmt->close();
$conn->close();
?>
