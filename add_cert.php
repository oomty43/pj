<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

$s_id = $_SESSION['s_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ce_na = $_POST['ce_na'];
    $og_na = $_POST['og_na'];
    $ce_year = $_POST['ce_year'];
    $ce_file = $_FILES['ce_file'];

    // จัดการกับการอัพโหลดไฟล์
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($ce_file["name"]);
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
        if (move_uploaded_file($ce_file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO certi (ce_na, og_na, ce_year, ce_file, s_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $ce_na, $og_na, $ce_year, $target_file, $s_id);

            if ($stmt->execute()) {
                echo "เพิ่มข้อมูลใบรับรองเรียบร้อยแล้ว";
                header("Location: stdaward.php"); // เปลี่ยนเส้นทางไปยังหน้าหลักหลังจากเพิ่มข้อมูลสำเร็จ
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
    <title>เพิ่มข้อมูลใบรับรอง</title>
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
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>เพิ่มข้อมูลใบรับรอง</h2>
        <form action="add_cert.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ce_na">ชื่อใบรับรอง</label>
                <input type="text" id="ce_na" name="ce_na" required>
            </div>
            <div class="form-group">
                <label for="og_na">หน่วยงานที่รับรอง</label>
                <input type="text" id="og_na" name="og_na" required>
            </div>
            <div class="form-group">
                <label for="ce_year">ปีที่ได้รับ</label>
                <input type="number" id="ce_year" name="ce_year" required>
            </div>
            <div class="form-group">
                <label for="ce_file">เอกสารแนบ</label>
                <input type="file" id="ce_file" name="ce_file" accept=".pdf,.doc,.docx" required>
            </div>
            <button type="submit" class="btn-submit">บันทึก</button>
        </form>
    </div>
</body>
</html>
