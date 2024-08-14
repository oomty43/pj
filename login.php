<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ MySQL
$password = "";      // รหัสผ่าน MySQL (ถ้ามี)
$dbname = "project"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการส่งฟอร์มล็อกอิน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];
  

    // ค้นหาข้อมูลผู้ดูแลในฐานข้อมูล
    $sql = "SELECT * FROM student WHERE s_id = ? AND s_pws = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $s_id, $s_pws);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // เข้าสู่ระบบสำเร็จ
        $_SESSION['s_id'] = $s_id;
        header("Location: mainstd.php"); // ไปยังหน้า mainstd.php หลังจากล็อกอินสำเร็จ
        exit();
    } else {
        // ล็อกอินไม่สำเร็จ
        $error_message = "<span style='color: red; font-size: 12px;'>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</span>";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        .container {
            width: 300px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
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
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .register-link {
            margin-top: 10px;
        }
        .register-link a {
            text-decoration: none;
            color: #4CAF50;
        }
        .admin-login {
            position: right;
            size : 5px;
            top: 10px;
            right: 10px;
            bottom : 10px;
        }
        .admin-login a {
            text-decoration: none;
            color: #4CAF50;
        }
        .guest-login {
            margin-top: 20px;
            color: #4CAF50;
        }
        .guest-login a {
            text-decoration: none;
            color: #4CAF50;
        }
        .error-message {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-login">
            <p><a href="loginstd.php">เข้าสู่ระบบสำหรับผู้ดูแล</a></p>
            </form>
        </div>
        <h2>เข้าสู่ระบบ</h2>
        <form method="post" action="">
            
            <div class="form-group">
                <input type="text" name="s_id" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="s_pws" placeholder="Password" required>
            </div>
            <?php
            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
        </form>
        <div class="register-link">
            <p>ยังไม่ได้เป็นสมาชิก? <a href="register.php">สมัครสมาชิก</a></p>
        </div>
        <div class="guest-login">
            <p><a href="guestlogin.php">เยี่ยมชมเว็บไซต์</a></p>
            </form>
        </div>
    </div>
</body>
</html>
