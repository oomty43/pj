<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];
    $s_pna = $_POST['s_pna'];
    $s_na = $_POST['s_na'];
    $s_la = $_POST['s_la'];
    $s_email = $_POST['s_email'];
    $s_address = $_POST['s_address'];
    $s_stat = $_POST['s_stat'];
    $s_bloodtype = $_POST['s_bloodtype'];
    $s_race = $_POST['s_race'];
    $s_birth = $_POST['s_birth'];
    $s_nationlity = $_POST['s_nationlity'];
    $religious = $_POST['religious'];
    $s_marriage = $_POST['s_marriage'];
    $s_province = $_POST['s_province'];
    $s_country = $_POST['s_country'];
    $s_gender = $_POST['s_gender'];
    
    // อัปโหลดรูปภาพ
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["s_pic"]["name"]);
    move_uploaded_file($_FILES["s_pic"]["tmp_name"], $target_file);

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO student (s_id, s_pws, s_pna, s_na, s_la, s_email, s_address, s_stat, s_pic, s_bloodtype, s_race, s_birth, s_nationlity, religious, s_marriage, s_province, s_country, s_gender) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssss", $s_id, $s_pws, $s_pna, $s_na, $s_la, $s_email, $s_address, $s_stat, $target_file, $s_bloodtype, $s_race, $s_birth, $s_nationlity, $religious, $s_marriage, $s_province, $s_country, $s_gender);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลเรียบร้อยแล้ว'); window.location='display_student.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>เพิ่มข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #333333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            text-align: left;
            font-weight: bold;
        }
        input, select {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            width: 100%;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เพิ่มข้อมูลนักศึกษา</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="s_id">รหัสนักศึกษา:</label>
            <input type="text" id="s_id" name="s_id" required>
            
            <label for="s_pws">รหัสผ่าน:</label>
            <input type="password" id="s_pws" name="s_pws" required>
            
            <label for="s_pna">คำนำหน้า:</label>
            <select id="s_pna" name="s_pna" required>
                <option value="1">นาย</option>
                <option value="2">นาง</option>
                <option value="3">นางสาว</option>
            </select>
            
            <label for="s_na">ชื่อ:</label>
            <input type="text" id="s_na" name="s_na" required>
            
            <label for="s_la">นามสกุล:</label>
            <input type="text" id="s_la" name="s_la" required>
            
            <label for="s_email">อีเมล์:</label>
            <input type="email" id="s_email" name="s_email" required>
            
            <label for="s_address">ที่อยู่:</label>
            <input type="text" id="s_address" name="s_address" required>
            
            <label for="s_stat">สถานะนักศึกษา:</label>
            <select id="s_stat" name="s_stat" required>
                <option value="จบการศึกษา">จบการศึกษา</option>
                <option value="ยังไม่จบการศึกษา">ยังไม่จบการศึกษา</option>
            </select>
            
            <label for="s_pic">รูปภาพ:</label>
            <input type="file" id="s_pic" name="s_pic" required>
            
            <label for="s_bloodtype">กรุ๊ปเลือด:</label>
            <select id="s_bloodtype" name="s_bloodtype" required>
                <option value="A">A</option>
                <option value="AB">AB</option>
                <option value="B">B</option>
                <option value="O">O</option>
                <option value="อื่นๆ">อื่นๆ</option>
            </select>
            
            <label for="s_race">เชื้อชาติ:</label>
            <input type="text" id="s_race" name="s_race" required>
            
            <label for="s_birth">วันเกิด:</label>
            <input type="date" id="s_birth" name="s_birth" required>
            
            <label for="s_nationlity">สัญชาติ:</label>
            <input type="text" id="s_nationlity" name="s_nationlity" required>
            
            <label for="religious">ศาสนา:</label>
            <input type="text" id="religious" name="religious" required>
            
            <label for="s_marriage">สถานะภาพสมรส:</label>
            <input type="text" id="s_marriage" name="s_marriage" required>
            
            <label for="s_province">จังหวัด:</label>
            <select id="s_province" name="s_province" required>
            <option value="กรุงเทพมหานคร">เพชรบูรณ์</option>
                <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                <option value="สมุทรปราการ">สมุทรปราการ</option>
                <option value="นนทบุรี">นนทบุรี</option>
                <option value="ปทุมธานี">ปทุมธานี</option>
                <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
                <option value="อื่นๆ">อื่นๆ</option>
            </select>
            
            <label for="s_country">ประเทศ:</label>
            <input type="text" id="s_country" name="s_country" required>
            
            <label for="s_gender">เพศ:</label>
            <select id="s_gender" name="s_gender" required>
                <option value="ชาย">ชาย</option>
                <option value="หญิง">หญิง</option>
                <option value="อื่นๆ">อื่นๆ</option>
            </select>
            
            <input type="submit" value="เพิ่มข้อมูลนักศึกษา">
        </form>
    </div>
</body>
</html>