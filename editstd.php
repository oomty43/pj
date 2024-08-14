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
$sql = "SELECT s_pna, s_na, s_la FROM student WHERE s_id = ?";
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
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>แก้ไขข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        label, input, select {
            margin: 10px 0;
            width: 48%;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>แก้ไขข้อมูลนักศึกษา</h2>
        <form method="post" action="">
            <label for="s_id">รหัสนักศึกษา:</label>
            <input type="text" id="s_id" name="s_id" value="<?php echo $s_id; ?>" disabled>
            
            <label for="s_pws">รหัสผ่าน:</label>
            <input type="password" id="s_pws" name="s_pws" value="<?php echo $s_pws; ?>" required>
            
            <label for="s_pna">คำนำหน้า:</label>
            <select id="s_pna" name="s_pna" required>
                <option value="1" <?php if ($s_pna == 1) echo "selected"; ?>>นาย</option>
                <option value="2" <?php if ($s_pna == 2) echo "selected"; ?>>นาง</option>
                <option value="3" <?php if ($s_pna == 3) echo "selected"; ?>>นางสาว</option>
            </select>
            
            <label for="s_na">ชื่อ:</label>
            <input type="text" id="s_na" name="s_na" value="<?php echo $s_na; ?>" required>
            
            <label for="s_la">นามสกุล:</label>
            <input type="text" id="s_la" name="s_la" value="<?php echo $s_la; ?>" required>
            
            <label for="s_email">อีเมล์:</label>
            <input type="email" id="s_email" name="s_email" value="<?php echo $s_email; ?>" required>
            
            <label for="s_address">ที่อยู่:</label>
            <input type="text" id="s_address" name="s_address" value="<?php echo $s_address; ?>" required>
            
            <label for="s_stat">สถานะนักศึกษา:</label>
            <input type="text" id="s_stat" name="s_stat" value="<?php echo $s_stat; ?>" required>
            
            <label for="s_bloodtype">กรุ๊ปเลือด:</label>
            <input type="text" id="s_bloodtype" name="s_bloodtype" value="<?php echo $s_bloodtype; ?>" required>
            
            <label for="s_race">เชื้อชาติ:</label>
            <input type="text" id="s_race" name="s_race" value="<?php echo $s_race; ?>" required>
            
            <label for="s_birth">วันเดือนปีเกิด:</label>
            <input type="text" id="s_birth" name="s_birth" value="<?php echo $s_birth; ?>" required>
            
            <label for="s_nationlity">สัญชาติ:</label>
            <input type="text" id="s_nationlity" name="s_nationlity" value="<?php echo $s_nationlity; ?>" required>
            
            <label for="religious">ศาสนา:</label>
            <input type="text" id="religious" name="religious" value="<?php echo $religious; ?>" required>
            
            <label for="s_marriage">สถานภาพสมรส:</label>
            <input type="text" id="s_marriage" name="s_marriage" value="<?php echo $s_marriage; ?>" required>
            
            <label for="s_province">จังหวัด:</label>
            <input type="text" id="s_province" name="s_province" value="<?php echo $s_province; ?>" required>
            
            <label for="s_country">ประเทศ:</label>
            <input type="text" id="s_country" name="s_country" value="<?php echo $s_country; ?>" required>
            
            <label for="s_gender">เพศ:</label>
            <input type="text" id="s_gender" name="s_gender" value="<?php echo $s_gender; ?>" required>
            
            <input type="submit" value="บันทึกการแก้ไข">
        </form>
    </div>
</body>
</html>
