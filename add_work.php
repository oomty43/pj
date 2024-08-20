<?php
session_start(); 

include 'std_con.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $w_na = $_POST['w_na'];
    $w_date = $_POST['w_date'];
    $s_id = $_SESSION['s_id'];

    // Insert the new work history into the database
    $sql = "INSERT INTO wk (w_na, w_date, s_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $w_na, $w_date, $s_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลการทำงานสำเร็จ!'); window.location.href='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล! กรุณาลองใหม่');</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มประวัติการทำงาน</title>
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
        .btn-submit {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-cancel {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>เพิ่มประวัติการทำงาน</h2>
        <form action="add_work.php" method="post">
            <div class="form-group">
                <label for="w_na">สถานที่ทำงาน</label>
                <input type="text" id="w_na" name="w_na" required>
            </div>
            <div class="form-group">
                <label for="w_date">ปีที่เริ่มทำงาน</label>
                <input type="date" id="w_date" name="w_date" required>
            </div>
            <button type="submit" class="btn-submit">บันทึก</button>
            <a href="stdaward.php" class="btn-cancel">ยกเลิก</a>
        </form>
    </div>

</body>
</html>
