<?php
session_start();
include 'std_con.php';

// ดึงข้อมูลการฝึกงานที่ต้องการแก้ไขจากฐานข้อมูล
if (isset($_GET['its_id'])) {
    $its_id = $_GET['its_id'];

    $sql = "SELECT its_name, its_date, its_file FROM its_history WHERE its_id = ? AND s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $its_id, $_SESSION['s_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลการฝึกงาน";
        exit();
    }
} else {
    echo "ไม่มีข้อมูลการฝึกงานที่เลือก";
    exit();
}

// อัพเดทข้อมูลการฝึกงาน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $its_name = $_POST['its_name'];
    $its_date = $_POST['its_date'];
    $its_file = $row['its_file']; // ค่าเดิมในกรณีไม่ได้เปลี่ยนไฟล์

    // เช็คว่ามีการอัพโหลดไฟล์ใหม่หรือไม่
    if (isset($_FILES['its_file']) && $_FILES['its_file']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["its_file"]["name"]);
        if (move_uploaded_file($_FILES["its_file"]["tmp_name"], $target_file)) {
            $its_file = $target_file;
        } else {
            echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์";
        }
    }

    $sql = "UPDATE its_history SET its_name = ?, its_date = ?, its_file = ? WHERE its_id = ? AND s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $its_name, $its_date, $its_file, $its_id, $_SESSION['s_id']);

    if ($stmt->execute()) {
        echo "แก้ไขข้อมูลการฝึกงานสำเร็จ";
        header("Location: stdaward.php");
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการฝึกงาน</title>
</head>
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
    <h2>แก้ไขข้อมูลการฝึกงาน</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="its_name">ชื่อที่ฝึกงาน:</label>
            <input type="text" name="its_name" value="<?php echo $row['its_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="its_date">ระยะเวลาฝึกงาน:</label>
            <input type="date" name="its_date" value="<?php echo $row['its_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="its_file">ไฟล์โปรเจคฝึกงาน:</label>
            <input type="file" name="its_file">
            <?php if ($row['its_file']) { ?>
                <p>ไฟล์ปัจจุบัน: <a href="<?php echo $row['its_file']; ?>">ดาวน์โหลด</a></p>
            <?php } ?>
        </div>
        <button type="submit">บันทึก</button>
    </form>
</body>
</html>
