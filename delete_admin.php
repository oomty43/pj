<?php
// เชื่อมต่อกับฐานข้อมูล
session_start();
include 'db_connect.php';

if (!isset($_SESSION['a_st']) || $_SESSION['a_st'] < 2) {
    echo "<script>alert('คุณไม่มีสิทธิ์ในการเข้าถึงหน้านี้');</script>";
    header("Location: mainadmin.php");
    exit();
}

$message = '';

if (isset($_GET['a_user'])) {
    $a_user = $_GET['a_user'];

    $sql = "DELETE FROM admin WHERE a_user=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $a_user);

    if ($stmt->execute()) {
        $message = "ลบข้อมูลสำเร็จ";
    } else {
        $message = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $conn->error;
    }

    $stmt->close();
} else {
    $message = "คำขอไม่ถูกต้อง";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังที่เข้ม */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
            width: 80%;
            max-width: 600px; /* จำกัดขนาดความกว้าง */
            color: #fff; /* สีตัวอักษร */
            text-align: center;
        }
        h2 {
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }
        p.message {
            font-size: 18px;
            margin-bottom: 20px;
        }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        a.button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ลบข้อมูลผู้ดูแลระบบ</h2>
        <p class="message"><?php echo $message; ?></p>
        <a href="display_admin.php" class="button">กลับไปยังตารางผู้ดูแลระบบ</a>
    </div>
</body>
</html>
