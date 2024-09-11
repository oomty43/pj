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
$sql = "SELECT s_pic, s_pna, s_na, s_la, s_id, s_pws, s_email, s_stat, s_bloodtype, s_race, s_birth, s_nationlity, religious, s_marriage, s_province, s_country FROM student WHERE s_id = ?";
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
            background-color: #D35400;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .form-group img {
            max-width: 300px; /* ปรับขนาดความกว้างสูงสุดของรูปภาพ */
            max-height: 300px; /* ปรับขนาดความสูงสูงสุดของรูปภาพ */
            border-radius: 5px;
            margin-bottom: 15px;
}
        .nav-buttons a:hover {
            background-color: #E07B00;
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
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/banner1.jpg" alt="Banner" class="banner">

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="mainstd.php">หน้าหลัก</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
    </div>

    <!-- Centered Text -->
    <div class="center-text">
        แก้ไขข้อมูลนักศึกษา
    </div>

    <!-- Student Information Form -->
    <div class="form-container">
        <form action="editstd_process.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>รูปภาพ</label>
                <img src="uploads/<?php echo $row['s_pic']; ?>" alt="รูปภาพของนักเรียน">
                <input type="file" name="s_pic">
                <input type="hidden" name="s_pic_old" value="<?php echo $row['s_pic']; ?>">
            </div>

            <div class="form-group">
                <label>คำนำหน้าชื่อ</label>
                <input type="radio" name="s_pna" value="1" <?php echo $row['s_pna'] == 1 ? 'checked' : ''; ?>> นาย
                <input type="radio" name="s_pna" value="2" <?php echo $row['s_pna'] == 2 ? 'checked' : ''; ?>> นาง
                <input type="radio" name="s_pna" value="3" <?php echo $row['s_pna'] == 3 ? 'checked' : ''; ?>> นางสาว
                <input type="radio" name="s_pna" value="4" <?php echo $row['s_pna'] == 4 ? 'checked' : ''; ?>> ไม่ระบุเพศ
            </div>

            <div class="form-group">
                <label>ชื่อนักศึกษา</label>
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
                <input type="text" name="s_pws" value="<?php echo $row['s_pws']; ?>"> <!-- ใช้ type="text" แทนที่จะเป็น type="password" -->
            </div>

            <div class="form-group">
                <label>อีเมล์</label>
                <input type="text" name="s_email" value="<?php echo $row['s_email']; ?>">
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
    <select name="s_race">
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

        // แสดงรายการใน select พร้อมกับเลือกอัตโนมัติถ้าค่าตรงกับค่าที่มีอยู่แล้ว
        foreach ($races as $key => $race) {
            echo "<option value=\"$key\" " . ($row['s_race'] == $key ? 'selected' : '') . ">$race</option>";
        }
        ?>
    </select>
</div>


            <div class="form-group">
                <label>วันเกิด</label>
                <input type="date" name="s_birth" value="<?php echo $row['s_birth']; ?>">
            </div>

            <div class="form-group">
    <label>สัญชาติ</label>
    <select name="s_nationlity">
        <?php
        // รายการสัญชาติ
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

        // แสดงรายการใน select พร้อมกับเลือกอัตโนมัติถ้าค่าตรงกับค่าที่มีอยู่แล้ว
        foreach ($nationalities as $key => $nationality) {
            echo "<option value=\"$key\" " . ($row['s_nationlity'] == $key ? 'selected' : '') . ">$nationality</option>";
        }
        ?>
    </select>
</div>

            <div class="form-group">
                <label>ศาสนา</label>
                <input type="radio" name="religious" value="1" <?php echo $row['religious'] == 1 ? 'checked' : ''; ?>> พุทธ
                <input type="radio" name="religious" value="2" <?php echo $row['religious'] == 2 ? 'checked' : ''; ?>> คริสต์
                <input type="radio" name="religious" value="3" <?php echo $row['religious'] == 3 ? 'checked' : ''; ?>> อิสลาม
                <input type="radio" name="religious" value="4" <?php echo $row['religious'] == 4 ? 'checked' : ''; ?>> ฮินดู
                <input type="radio" name="religious" value="5" <?php echo $row['religious'] == 5 ? 'checked' : ''; ?>> อื่นๆ
            </div>

            <div class="form-group">
                <label>สถานภาพการสมรส</label>
                <input type="radio" name="s_marriage" value="1" <?php echo $row['s_marriage'] == 1 ? 'checked' : ''; ?>> โสด
                <input type="radio" name="s_marriage" value="2" <?php echo $row['s_marriage'] == 2 ? 'checked' : ''; ?>> สมรส
                <input type="radio" name="s_marriage" value="3" <?php echo $row['s_marriage'] == 3 ? 'checked' : ''; ?>> หย่า
                <input type="radio" name="s_marriage" value="4" <?php echo $row['s_marriage'] == 4 ? 'checked' : ''; ?>> แยกกันอยู่
            </div>

            <div class="form-group">
    <label>จังหวัด</label>
    <select name="s_province">
        <?php
        // รายการจังหวัดที่เรียงตามตัวอักษร ก ถึง ฮ
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

        // แสดงรายการใน select พร้อมกับเลือกอัตโนมัติถ้าค่าตรงกับค่าที่มีอยู่แล้ว
        foreach ($provinces as $key => $province) {
            echo "<option value=\"$key\" " . ($row['s_province'] == $key ? 'selected' : '') . ">$province</option>";
        }
        ?>
    </select>
</div>


            <div class="form-group">
                <label>ประเทศ</label>
                <input type="text" name="s_country" value="<?php echo $row['s_country']; ?>">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="stdprofile.php" class="btn-cancel">ยกเลิก</a>
            </div>
        </form>
    </div>

</body>
</html>
