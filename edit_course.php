<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

// ตรวจสอบว่ามี c_id ถูกส่งมาหรือไม่
if (isset($_GET['c_id'])) {
    $c_id = $_GET['c_id'];

    // ดึงข้อมูลจากฐานข้อมูลเพื่อแสดงในแบบฟอร์ม
    $sql = "SELECT c_na, c_add, c_date FROM course WHERE c_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $c_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $c_na = $row['c_na'];
        $c_add = $row['c_add'];
        $c_date = $row['c_date'];
    } else {
        echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
        exit();
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
    exit();
}

// อัพเดตข้อมูลเมื่อส่งแบบฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $c_na = $_POST['c_na'];
    $c_add = $_POST['c_add'];
    $c_date = $_POST['c_date'];

    $sql = "UPDATE course SET c_na = ?, c_add = ?, c_date = ? WHERE c_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $c_na, $c_add, $c_date, $c_id);

    if ($stmt->execute()) {
        echo "แก้ไขข้อมูลเรียบร้อยแล้ว";
        header("Location: stdaward.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการอบรม</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .form-container {
            width: 50%;
            margin: 0 auto;
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
        .btn-save {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-cancel {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>แก้ไขข้อมูลการอบรม</h2>
        <form method="POST">
            <div class="form-group">
                <label for="c_na">ชื่อโครงการอบรม</label>
                <input type="text" id="c_na" name="c_na" value="<?php echo $c_na; ?>" required>
            </div>
            <div class="form-group">
                <label for="c_add">ชื่อสถานที่อบรม</label>
                <input type="text" id="c_add" name="c_add" value="<?php echo $c_add; ?>" required>
            </div>
            <div class="form-group">
                <label for="c_date">วันที่อบรม</label>
                <input type="date" id="c_date" name="c_date" value="<?php echo $c_date; ?>" required>
            </div>
            <button type="submit" class="btn-save">บันทึก</button>
            <a href="stdaward.php" class="btn-cancel">ยกเลิก</a>
        </form>
    </div>

</body>
</html>
