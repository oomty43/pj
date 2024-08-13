<?php
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

if (isset($_GET['a_user'])) {
    $a_user = $_GET['a_user'];

    $sql = "DELETE FROM admin WHERE a_user=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $a_user);

    if ($stmt->execute()) {
        echo "Record deleted successfully. <a href='display_admin.php'>Back to Admin Table</a>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>
