<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// ตรวจสอบการส่งข้อมูล POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตัวแปรที่รับค่าจากฟอร์ม
    $itype_name = $_POST['itype_name'];

    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูล
    $sql = "INSERT INTO info_type (itype_name) VALUES ('$itype_name')";

    if ($conn->query($sql) === TRUE) {
        echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มประเภทข่าวสาร</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* สีพื้นหลังที่เข้ม */
            color: #f0f0f0; /* สีตัวอักษร */
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            background-color: #333; /* พื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type=text], textarea, input[type=date], input[type=file] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #444; /* ขอบสีเทาเข้ม */
            border-radius: 4px;
            background-color: #1e1e1e; /* พื้นหลังกล่องข้อความ */
            color: #f0f0f0; /* สีตัวอักษรในกล่องข้อความ */
        }
        .button-container {
            display: flex;
            justify-content: center; /* จัดให้อยู่ตรงกลาง */
            gap: 20px; /* ระยะห่างระหว่างปุ่ม */
            width: 100%;
            margin-top: 10px;
        }
        input[type=submit] {
            background-color: #4CAF50; /* ปุ่มสีเขียว */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type=submit]:hover {
            background-color: #45a049; /* สีเขียวเข้มเมื่อ hover */
        }
        a.cancel-button {
            background-color: #f44336; /* ปุ่มสีแดง */
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-align: center;
            display: inline-block;
        }
        a.cancel-button:hover {
            background-color: #d32f2f; /* สีแดงเข้มเมื่อ hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เพิ่มประเภทข่าวสาร</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="itype_name">ประเภทข่าว:</label>
            <input type="text" id="itype_name" name="itype_name" required>

            <div class="button-container">
                <input type="submit" value="เพิ่มข้อมูล">
                <a href="display_information.php" class="cancel-button">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>
