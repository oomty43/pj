<?php
session_start(); 

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];
    $s_pna = $_POST['s_pna'];
    $s_na = $_POST['s_na'];
    $s_la = $_POST['s_la'];
    $s_email = $_POST['s_email'];
    $s_address = $_POST['s_address'];
    $s_stat = $_POST['s_stat'];
    $s_pic = $_FILES['s_pic']['name']; // รับชื่อไฟล์ที่อัพโหลด
    $s_bloodtype = $_POST['s_bloodtype'];
    $s_race = $_POST['s_race'];
    $s_birth = $_POST['s_birth'];
    $s_nationlity = $_POST['s_nationlity'];
    $religious = $_POST['religious'];
    $s_marriage = $_POST['s_marriage'];
    $s_province = $_POST['s_province'];
    $s_country = $_POST['s_country'];

    // ตรวจสอบและอัพโหลดไฟล์ภาพ
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($s_pic);
    move_uploaded_file($_FILES['s_pic']['tmp_name'], $target_file);

    // เตรียมคำสั่ง SQL
    $sql = "INSERT INTO student (s_id, s_pws, s_pna, s_na, s_la, s_email, s_address, s_stat, s_pic, s_bloodtype, s_race, s_birth, s_nationlity, religious, s_marriage, s_province, s_country)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // เตรียมคำสั่งสำหรับการดำเนินการ
    $stmt = $conn->prepare($sql);

    // ผูกค่าที่จะใช้กับคำสั่ง SQL
    $stmt->bind_param("sssssssssssssssss", $s_id, $s_pws, $s_pna, $s_na, $s_la, $s_email, $s_address, $s_stat, $s_pic, $s_bloodtype, $s_race, $s_birth, $s_nationlity, $religious, $s_marriage, $s_province, $s_country);

    // ดำเนินการคำสั่ง
    if ($stmt->execute()) {
        echo "เพิ่มข้อมูลสำเร็จ!";
        header("Location: display_student.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อคำสั่ง
    $stmt->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังเข้ม */
            color: #fff; /* สีตัวอักษร */
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #ffa500; /* สีหัวข้อ */
            margin-bottom: 20px;
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
            margin-bottom: 15px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            width: 100%;
            background-color: #444; /* สีพื้นหลังของ input */
            color: #fff; /* สีตัวอักษรใน input */
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        button, a.btn-cancel {
            display: inline-block;
            width: 48%; /* ทำให้ปุ่มทั้งสองมีขนาดกว้าง 48% เพื่อให้สามารถวางคู่กันได้ */
            padding: 15px;
            font-size: 18px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        button.btn-save {
            background-color: #4CAF50; /* สีเขียว */
            color: white;
        }
        button.btn-save:hover {
            background-color: #45a049; /* สีเขียวเข้มเมื่อ hover */
        }
        a.btn-cancel {
            background-color: #2196F3; /* สีน้ำเงิน */
            color: white;
        }
        a.btn-cancel:hover {
            background-color: #c82333; /* สีแดงเข้มเมื่อ hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เพิ่มข้อมูลนักศึกษา</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <!-- รายการฟิลด์ต่างๆ -->
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
                <option value="1">ยังคงศึกษาอยู่</option>
                <option value="2">จบการศึกษาแล้ว</option>
            </select>
            
            <label for="s_pic">รูปภาพ:</label>
            <input type="file" id="s_pic" name="s_pic" required>
            
            <label for="s_bloodtype">กรุ๊ปเลือด:</label>
            <select id="s_bloodtype" name="s_bloodtype" required>
                <option value="1">A</option>
                <option value="2">B</option>
                <option value="3">AB</option>
                <option value="4">O</option>
            </select>
            
            <label for="s_race">เชื้อชาติ:</label>
            <select id="s_race" name="s_race" required>
                <option value="1">ไทย</option>
                <option value="2">ลาว</option>
                <option value="3">เมียนมา</option>
                <option value="4">เวียดนาม</option>
                <option value="5">กำพูชา</option>
                <option value="6">มาเลเซีย</option>
                <option value="7">อินโดนิเซีย</option>
                <option value="8">จีน</option>
                <option value="9">สหรัฐอเมริกา</option>
                <option value="10">อังกฤษ</option>
            </select>
            
            <label for="s_birth">วันเกิด:</label>
            <input type="date" id="s_birth" name="s_birth" required>
            
            <label for="s_nationlity">สัญชาติ:</label>
            <select id="s_nationlity" name="s_nationlity" required>
                <option value="1">ไทย</option>
                <option value="2">ลาว</option>
                <option value="3">เมียนมา</option>
                <option value="4">เวียดนาม</option>
                <option value="5">กำพูชา</option>
                <option value="6">มาเลเซีย</option>
                <option value="7">อินโดนิเซีย</option>
                <option value="8">จีน</option>
                <option value="9">สหรัฐอเมริกา</option>
                <option value="10">อังกฤษ</option>
            </select>
            
            <label for="religious">ศาสนา:</label>
            <select id="religious" name="religious" required>
                <option value="1">พุทธ</option>
                <option value="2">คริสต์</option>
                <option value="3">อิสลาม</option>
                <option value="4">ฮินดู</option>
                <option value="5">อื่นๆ</option>
            </select>
            
            <label for="s_marriage">สถานภาพการสมรส:</label>
            <select id="s_marriage" name="s_marriage" required>
                <option value="1">โสด</option>
                <option value="2">สมรส</option>
                <option value="3">หย่า</option>
                <option value="4">แยกกันอยู่</option>
            </select>
            <label for="s_province">จังหวัด:</label>
            <select id="s_province" name="s_province" required>
                <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                <option value="กระบี่">กระบี่</option>
                <option value="กาญจนบุรี">กาญจนบุรี</option>
                <option value="กาฬสินธุ์">กาฬสินธุ์</option>
                <option value="กำแพงเพชร">กำแพงเพชร</option>
                <option value="ขอนแก่น">ขอนแก่น</option>
                <option value="จันทบุรี">จันทบุรี</option>
                <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
                <option value="ชลบุรี">ชลบุรี</option>
                <option value="ชัยนาท">ชัยนาท</option>
                <option value="ชัยภูมิ">ชัยภูมิ</option>
                <option value="ชุมพร">ชุมพร</option>
                <option value="เชียงใหม่">เชียงใหม่</option>
                <option value="เชียงราย">เชียงราย</option>
                <option value="ตรัง">ตรัง</option>
                <option value="ตราด">ตราด</option>
                <option value="ตาก">ตาก</option>
                <option value="นครนายก">นครนายก</option>
                <option value="นครปฐม">นครปฐม</option>
                <option value="นครพนม">นครพนม</option>
                <option value="นครราชสีมา">นครราชสีมา</option>
                <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
                <option value="นครสวรรค์">นครสวรรค์</option>
                <option value="นนทบุรี">นนทบุรี</option>
                <option value="นราธิวาส">นราธิวาส</option>
                <option value="น่าน">น่าน</option>
                <option value="บึงกาฬ">บึงกาฬ</option>
                <option value="บุรีรัมย์">บุรีรัมย์</option>
                <option value="ปทุมธานี">ปทุมธานี</option>
                <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
                <option value="ปราจีนบุรี">ปราจีนบุรี</option>
                <option value="ปัตตานี">ปัตตานี</option>
                <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
                <option value="พังงา">พังงา</option>
                <option value="พัทลุง">พัทลุง</option>
                <option value="พิจิตร">พิจิตร</option>
                <option value="พิษณุโลก">พิษณุโลก</option>
                <option value="เพชรบุรี">เพชรบุรี</option>
                <option value="เพชรบูรณ์">เพชรบูรณ์</option>
                <option value="แพร่">แพร่</option>
                <option value="พะเยา">พะเยา</option>
                <option value="ภูเก็ต">ภูเก็ต</option>
                <option value="มหาสารคาม">มหาสารคาม</option>
                <option value="มุกดาหาร">มุกดาหาร</option>
                <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
                <option value="ยโสธร">ยโสธร</option>
                <option value="ยะลา">ยะลา</option>
                <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
                <option value="ระนอง">ระนอง</option>
                <option value="ระยอง">ระยอง</option>
                <option value="ราชบุรี">ราชบุรี</option>
                <option value="ลพบุรี">ลพบุรี</option>
                <option value="ลำปาง">ลำปาง</option>
                <option value="ลำพูน">ลำพูน</option>
                <option value="เลย">เลย</option>
                <option value="ศรีสะเกษ">ศรีสะเกษ</option>
                <option value="สกลนคร">สกลนคร</option>
                <option value="สงขลา">สงขลา</option>
                <option value="สตูล">สตูล</option>
                <option value="สมุทรปราการ">สมุทรปราการ</option>
                <option value="สมุทรสงคราม">สมุทรสงคราม</option>
                <option value="สมุทรสาคร">สมุทรสาคร</option>
                <option value="สระแก้ว">สระแก้ว</option>
                <option value="สระบุรี">สระบุรี</option>
                <option value="สิงห์บุรี">สิงห์บุรี</option>
                <option value="สุโขทัย">สุโขทัย</option>
                <option value="สุพรรณบุรี">สุพรรณบุรี</option>
                <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
                <option value="สุรินทร์">สุรินทร์</option>
                <option value="หนองคาย">หนองคาย</option>
                <option value="หนองบัวลำภู">หนองบัวลำภู</option>
                <option value="อ่างทอง">อ่างทอง</option>
                <option value="อุดรธานี">อุดรธานี</option>
                <option value="อุทัยธานี">อุทัยธานี</option>
                <option value="อุตรดิตถ์">อุตรดิตถ์</option>
                <option value="อุบลราชธานี">อุบลราชธานี</option>
                <option value="อำนาจเจริญ">อำนาจเจริญ</option>
                </select>
            
            <div class="form-group">
                <label for="s_country">ประเทศ:</label>
                <input type="text" id="s_country" name="s_country" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="display_student.php" class="btn-cancel">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>