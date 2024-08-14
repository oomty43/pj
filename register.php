<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];
    $s_na = $_POST['s_na'];
    $s_la = $_POST['s_la'];
    $s_gender = $_POST['s_gender']; // รับค่าข้อมูลเพศ

    // ตรวจสอบว่ารหัสนักศึกษามีความยาว 12 ตัวอักษรหรือไม่
    if (strlen($s_id) != 12 || !ctype_digit($s_id)) {
        echo "<script>
            alert('รหัสนักศึกษา ต้องมีความยาว 12 ตัวอักษรและเป็นตัวเลขเท่านั้น');
            window.history.back();
        </script>";
        exit();
    }

    // ตรวจสอบว่ามีรหัสนักศึกษาในฐานข้อมูลอยู่แล้วหรือไม่
    $checkSql = "SELECT s_id FROM student WHERE s_id = '$s_id'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo "<script>
            alert('มีผู้ใช้นี้อยู่แล้ว');
            window.history.back();
        </script>";
        exit();
    }

    // เก็บรหัสผ่านเป็นข้อความธรรมดา
    $sql = "INSERT INTO student (s_id, s_pws, s_na, s_la, s_gender) VALUES ('$s_id', '$s_pws', '$s_na', '$s_la', '$s_gender')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('สมัครสมาชิกสำเร็จ');
            window.location='login.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>สมัครสมาชิก</title>
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
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
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
        .form-group {
            width: 100%;
            margin: 5px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .back-link {
            margin-top: 20px;
        }
        .back-link a {
            text-decoration: none;
            color: #007BFF;
            font-size: 16px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>สมัครสมาชิก</h2>
        <form method="post">
            <div class="form-group">
                <label for="s_gender">เพศ:</label>
                <select id="s_gender" name="s_gender" required>
                    <option value="" disabled selected>เลือกเพศ</option>
                    <option value="1">ชาย</option>
                    <option value="2">หญิง</option>
                    <option value="3">ไม่ระบุ</option>
                </select>
            </div>

            <label for="s_id">รหัสนักศึกษา:</label>
            <input type="text" id="s_id" name="s_id" pattern="\d{12}" required title="กรุณากรอกรหัสนักศึกษา 12 ตัวอักษร">

            <label for="s_pws">รหัสผ่าน:</label>
            <input type="password" id="s_pws" name="s_pws" required>

            <label for="s_na">ชื่อ:</label>
            <input type="text" id="s_na" name="s_na" required>

            <label for="s_la">นามสกุล:</label>
            <input type="text" id="s_la" name="s_la" required>

            <input type="submit" value="สมัครสมาชิก">
        </form>
        <div class="back-link">
            <a href="login.php">กลับไปที่หน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>
