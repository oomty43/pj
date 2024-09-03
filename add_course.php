<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

// เมื่อฟอร์มถูกส่ง
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_na = $_POST['c_na'];
    $c_add = $_POST['c_add'];
    $c_date = $_POST['c_date'];
    $s_id = $_SESSION['s_id'];

    $sql = "INSERT INTO course (c_na, c_add, c_date, s_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $c_na, $c_add, $c_date, $s_id);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลสำเร็จ!'); window.location.href='stdaward.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลการอบรม</title>
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
        .form-group input[type="date"] {
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
        .button-group button {
            padding: 10px 20px;
            width: 30%;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .btn-save {
            background-color: #28a745;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-red {
            background-color: red;
        }
        .btn-red:hover {
            background-color: darkred;
        }
        .btn-blue {
            background-color: blue;
        }
        .btn-blue:hover {
            background-color: darkblue;
        }
    </style>
    <script>
        function resetForm() {
            if(confirm('คุณต้องการล้างฟอร์มหรือไม่?')) {
                document.getElementById("courseForm").reset(); // ล้างฟอร์ม
            }
        }

        function goBack() {
            window.history.back(); // กลับไปหน้าก่อนหน้า
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>เพิ่มข้อมูลการอบรม (Course)</h2>
        <form id="courseForm" method="POST" action="add_course.php">
            <div class="form-group">
                <label for="c_na">ชื่อโครงการอบรม</label>
                <input type="text" id="c_na" name="c_na" required>
            </div>
            <div class="form-group">
                <label for="c_add">ชื่อสถานที่อบรม</label>
                <input type="text" id="c_add" name="c_add" required>
            </div>
            <div class="form-group">
                <label for="c_date">วันที่อบรม</label>
                <input type="date" id="c_date" name="c_date" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">บันทึกข้อมูล</button>
                <button type="button" class="btn-red" onclick="resetForm()">ยกเลิก</button>
                <button type="button" class="btn-blue" onclick="goBack()">ย้อนกลับ</button>
            </div>
        </form>
    </div>
</body>
</html>
