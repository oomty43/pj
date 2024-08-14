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
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-login">
            <p><a href="loginadmin.php">เข้าสู่ระบบสำหรับผู้ดูแล</a></p>
            </form>
        </div>
        <h2>เข้าสู่ระบบ</h2>
        <form method="post">
            <input type="text" name="s_id" placeholder="รหัสนักศึกษา" required>
            <input type="password" name="s_pws" placeholder="รหัสผ่าน" required>
            <input type="submit" value="เข้าสู่ระบบ">
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
