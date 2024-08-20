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
        echo "แก้ไขข้อมูลสำเร็จ!";
        header("Location: mainstd.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล";
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
        .btn-save {
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
            cursor: pointer;
        }
        .btn-cancel {
            display: inline-block;
            width: 48%;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
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
            <button type="submit" class="btn-save">บันทึก</button>
            <a href="mainstd.php" class="btn-cancel">ยกเลิก</a>
        </form>
    </div>
</body>
</html>
