<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $itype_id = $_POST['itype_id'];
    $itype_name = $_POST['itype_name'];

    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูล
    $sql = "INSERT INTO info_type (itype_name) VALUES ('$itype_name')";

    // ตรวจสอบการเพิ่มข้อมูล
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('เพิ่มข้อมูลสำเร็จ');</script>";
        header("Location: display_information.php"); // เปลี่ยนเส้นทางไปยังหน้าที่ต้องการหลังจากเพิ่มข้อมูลเสร็จสิ้น
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
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
        /* คงไว้ตามโค้ดเดิม */
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
        a.cancel-button, a.back-button {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-align: center;
            display: inline-block;
            cursor: pointer;
        }
        a.cancel-button {
            background-color: #f44336; /* ปุ่มสีแดง */
        }
        a.cancel-button:hover {
            background-color: #d32f2f; /* สีแดงเข้มเมื่อ hover */
        }
        a.back-button {
            background-color: #2196F3; /* ปุ่มสีน้ำเงิน */
        }
        a.back-button:hover {
            background-color: #1e88e5; /* สีน้ำเงินเข้มเมื่อ hover */
        }
    </style>
    <script>
        function resetForm() {
            document.getElementById("addForm").reset(); // รีเซ็ตฟอร์ม
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>เพิ่มประเภทข่าวประชาสัมพันธ์</h2>
        <form id="addForm" method="post" enctype="multipart/form-data">
            <label for="itype_name">ประเภทข่าว:</label>
            <input type="text" id="itype_name" name="itype_name" required>

            <div class="button-container">
                <input type="submit" value="เพิ่มข้อมูล">
                <a class="cancel-button" onclick="resetForm()">ยกเลิก</a>
                <a href="display_information.php" class="back-button">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>
