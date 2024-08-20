<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

$s_id = $_SESSION['s_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $its_name = $_POST['its_name'];
    $its_date = $_POST['its_date'];
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
            $sql = "INSERT INTO its_history (its_name, its_date, its_file, s_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $its_name, $its_date, $target_file, $s_id);


            if ($stmt->execute()) {
                echo "เพิ่มข้อมูลฝึกงานเรียบร้อยแล้ว";
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
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>เพิ่มข้อมูลการฝึกงาน</h2>
        <form action="add_intern.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="its_name">ชื่อที่ฝึกงาน:</label>
                <input type="text" id="its_name" name="its_name" required>
            </div>
            <div class="form-group">
                <label for="its_date">ปีที่ฝึกงาน:</label>
                <input type="number" id="its_date" name="its_date" required>
            </div>
            <div class="form-group">
                <label for="its_file">อัพโหลดไฟล์:</label>
                <input type="file" id="its_file" name="its_file" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="stdaward.php" class="btn-cancel">ยกเลิก</a>
            </div>
        </form>
    </div>

</body>
</html>