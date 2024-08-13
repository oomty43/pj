<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_pws = $_POST['s_pws'];

    $sql = "SELECT * FROM student WHERE s_id = ? AND s_pws = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $s_id, $s_pws);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['s_id'] = $s_id;
        header("Location: main.php");
        exit();
    } else {
        echo "<script>alert('รหัสนักศึกษา หรือรหัสผ่านไม่ถูกต้อง');</script>";
    }
}
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
    </style>
</head>
<body>
    <div class="container">
        <h2>เข้าสู่ระบบ</h2>
        <form method="post">
            <input type="text" name="s_id" placeholder="รหัสนักศึกษา" required>
            <input type="password" name="s_pws" placeholder="รหัสผ่าน" required>
            <input type="submit" value="เข้าสู่ระบบ">
        </form>
        <div class="register-link">
            <p>ยังไม่ได้เป็นสมาชิก? <a href="register.php">สมัครสมาชิก</a></p>
        </div>
    </div>
</body>
</html>
