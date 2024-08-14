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
$sql = "SELECT s_pic, s_pna, s_na, s_la, s_id, s_pws, s_stat, s_bloodtype, s_race, s_birth, s_nationlity, religious, s_marriage, s_province, s_country FROM student WHERE s_id = ?";
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

$stmt->close();

// จัดการการอัปเดตข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $s_pna = $_POST['s_pna'];
    $s_na = $_POST['s_na'];
    $s_la = $_POST['s_la'];
    $s_pws = $_POST['s_pws']; // อาจพิจารณาเปลี่ยนเป็น type="password" ถ้าต้องการความปลอดภัย
    $s_stat = $_POST['s_stat'];
    $s_bloodtype = $_POST['s_bloodtype'];
    $s_race = $_POST['s_race'];
    $s_birth = $_POST['s_birth'];
    $s_nationlity = $_POST['s_nationlity'];
    $religious = $_POST['religious'];
    $s_marriage = $_POST['s_marriage'];
    $s_province = $_POST['s_province'];
    $s_country = $_POST['s_country'];

    // เตรียมคำสั่ง SQL อัปเดตข้อมูล
    $sql_update = "UPDATE student SET s_pna = ?, s_na = ?, s_la = ?, s_pws = ?, s_stat = ?, s_bloodtype = ?, s_race = ?, s_birth = ?, s_nationlity = ?, religious = ?, s_marriage = ?, s_province = ?, s_country = ? WHERE s_id = ?";
    $stmt_update = $conn->prepare($sql_update);

    // ตรวจสอบชนิดของพารามิเตอร์
    $stmt_update->bind_param("isssssssssssi", $s_pna, $s_na, $s_la, $s_pws, $s_stat, $s_bloodtype, $s_race, $s_birth, $s_nationlity, $religious, $s_marriage, $s_province, $s_country, $s_id);

    if ($stmt_update->execute()) {
        echo "<p>ข้อมูลถูกอัปเดตเรียบร้อยแล้ว</p>";
    } else {
        echo "<p>เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error . "</p>";
    }

    $stmt_update->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลส่วนตัวนักศึกษา</title>
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
        .form-group input[type="date"] {
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
        .btn-group {
            display: flex;
            justify-content: space-between;
        }
        .btn-save,
        .btn-cancel {
            display: inline-block;
            width: 48%;
            padding: 10px;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-save {
            background-color: #28a745;
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
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/testb.jpg" alt="Banner" class="banner">

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="stdmain.php">หน้าหลัก</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
    </div>

    <!-- Centered Text -->
    <div class="center-text">
        จัดการข้อมูลส่วนตัวนักศึกษา
    </div>

    <!-- Student Information Form -->
    <div class="form-container">
        <form action="editstd.php" method="post">
            <div class="form-group">
                <label>รูปภาพ</label>
                <img src="upload/<?php echo $row['s_pic']; ?>" alt="Student Picture">
            </div>

            <div class="form-group">
                <label>คำนำหน้าชื่อ</label>
                <input type="radio" name="s_pna" value="1" <?php echo $row['s_pna'] == 1 ? 'checked' : ''; ?>> นาย
                <input type="radio" name="s_pna" value="2" <?php echo $row['s_pna'] == 2 ? 'checked' : ''; ?>> นาง
                <input type="radio" name="s_pna" value="3" <?php echo $row['s_pna'] == 3 ? 'checked' : ''; ?>> นางสาว
            </div>

            <div class="form-group">
                <label>ชื่อ</label>
                <input type="text" name="s_na" value="<?php echo $row['s_na']; ?>">
            </div>

            <div class="form-group">
                <label>นามสกุล</label>
                <input type="text" name="s_la" value="<?php echo $row['s_la']; ?>">
            </div>

            <div class="form-group">
                <label>รหัสนักศึกษา</label>
                <input type="text" name="s_id" value="<?php echo $row['s_id']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>รหัสผ่าน</label>
                <input type="password" name="s_pws" value="<?php echo $row['s_pws']; ?>">
            </div>

            <div class="form-group">
                <label>สถานะ</label>
                <input type="radio" name="s_stat" value="1" <?php echo $row['s_stat'] == 1 ? 'checked' : ''; ?>> ยังคงศึกษาอยู่
                <input type="radio" name="s_stat" value="0" <?php echo $row['s_stat'] == 0 ? 'checked' : ''; ?>> จบการศึกษาแล้ว
            </div>

            <div class="form-group">
                <label>กรุ๊ปเลือด</label>
                <input type="radio" name="s_bloodtype" value="1" <?php echo $row['s_bloodtype'] == 1 ? 'checked' : ''; ?>> A
                <input type="radio" name="s_bloodtype" value="2" <?php echo $row['s_bloodtype'] == 2 ? 'checked' : ''; ?>> B
                <input type="radio" name="s_bloodtype" value="3" <?php echo $row['s_bloodtype'] == 3 ? 'checked' : ''; ?>> AB
                <input type="radio" name="s_bloodtype" value="4" <?php echo $row['s_bloodtype'] == 4 ? 'checked' : ''; ?>> O
            </div>

            <div class="form-group">
                <label>เชื้อชาติ</label>
                <input type="text" name="s_race" value="<?php echo $row['s_race']; ?>">
            </div>

            <div class="form-group">
                <label>วันเดือนปีเกิด</label>
                <input type="date" name="s_birth" value="<?php echo $row['s_birth']; ?>">
            </div>

            <div class="form-group">
                <label>สัญชาติ</label>
                <input type="text" name="s_nationlity" value="<?php echo $row['s_nationlity']; ?>">
            </div>

            <div class="form-group">
                <label>ศาสนา</label>
                <select name="religious">
                    <option value="1" <?php echo $row['religious'] == 1 ? 'selected' : ''; ?>>พุทธ</option>
                    <option value="2" <?php echo $row['religious'] == 2 ? 'selected' : ''; ?>>คริสต์</option>
                    <option value="3" <?php echo $row['religious'] == 3 ? 'selected' : ''; ?>>อิสลาม</option>
                    <option value="4" <?php echo $row['religious'] == 4 ? 'selected' : ''; ?>>พราหมณ์ฮินดู</option>
                    <option value="5" <?php echo $row['religious'] == 5 ? 'selected' : ''; ?>>อื่นๆ</option>
                </select>
            </div>

            <div class="form-group">
                <label>สถานภาพสมรส</label>
                <input type="radio" name="s_marriage" value="1" <?php echo $row['s_marriage'] == 1 ? 'checked' : ''; ?>> สมรสแล้ว
                <input type="radio" name="s_marriage" value="0" <?php echo $row['s_marriage'] == 0 ? 'checked' : ''; ?>> โสด
            </div>

            <div class="form-group">
                <label>จังหวัด</label>
                <input type="text" name="s_province" value="<?php echo $row['s_province']; ?>">
            </div>

            <div class="form-group">
                <label>ประเทศ</label>
                <input type="text" name="s_country" value="<?php echo $row['s_country']; ?>">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="edit_student2.php" class="btn-cancel">ยกเลิก</a>
            </div>
        </form>
    </div>

</body>
</html>
