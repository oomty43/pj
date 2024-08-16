<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "project"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];

    $sql = "SELECT * FROM student WHERE s_id = ? AND s_pws = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $s_id, $s_pws);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['s_id'] = $s_id;
        header("Location: mainstd.php");
        exit();
    } else {
        $error_message = "<span style='color: #e53935; font-size: 12px;'>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</span>";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
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
        input[type=text], input[type=password] {
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
        <h2>เข้าสู่ระบบ</h2>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" name="s_id" placeholder="รหัสนักศึกษา" required>
            </div>
            <div class="form-group">
                <input type="password" name="s_pws" placeholder="รหัสผ่าน" required>
            </div>
            <?php
            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>
            <div class="form-group">
                <input type="submit" value="เข้าสู่ระบบ">
            </div>
        </form>
        <div class="register-link">
            <p>ยังไม่ได้เป็นสมาชิก? <a href="register.php">สมัครสมาชิก</a></p>
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
