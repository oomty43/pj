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
    $a_user = $_POST['a_user'];
    $a_pws = $_POST['a_pws'];
  

    // ค้นหาข้อมูลผู้ดูแลในฐานข้อมูล
    $sql = "SELECT * FROM admin WHERE a_user = ? AND a_pws = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $a_user, $a_pws);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // เข้าสู่ระบบสำเร็จ
        $_SESSION['a_user'] = $a_user;
        header("Location: mainadmin.php"); // ไปยังหน้า mainadmin.php หลังจากล็อกอินสำเร็จ
        exit();
    } else {
        // ล็อกอินไม่สำเร็จ
        $error_message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        .login-container {
            margin-top: 100px;
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ผู้ดูแลระบบ </h2>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" name="a_user" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="a_pws" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="เข้าสู่ระบบ">
            </div>
            <?php
            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>
        </form>
    </div>
</body>
</html>
