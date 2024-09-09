<?php
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
        echo "<script>alert('ขออภัย, อนุญาตเฉพาะไฟล์ PDF, DOC, DOCX เท่านั้น.');</script>";
        $uploadOk = 0;
    }

    // ตรวจสอบว่ามีข้อผิดพลาดในการอัพโหลดไฟล์หรือไม่
    if ($uploadOk == 0) {
        echo "<script>alert('ขออภัย, ไฟล์ของคุณไม่สามารถอัพโหลดได้.');</script>";
    } else {
        if (move_uploaded_file($ce_file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO certi (ce_na, og_na, ce_year, ce_file, s_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $ce_na, $og_na, $ce_year, $target_file, $s_id);

            if ($stmt->execute()) {
                echo "<script>alert('เพิ่มข้อมูลใบรับรองเรียบร้อยแล้ว'); window.location.href='stdaward.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('ขออภัย, มีปัญหาในการอัพโหลดไฟล์ของคุณ.');</script>";
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
        .form-group input[type="number"],
        .form-group input[type="file"] {
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
        .btn-submit {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px; /* ช่องว่างระหว่างปุ่ม */
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-cancel {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px; /* ช่องว่างระหว่างปุ่ม */
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .btn-back {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
        }
        .btn-back:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>เพิ่มข้อมูลใบรับรอง</h2>
        <form id="certForm" action="add_cert.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ce_na">ชื่อใบรับรอง:</label>
                <input type="text" id="ce_na" name="ce_na" required>
            </div>
            <div class="form-group">
                <label for="og_na">หน่วยงานที่รับรอง:</label>
                <input type="text" id="og_na" name="og_na" required>
            </div>
            <div class="form-group">
    <label for="ce_year">ปีที่ได้รับ:</label>
    <input type="text" id="ce_year" name="ce_year" maxlength="4" placeholder="ค.ศ." required>
</div>

            <div class="form-group">
                <label for="ce_file">เอกสารแนบ</label>
                <input type="file" id="ce_file" name="ce_file" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-submit">บันทึก</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('certForm').reset();">ยกเลิก</button>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>
