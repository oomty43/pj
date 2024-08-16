<?php
session_start(); // เริ่มต้น session

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['s_id'])) {
    header('Location: login.php'); // หากไม่ได้เข้าสู่ระบบ ให้กลับไปที่หน้า login
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "project");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชั่นแปลงค่า s_pna
function getPrefix($s_pna) {
    switch ($s_pna) {
        case 1:
            return "นาย";
        case 2:
            return "นาง";
        case 3:
            return "นางสาว";
        default:
            return "ไม่ทราบ";
    }
}

// ดึงข้อมูลนักศึกษาจากฐานข้อมูลตาม user id ใน session
$s_id = $_SESSION['s_id'];
$sql = "SELECT s_pic, s_pna, s_na, s_la, s_id, s_pws, s_email, s_address, s_stat, s_bloodtype, s_race, s_birth, s_nationlity, religious, s_marriage, s_province, s_country FROM student WHERE s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $welcome_message = "ยินดีต้อนรับ : " . getPrefix($row["s_pna"]) . " " . $row["s_na"] . " " . $row["s_la"];
} else {
    $welcome_message = "ไม่พบข้อมูลนักศึกษา";
}

$sql = "SELECT c.c_id, c_na, c.c_add, c.c_date, c.s_id, s.s_na, s.s_la
        FROM course c
        INNER JOIN student s ON s.s_id = c.s_id";
$result = $conn->query($sql);

$sql = "SELECT sk.sk_id, sk.sk_na, sk.s_id, s.s_na, s.s_la
        FROM skill sk
        INNER JOIN student s ON s.s_id = sk.s_id";
$result = $conn->query($sql);

$sql = "SELECT its.its_id, its_name , its.its_date, its.its_file, s.s_na, s.s_la
        FROM its_history its
        INNER JOIN student s ON s.s_id = its.s_id";
$result = $conn->query($sql);

$sql = "SELECT pg.pg_id, pg.pg_name, s.s_na, s.s_la
        FROM program pg
        INNER JOIN student s ON s.s_id = pg.s_id";
$result = $conn->query($sql);

$sql = "SELECT w.w_id, w.w_na, w.w_date, s.s_na, s.s_la
        FROM wk w
        INNER JOIN student s ON s.s_id = w.s_id";
$result = $conn->query($sql);

$sql = "SELECT ce.ce_id, ce.ce_na, ce.ce_year , ce.og_na, ce.ce_file, s.s_na, s.s_la
        FROM certi ce
        INNER JOIN student s ON s.s_id = ce.s_id";
$result = $conn->query($sql);

$sql = "SELECT e.e_id , e.e_na, e.e_add , e.e_date, e.e_pic, s.s_na, s.s_la
        FROM ev e
        INNER JOIN student s ON s.s_id = e.s_id";
$result = $conn->query($sql);

?>




<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .banner {
            width: 100%;
            height: auto;
        }
        .nav-buttons {
            text-align: right;
            margin: 10px;
        }
        .nav-buttons a {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .nav-buttons a:hover {
            background-color: #0056b3;
        }
        .center-text {
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            color: #333;
        }
        .form-container {
            width: 50%;
            margin: 0 auto;
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
        .form-group input[type="password"],
        .form-group input[type="radio"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }
        .btn-save,
        .btn-cancel {
            display: inline-block;
            width: 48%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .welcome-message {
            margin: 20px;
            font-size: 20px;
            color: #333;
            text-align: right; /* ทำให้ข้อความชิดขวา */
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/testb.jpg" alt="Banner" class="banner">

        <!-- แสดงข้อความต้อนรับ -->
        <div class="welcome-message">
        <?php echo $welcome_message; ?>
    </div>

    <div class="nav-buttons">
        <a href="mainstd.php">หน้าหลัก</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
    </div>

