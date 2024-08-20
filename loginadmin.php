<!DOCTYPE html>
<html lang="th">
<head>
    <?php 

    // เชื่อมต่อกับฐานข้อมูล
    $servername = "localhost";
    $username = "root";  // ชื่อผู้ใช้ MySQL
    $password = "";      // รหัสผ่าน MySQL (ถ้ามี)
    $dbname = "project"; // ชื่อฐานข้อมูล

    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแลระบบ</title>
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
        .login-container {
            width: 300px;
            background-color: #1e1e1e; /* พื้นหลังกล่องสีเทาเข้ม */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .login-container h2 {
            color: #fbc02d; /* สีเหลือง */
            margin-bottom: 20px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #333; /* ขอบสีเทาเข้ม */
            border-radius: 5px;
            background-color: #333; /* พื้นหลังกล่องข้อความสีเทาเข้ม */
            color: #f0f0f0; /* ตัวอักษรสีขาว */
            font-size: 14px;
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
        .error-message {
            color: #e53935; /* สีแดง */
            font-size: 14px;
            margin-top: 10px;
        }
        .back-link {
            margin-top: 15px; /* เพิ่มระยะห่าง */
            display: block;
            color: #fbc02d; /* สีเหลือง */
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline; /* ขีดเส้นใต้เมื่อชี้ */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin</h2>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" name="a_user" placeholder="ชื่อผู้ใช้" required>
            </div>
            <div class="form-group">
                <input type="password" name="a_pws" placeholder="รหัสผ่าน" required>
            </div>
            <div class="form-group">
                <input type="submit" value="เข้าสู่ระบบ">
            </div>
            <a href="login.php" class="back-link">กลับไปยังหน้าเข้าสู่ระบบ</a>
            <?php
            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>
        </form>
    </div>
</body>
</html>
