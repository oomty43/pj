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

// เมื่อฟอร์มถูกส่ง
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_na = $_POST['c_na'];
    $c_add = $_POST['c_add'];
    $c_date = $_POST['c_date'];
    $s_id = $_SESSION['s_id'];

    $sql = "INSERT INTO course (c_na, c_add, c_date, s_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $c_na, $c_add, $c_date, $s_id);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลสำเร็จ!'); window.location.href='stdprofile.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลการอบรม</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-save {
            display: inline-block;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-save:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>เพิ่มข้อมูลการอบรม (Course)</h2>
        <form method="POST" action="add_course.php">
            <div class="form-group">
                <label for="c_na">ชื่อโครงการอบรม</label>
                <input type="text" id="c_na" name="c_na" required>
            </div>
            <div class="form-group">
                <label for="c_add">ชื่อสถานที่อบรม</label>
                <input type="text" id="c_add" name="c_add" required>
            </div>
            <div class="form-group">
                <label for="c_date">วันที่อบรม</label>
                <input type="date" id="c_date" name="c_date" required>
            </div>
            <button type="submit" class="btn-save">บันทึกข้อมูล</button>
        </form>
    </div>
</body>
</html>
