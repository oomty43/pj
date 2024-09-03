<?php
session_start(); 

include 'std_con.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $e_na = $_POST['e_na'];
    $e_add = $_POST['e_add'];
    $e_date = $_POST['e_date'];
    $s_id = $_SESSION['s_id'];

    // Insert the new event into the database
    $sql = "INSERT INTO ev (e_na, e_add, e_date, s_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $e_na, $e_add, $e_date, $s_id);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลกิจกรรมสำเร็จ!'); window.location.href='stdaward.php';</script>";
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
    <title>เพิ่มกิจกรรม</title>
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
        <h2>เพิ่มกิจกรรม</h2>
        <form action="add_event.php" method="post">
            <div class="form-group">
                <label for="e_na">ชื่อกิจกรรม</label>
                <input type="text" id="e_na" name="e_na" required>
            </div>
            <div class="form-group">
                <label for="e_add">สถานที่จัดกิจกรรม</label>
                <input type="text" id="e_add" name="e_add" required>
            </div>
            <div class="form-group">
                <label for="e_date">ปีที่จัดกิจกรรม</label>
                <input type="date" id="e_date" name="e_date" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-submit">บันทึก</button>
                <a href="stdaward.php" class="btn-cancel">ยกเลิก</a>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>

</body>
</html>
