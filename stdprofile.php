<?php
session_start(); // เริ่มต้น session

include 'std_con.php';

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
$sql = "SELECT s_pna, s_na, s_la, s_pic, s_stat, s_pws, s_email FROM student WHERE s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $welcome_message = "  " . getPrefix($row["s_pna"]) . " " . $row["s_na"] . " " . $row["s_la"];
} else {
    $welcome_message = "ไม่พบข้อมูลนักศึกษา";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>
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
        .welcome-message {
            text-align: right;
            margin: 10px 20px; /* เพิ่ม margin เพื่อให้ห่างจากขอบขวา */
            font-size: 18px;
            color: #333;
        }
        .form-container {
            width: 80%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="radio"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group img {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
            margin: 0 auto;
        }
        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }
        .btn-edit {
            display: block;
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
        .btn-edit:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/banner.jpg" alt="Banner" class="banner">

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="mainstd.php">หน้าหลัก</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
        <a href="logout.php">ออกจากระบบ</a>
    </div>

    <!-- แสดงข้อความต้อนรับ -->
    <div class="welcome-message">
        <?php echo $welcome_message; ?>
    </div>

    <!-- Centered Text -->
    <div class="center-text">
        ข้อมูลส่วนตัว
    </div>

    <!-- Student Information Form -->
    <div class="form-container">
        <?php
        // เชื่อมต่อฐานข้อมูล
        $conn = new mysqli("localhost", "root", "", "project");

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
        }

        // ตั้งค่าตัวแปร s_id ให้เป็นค่าที่ได้จากการ login
        $s_id = $_SESSION['s_id'];

        // คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง student
        $sql = "SELECT s_pic, s_pna, s_na, s_la, s_id, s_pws, s_stat, s_email FROM student WHERE s_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $s_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
            <div class="form-group">
                <label>รูปภาพ</label>
                <img src="uploads/<?php echo $row['s_pic']; ?>" alt="Student Picture">
            </div>

            <div class="form-group">
                <label>คำนำหน้าชื่อ</label>
                <input type="radio" name="s_pna" value="1" <?php echo $row['s_pna'] == 1 ? 'checked' : ''; ?> disabled> นาย
                <input type="radio" name="s_pna" value="2" <?php echo $row['s_pna'] == 2 ? 'checked' : ''; ?> disabled> นาง
                <input type="radio" name="s_pna" value="3" <?php echo $row['s_pna'] == 3 ? 'checked' : ''; ?> disabled> นางสาว
            </div>

            <div class="form-group">
                <label>ชื่อนักศึกษา</label>
                <input type="text" name="s_na" value="<?php echo $row['s_na']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>นามสกุล</label>
                <input type="text" name="s_la" value="<?php echo $row['s_la']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>รหัสนักศึกษา</label>
                <input type="text" name="s_id" value="<?php echo $row['s_id']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>รหัสผ่าน</label>
                <input type="text" name="s_pws" value="<?php echo $row['s_pws']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>อีเมล</label>
                <input type="text" name="s_email" value="<?php echo $row['s_email']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>สถานะ</label>
                <input type="radio" name="s_stat" value="1" <?php echo $row['s_stat'] == 1 ? 'checked' : ''; ?> disabled> ยังคงศึกษาอยู่
                <input type="radio" name="s_stat" value="0" <?php echo $row['s_stat'] == 0 ? 'checked' : ''; ?> disabled> จบการศึกษาแล้ว
            </div>

            <a href="editstd.php" class="btn-edit">แก้ไข</a>

        <?php
        } else {
            echo "<p>ไม่พบข้อมูลนักศึกษา</p>";
        }

        // ปิดการเชื่อมต่อ
        $stmt->close();
        $conn->close();
        ?>
    </div>

</body>
</html>
