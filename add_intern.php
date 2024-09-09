<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

$s_id = $_SESSION['s_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $its_name = $_POST['its_name'];
    $its_date = $_POST['its_date'];
    $its_province = $_POST['its_province']; // รับค่าจังหวัดที่ฝึกงาน
    $its_file = $_FILES['its_file'];

    // จัดการกับการอัพโหลดไฟล์
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($its_file["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ตรวจสอบประเภทของไฟล์
    if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        echo "ขออภัย, อนุญาตเฉพาะไฟล์ PDF, DOC, DOCX เท่านั้น.";
        $uploadOk = 0;
    }

    // ตรวจสอบว่ามีข้อผิดพลาดในการอัพโหลดไฟล์หรือไม่
    if ($uploadOk == 0) {
        echo "ขออภัย, ไฟล์ของคุณไม่สามารถอัพโหลดได้.";
    } else {
        if (move_uploaded_file($its_file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO its_history (its_name, its_date, its_province, its_file, s_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $its_name, $its_date, $its_province, $target_file, $s_id);

            if ($stmt->execute()) {
                echo "<script>alert('เพิ่มข้อมูลฝึกงานเรียบร้อยแล้ว'); window.location.href='stdaward.php';</script>";
                exit;
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
            }
        } else {
            echo "ขออภัย, มีปัญหาในการอัพโหลดไฟล์ของคุณ.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลการฝึกงาน</title>
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
        .form-group input[type="file"],
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
        .button-group button,
        .button-group a {
            padding: 10px 20px;
            width: 30%;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-save {
            background-color: #28a745;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .btn-back {
            background-color: blue;
        }
        .btn-back:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>เพิ่มข้อมูลการฝึกงาน</h2>
        <form id="internForm" action="add_intern.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="its_name">ชื่อที่ฝึกงาน:</label>
                <input type="text" id="its_name" name="its_name" required>
            </div>
            <div class="form-group">
                <label for="its_date">ปีที่ฝึกงาน:</label>
                <input type="text" id="its_date" name="its_date" placeholder="ค.ศ." pattern="\d{4}" maxlength="4" minlength="4" required>
            </div>
            <div class="form-group">
                <label for="its_province">จังหวัดที่ฝึกงาน:</label>
                <select id="its_province" name="its_province" required>
                    <option value="">เลือกจังหวัด</option>
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
                    ];

                    foreach ($provinces as $province) {
                        echo "<option value=\"$province\">$province</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="its_file">อัพโหลดไฟล์:</label>
                <input type="file" id="its_file" name="its_file" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('internForm').reset();">ยกเลิก</button>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>

</body>
</html>
