<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// รับรหัสนักศึกษาที่ต้องการแก้ไขจาก URL parameter
if (isset($_GET['s_id'])) {
    $s_id = $_GET['s_id'];
    
    // ค้นหาข้อมูลนักศึกษาจากฐานข้อมูล
    $sql = "SELECT * FROM student WHERE s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $s_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $s_pws = $row['s_pws'];
        $s_pna = $row['s_pna'];
        $s_na = $row['s_na'];
        $s_la = $row['s_la'];
        $s_email = $row['s_email'];
        $s_address = $row['s_address'];
        $s_stat = $row['s_stat'];
        $s_bloodtype = $row['s_bloodtype'];
        $s_race = $row['s_race'];
        $s_birth = $row['s_birth'];
        $s_nationlity = $row['s_nationlity'];
        $religious = $row['religious'];
        $s_marriage = $row['s_marriage'];
        $s_province = $row['s_province'];
        $s_country = $row['s_country'];
        $s_gender = $row['s_gender'];
        $s_pic = $row['s_pic']; // เก็บชื่อไฟล์รูปภาพเดิมไว้
    } else {
        echo "ไม่พบข้อมูลนักศึกษา";
        exit();
    }
    
    $stmt->close();
} else {
    echo "ไม่พบรหัสนักศึกษาที่ต้องการแก้ไข";
    exit();
}

// กระบวนการบันทึกการแก้ไข
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    // อัพโหลดรูปภาพใหม่ถ้ามี
    if (!empty($_FILES['s_pic']['name'])) {
        $s_pic = $_FILES['s_pic']['name']; // รับชื่อไฟล์ที่อัพโหลดใหม่
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($s_pic);
        move_uploaded_file($_FILES['s_pic']['tmp_name'], $target_file);
    } else {
        // ถ้าไม่มีการอัพโหลดรูปใหม่ ให้ใช้รูปเดิม
        $s_pic = $_POST['s_pic_old'];
    }

    $sql_update = "UPDATE student SET s_pws=?, s_pic=?, s_pna=?, s_na=?, s_la=?, s_email=?, s_address=?, s_stat=?, s_bloodtype=?, s_race=?, s_birth=?, s_nationlity=?, religious=?, s_marriage=?, s_province=?, s_country=?, s_gender=? WHERE s_id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssssssssssssssi", $s_pws, $s_pic, $s_pna, $s_na, $s_la, $s_email, $s_address, $s_stat, $s_bloodtype, $s_race, $s_birth, $s_nationlity, $religious, $s_marriage, $s_province, $s_country, $s_gender, $s_id);
    
    if ($stmt_update->execute()) {
        // บันทึกการกระทำใน admin_logs
        $admin_user = $_SESSION['a_user']; // รับข้อมูล a_user จาก session
        $action_type = 'แก้ไข'; // ประเภทการกระทำ
        $student_id = $s_id; // รหัสนักศึกษาที่ถูกแก้ไข

        $sql_log = "INSERT INTO admin_logs (a_id, action_type, student_id) VALUES (?, ?, ?)";
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bind_param("sss", $admin_user, $action_type, $student_id);
        $stmt_log->execute();
        $stmt_log->close();

        echo "<script>alert('อัปเดตข้อมูลเรียบร้อยแล้ว'); window.location='display_student.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt_update->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลนักศึกษา</title>
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
        img {
            max-width: 200px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>แก้ไขข้อมูลนักศึกษา</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <!-- แก้ไขรูปภาพด้านบนสุด -->
            <label for="s_pic">รูปภาพ:</label>
            <img src="uploads/<?php echo $s_pic; ?>" alt="รูปภาพนักศึกษา">
            <input type="file" id="s_pic" name="s_pic">
            <input type="hidden" name="s_pic_old" value="<?php echo $s_pic; ?>"> <!-- เก็บชื่อรูปภาพเดิม -->

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
            <select id="s_stat" name="s_stat" required>
                <option value="1" <?php if ($s_stat == 1) echo "selected"; ?>>ยังคงศึกษาอยู่</option>
                <option value="0" <?php if ($s_stat == 0) echo "selected"; ?>>จบการศึกษาแล้ว</option>
            </select>
            
            <label for="s_bloodtype">กรุ๊ปเลือด:</label>
            <select id="s_bloodtype" name="s_bloodtype" required>
                <option value="1" <?php if ($s_bloodtype == 1) echo "selected"; ?>>A</option>
                <option value="2" <?php if ($s_bloodtype == 2) echo "selected"; ?>>B</option>
                <option value="3" <?php if ($s_bloodtype == 3) echo "selected"; ?>>AB</option>
                <option value="4" <?php if ($s_bloodtype == 4) echo "selected"; ?>>O</option>
            </select>
            
            <label for="s_race">เชื้อชาติ:</label>
            <select id="s_race" name="s_race" required>
                <?php
                // รายการเชื้อชาติ
                $races = [
                    1 => "ไทย",
                    2 => "ลาว",
                    3 => "เมียนมา",
                    4 => "เวียดนาม",
                    5 => "กำพูชา",
                    6 => "มาเลเซีย",
                    7 => "อินโดนิเซีย",
                    8 => "จีน",
                    9 => "สหรัฐอเมริกา",
                    10 => "อังกฤษ"
                ];
                
                foreach ($races as $key => $race) {
                    echo "<option value=\"$key\" " . ($s_race == $key ? "selected" : "") . ">$race</option>";
                }
                ?>
            </select>
            
            <label for="s_birth">วันเกิด:</label>
            <input type="date" id="s_birth" name="s_birth" value="<?php echo $s_birth; ?>" required>
            
            <label for="s_nationlity">สัญชาติ:</label>
            <select id="s_nationlity" name="s_nationlity" required>
                <?php
                $nationalities = [
                    1 => "ไทย",
                    2 => "ลาว",
                    3 => "เมียนมา",
                    4 => "เวียดนาม",
                    5 => "กำพูชา",
                    6 => "มาเลเซีย",
                    7 => "อินโดนิเซีย",
                    8 => "จีน",
                    9 => "สหรัฐอเมริกา",
                    10 => "อังกฤษ"
                ];
                
                foreach ($nationalities as $key => $nationality) {
                    echo "<option value=\"$key\" " . ($s_nationlity == $key ? "selected" : "") . ">$nationality</option>";
                }
                ?>
            </select>
            
            <label for="religious">ศาสนา:</label>
            <select id="religious" name="religious" required>
                <option value="1" <?php if ($religious == 1) echo "selected"; ?>>พุทธ</option>
                <option value="2" <?php if ($religious == 2) echo "selected"; ?>>คริสต์</option>
                <option value="3" <?php if ($religious == 3) echo "selected"; ?>>อิสลาม</option>
                <option value="4" <?php if ($religious == 4) echo "selected"; ?>>ฮินดู</option>
                <option value="5" <?php if ($religious == 5) echo "selected"; ?>>อื่นๆ</option>
            </select>
            
            <label for="s_marriage">สถานภาพการสมรส:</label>
            <select id="s_marriage" name="s_marriage" required>
                <option value="1" <?php if ($s_marriage == 1) echo "selected"; ?>>โสด</option>
                <option value="2" <?php if ($s_marriage == 2) echo "selected"; ?>>สมรส</option>
                <option value="3" <?php if ($s_marriage == 3) echo "selected"; ?>>หย่า</option>
                <option value="4" <?php if ($s_marriage == 4) echo "selected"; ?>>แยกกันอยู่</option>
            </select>
            
            <label for="s_province">จังหวัด:</label>
            <select id="s_province" name="s_province" required>
                <?php
                $provinces = [
                    1 => "กรุงเทพมหานคร",
                    2 => "กระบี่",
                    3 => "กาญจนบุรี",
                    4 => "กาฬสินธุ์",
                    5 => "กำแพงเพชร",
                    6 => "ขอนแก่น",
                    7 => "จันทบุรี",
                    8 => "ฉะเชิงเทรา",
                    9 => "ชลบุรี",
                    10 => "ชัยนาท",
                    11 => "ชัยภูมิ",
                    12 => "ชุมพร",
                    13 => "เชียงใหม่",
                    14 => "เชียงราย",
                    15 => "ตรัง",
                    16 => "ตราด",
                    17 => "ตาก",
                    18 => "นครนายก",
                    19 => "นครปฐม",
                    20 => "นครพนม",
                    21 => "นครราชสีมา",
                    22 => "นครศรีธรรมราช",
                    23 => "นครสวรรค์",
                    24 => "นนทบุรี",
                    25 => "นราธิวาส",
                    26 => "น่าน",
                    27 => "บึงกาฬ",
                    28 => "บุรีรัมย์",
                    29 => "ปทุมธานี",
                    30 => "ประจวบคีรีขันธ์",
                    31 => "ปราจีนบุรี",
                    32 => "ปัตตานี",
                    33 => "พระนครศรีอยุธยา",
                    34 => "พังงา",
                    35 => "พัทลุง",
                    36 => "พิจิตร",
                    37 => "พิษณุโลก",
                    38 => "เพชรบุรี",
                    39 => "เพชรบูรณ์",
                    40 => "แพร่",
                    41 => "พะเยา",
                    42 => "ภูเก็ต",
                    43 => "มหาสารคาม",
                    44 => "มุกดาหาร",
                    45 => "แม่ฮ่องสอน",
                    46 => "ยโสธร",
                    47 => "ยะลา",
                    48 => "ร้อยเอ็ด",
                    49 => "ระนอง",
                    50 => "ระยอง",
                    51 => "ราชบุรี",
                    52 => "ลพบุรี",
                    53 => "ลำปาง",
                    54 => "ลำพูน",
                    55 => "เลย",
                    56 => "ศรีสะเกษ",
                    57 => "สกลนคร",
                    58 => "สงขลา",
                    59 => "สตูล",
                    60 => "สมุทรปราการ",
                    61 => "สมุทรสงคราม",
                    62 => "สมุทรสาคร",
                    63 => "สระแก้ว",
                    64 => "สระบุรี",
                    65 => "สิงห์บุรี",
                    66 => "สุโขทัย",
                    67 => "สุพรรณบุรี",
                    68 => "สุราษฎร์ธานี",
                    69 => "สุรินทร์",
                    70 => "หนองคาย",
                    71 => "หนองบัวลำภู",
                    72 => "อ่างทอง",
                    73 => "อุดรธานี",
                    74 => "อุทัยธานี",
                    75 => "อุตรดิตถ์",
                    76 => "อุบลราชธานี",
                    77 => "อำนาจเจริญ"
                ];
                
                foreach ($provinces as $key => $province) {
                    echo "<option value=\"$key\" " . ($s_province == $key ? "selected" : "") . ">$province</option>";
                }
                ?>
            </select>
            
            <label for="s_country">ประเทศ:</label>
            <input type="text" id="s_country" name="s_country" value="<?php echo $s_country; ?>" required>

            <div class="form-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="display_student.php" class="btn-cancel">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>
