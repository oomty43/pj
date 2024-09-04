<?php

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['a_st']) || $_SESSION['a_st'] < 1) {
    echo "คุณไม่มีสิทธิ์ในการเข้าถึงหน้านี้";
    header("Location: loginadmin.php");
    exit();
}



// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "project");
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>
