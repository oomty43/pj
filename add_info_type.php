<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// ตรวจสอบการส่งข้อมูล POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตัวแปรที่รับค่าจากฟอร์ม
    $itype_name = $_POST['itype_name'];


    // เชื่อมต่อกับฐานข้อมูล
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูล
    $sql = "INSERT INTO info_type (itype_name)
            VALUES ('$itype_name')";

    if ($conn->query($sql) === TRUE) {
        echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type=text], textarea, input[type=date], input[type=file] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เพิ่มข่าวสาร</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="itype_name">ประเภทข่าว:</label>
            <input type="text" id="itype_name" name="itype_name" required>

            <input type="submit" value="เพิ่มข้อมูล">
        </form>
    </div>
</body>
</html>
