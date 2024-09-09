<?php
session_start();
include 'std_con.php';

// ดึงข้อมูลการฝึกงานที่ต้องการแก้ไขจากฐานข้อมูล
if (isset($_GET['its_id'])) {
    $its_id = $_GET['its_id'];

    $sql = "SELECT its_name, its_date, its_file, its_province FROM its_history WHERE its_id = ? AND s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $its_id, $_SESSION['s_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลการฝึกงาน";
        exit();
    }
} else {
    echo "ไม่มีข้อมูลการฝึกงานที่เลือก";
    exit();
}

// อัพเดทข้อมูลการฝึกงาน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $its_name = $_POST['its_name'];
    $its_date = $_POST['its_date'];
    $its_province = $_POST['its_province']; // รับค่าจังหวัดที่ฝึกงาน
    $its_file = $row['its_file']; // ค่าเดิมในกรณีไม่ได้เปลี่ยนไฟล์

    // เช็คว่ามีการอัพโหลดไฟล์ใหม่หรือไม่
    if (isset($_FILES['its_file']) && $_FILES['its_file']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";

        // ตรวจสอบว่าโฟลเดอร์อัพโหลดมีอยู่หรือไม่
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["its_file"]["name"]);
        if (move_uploaded_file($_FILES["its_file"]["tmp_name"], $target_file)) {
            $its_file = $target_file;
        } else {
            echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์";
        }
    }

    $sql = "UPDATE its_history SET its_name = ?, its_date = ?, its_province = ?, its_file = ? WHERE its_id = ? AND s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiii", $its_name, $its_date, $its_province, $its_file, $its_id, $_SESSION['s_id']);

    if ($stmt->execute()) {
        echo "<script>alert('บันทึกข้อมูลเรียบร้อยแล้ว'); window.location.href='stdaward.php';</script>";
        exit(); // ต้อง exit หลังจากการ redirect
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการฝึกงาน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        .form-group input[type="date"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-save {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px; /* ช่องว่างระหว่างปุ่ม */
        }
        .btn-cancel {
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px; /* ช่องว่างระหว่างปุ่ม */
        }
        .btn-back {
            padding: 10px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>แก้ไขข้อมูลการฝึกงาน</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="its_name">ชื่อที่ฝึกงาน:</label>
            <input type="text" name="its_name" value="<?php echo htmlspecialchars($row['its_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="its_date">ปีที่ฝึกงาน:</label>
            <input type="text" name="its_date" value="<?php echo htmlspecialchars($row['its_date']); ?>" maxlength="4" placeholder="ค.ศ." required>
        </div>

        <div class="form-group">
            <label for="its_province">จังหวัดที่ฝึกงาน:</label>
            <select id="its_province" name="its_province" required>
                <option value="">เลือกจังหวัด:</option>
                <?php
                // รายชื่อจังหวัดในประเทศไทย
                $provinces = [
                    "กรุงเทพมหานคร", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร", "ขอนแก่น",
                    "จันทบุรี", "ฉะเชิงเทรา", "ชลบุรี", "ชัยนาท", "ชัยภูมิ",
                    "ชุมพร", "ตรัง", "ตราด", "ตาก", "นครนายก",
                    "นครปฐม", "นครพนม", "นครราชสีมา", "นครศรีธรรมราช", "นนทบุรี",
                    "นราธิวาส", "น่าน", "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี",
                    "ประจวบคีรีขันธ์", "ปราจีนบุรี", "พะเยา", "พังงา", "พัทลุง",
                    "พระนครศรีอยุธยา", "ภูเก็ต", "มหาสารคาม", "มุกดาหาร", "ยะลา",
                    "ยโสธร", "ลำปาง", "ลำพูน", "เลย", "ศรีสะเกษ",
                    "สกลนคร", "สงขลา", "สตูล", "สมุทรปราการ", "สมุทรสงคราม",
                    "สมุทรสาคร", "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์",
                    "อ่างทอง", "อำนาจเจริญ", "อุดรธานี", "อุทัยธานี", "เชียงใหม่",
                    "เชียงราย", "ตราด", "ลำปาง", "ลำพูน", "หนองคาย"
                ];

                foreach ($provinces as $province) {
                    $selected = ($row['its_province'] == $province) ? 'selected' : '';
                    echo "<option value=\"$province\" $selected>$province</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="its_file">ไฟล์โปรเจคฝึกงาน:</label>
            <input type="file" name="its_file">
            <?php if ($row['its_file']) { ?>
                <p>ไฟล์ปัจจุบัน: <a href="<?php echo htmlspecialchars($row['its_file']); ?>" target="_blank">ดาวน์โหลด</a></p>
            <?php } ?>
        </div>
        <div class="button-group">
            <button type="submit" class="btn-save">บันทึก</button>
            <button type="button" class="btn-cancel" onclick="document.getElementById('its_name').value=''; document.getElementById('its_date').value=''; document.getElementById('its_province').selectedIndex=0;">ยกเลิก</button>
          <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>

        </div>
    </form>
</div>
</body>
</html>
