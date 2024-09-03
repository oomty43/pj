<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

// ตรวจสอบว่ามีการส่งค่า sk_id มาหรือไม่
if (isset($_GET['sk_id'])) {
    $sk_id = $_GET['sk_id'];

    // ดึงข้อมูลทักษะพิเศษจากฐานข้อมูลตาม sk_id
    $sql = "SELECT sk_na FROM skill WHERE sk_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sk_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sk_na = $row['sk_na'];
    } else {
        echo "ไม่พบข้อมูลทักษะพิเศษ";
        exit();
    }
} else {
    echo "ไม่ได้รับค่าที่ต้องการ";
    exit();
}

// หากฟอร์มถูกส่ง (เมื่อคลิกปุ่มบันทึก)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sk_na = $_POST['sk_na'];

    // อัปเดตข้อมูลทักษะพิเศษในฐานข้อมูล
    $sql = "UPDATE skill SET sk_na = ? WHERE sk_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $sk_na, $sk_id);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location.href='stdaward.php';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขทักษะพิเศษ</title>
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
            background-color: darkred;
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
        <h2>แก้ไขทักษะพิเศษ</h2>
        <form method="POST">
            <div class="form-group">
                <label for="sk_na">ชื่อทักษะ:</label>
                <input type="text" id="sk_na" name="sk_na" value="<?php echo htmlspecialchars($sk_na); ?>" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="javascript:history.back()" class="btn-cancel">ยกเลิก</a>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>
