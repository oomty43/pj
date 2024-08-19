<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบการส่งฟอร์มสมัครสมาชิก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];
    $s_pna = $_POST['s_pna'];
    $s_na = $_POST['s_na'];
    $s_la = $_POST['s_la'];
    $s_email = $_POST['s_email'];

    // ตรวจสอบว่ารหัสนักศึกษามีความยาว 12 ตัวอักษรหรือไม่
    if (strlen($s_id) != 12 || !ctype_digit($s_id)) {
        $error_message = "<span style='color: #e53935; font-size: 12px;'>รหัสนักศึกษา ต้องมีความยาว 12 ตัวอักษรและเป็นตัวเลขเท่านั้น</span>";
    } else {
        // ตรวจสอบว่ามีรหัสนักศึกษาในฐานข้อมูลอยู่แล้วหรือไม่
        $checkSql = "SELECT s_id FROM student WHERE s_id = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("s", $s_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "<span style='color: #e53935; font-size: 12px;'>มีรหัสนักศึกษานี้อยู่แล้ว</span>";
        } else {
            // เก็บรหัสผ่านเป็นข้อความธรรมดา
            $sql = "INSERT INTO student (s_id, s_pws, s_pna, s_na, s_la, s_email) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $s_id, $s_pws, $s_pna, $s_na, $s_la, $s_email);

            if ($stmt->execute()) {
                echo "<script>
                    alert('สมัครสมาชิกสำเร็จ');
                    window.location='login.php';
                </script>";
                exit();
            } else {
                $error_message = "<span style='color: #e53935; font-size: 12px;'>เกิดข้อผิดพลาดในการสมัครสมาชิก</span>";
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212; /* พื้นหลังสีดำ */
            color: #f0f0f0; /* ตัวอักษรสีขาว */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 360px;
            background-color: #1e1e1e; /* พื้นหลังกล่องสีเทาเข้ม */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: left;
            position: relative;
        }
        h2 {
            text-align: center;
            color: #fbc02d; /* สีเหลือง */
            margin-bottom: 20px;
            font-weight: 600;
        }
        input[type=text], input[type=password], input[type=email], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #333; /* ขอบสีเทาเข้ม */
            border-radius: 5px;
            font-size: 14px;
            color: #f0f0f0; /* ตัวอักษรสีขาว */
            background-color: #333; /* พื้นหลังกล่องข้อความสีเทาเข้ม */
        }
        input[type=submit] {
            width: 100%;
            background-color: #fbc02d; /* สีเหลือง */
            color: #121212; /* ตัวอักษรสีดำ */
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        input[type=submit]:hover {
            background-color: #f9a825; /* สีเหลืองเข้ม */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .register-link, .guest-login, .admin-login {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a, .guest-login a, .admin-login a {
            text-decoration: none;
            color: #fbc02d; /* สีเหลือง */
            font-size: 14px;
        }
        .register-link a:hover, .guest-login a:hover, .admin-login a:hover {
            text-decoration: underline;
        }
        .error-message {
            margin-bottom: 10px;
            text-align: center;
            color: #e53935; /* สีแดง */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>สมัครสมาชิก</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="s_pna">คำนำหน้า:</label>
                <select id="s_pna" name="s_pna" required>
                    <option value="" disabled selected>เลือกคำนำหน้า</option>
                    <option value="1">นาย</option>
                    <option value="2">นาง</option>
                    <option value="3">นางสาว</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="s_id" placeholder="รหัสนักศึกษา" required pattern="\d{12}" title="กรุณากรอกรหัสนักศึกษา 12 ตัวอักษร">
            </div>
            <div class="form-group">
                <input type="password" name="s_pws" placeholder="รหัสผ่าน" required>
            </div>
            <div class="form-group">
                <input type="text" name="s_na" placeholder="ชื่อ" required>
            </div>
            <div class="form-group">
                <input type="text" name="s_la" placeholder="นามสกุล" required>
            </div>
            <div class="form-group">
                <input type="email" name="s_email" placeholder="อีเมล" required>
            </div>
            <?php
            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>
            <div class="form-group">
                <input type="submit" value="สมัครสมาชิก">
            </div>
        </form>
        <div class="register-link">
            <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
        <div class="guest-login">
            <p><a href="guestmain.php">เยี่ยมชมเว็บไซต์</a></p>
        </div>
        <div class="admin-login">
            <p><a href="loginadmin.php">เข้าสู่ระบบสำหรับผู้ดูแล</a></p>
        </div>
    </div>
</body>
</html>
