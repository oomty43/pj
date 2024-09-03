<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

// ตรวจสอบว่าผู้ใช้ได้ส่งฟอร์มแล้วหรือยัง
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sk_na = $_POST['sk_na'];
    $s_id = $_SESSION['s_id'];

    // ตรวจสอบว่าข้อมูลถูกกรอกมาครบถ้วนหรือไม่
    if (!empty($sk_na)) {
        $sql = "INSERT INTO skill (sk_na, s_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $sk_na, $s_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('เพิ่มข้อมูลทักษะสำเร็จ'); window.location.href='stdaward.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูลทักษะ');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลทักษะพิเศษ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"] {
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
                document.getElementById("skillForm").reset(); // ล้างฟอร์ม
            }
        }

        function goBack() {
            window.history.back(); // กลับไปหน้าก่อนหน้า
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>เพิ่มข้อมูลทักษะพิเศษ</h2>
    <form id="skillForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="sk_na">ชื่อทักษะพิเศษ:</label>
            <input type="text" name="sk_na" id="sk_na" required>
        </div>
        <div class="button-group">
            <button type="submit" class="btn-save">บันทึก</button>
            <button type="button" class="btn-red" onclick="resetForm()">ยกเลิก</button>
            <button type="button" class="btn-blue" onclick="goBack()">ย้อนกลับ</button>
        </div>
    </form>
</div>

</body>
</html>
