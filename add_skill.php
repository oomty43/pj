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
            echo "<script>alert('เพิ่มข้อมูลทักษะสำเร็จ'); window.location.href='stdprofile.php';</script>";
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
        .btn-save {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-save:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>เพิ่มข้อมูลทักษะพิเศษ</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="sk_na">ชื่อทักษะพิเศษ:</label>
            <input type="text" name="sk_na" id="sk_na" required>
        </div>
        <button type="submit" class="btn-save">บันทึก</button>
    </form>
</div>

</body>
</html>
