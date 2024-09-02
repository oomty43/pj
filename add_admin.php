<?php
// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// เมื่อมีการส่งข้อมูล POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a_user = $_POST['a_user'];
    $a_na = $_POST['a_na'];
    $a_email = $_POST['a_email'];
    $a_pws = $_POST['a_pws'];
    $a_st = $_POST['a_st']; // รับข้อมูลสถานะ admin

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO admin (a_user, a_na, a_email, a_pws, a_st) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $a_user, $a_na, $a_email, $a_pws, $a_st); // 'i' สำหรับ int

    if ($stmt->execute()) {
        echo "<script>
                alert('เพิ่มข้อมูลเรียบร้อย');
                window.location.href='display_admin.php';
              </script>";
    } else {
        echo '<div style="padding: 20px; background-color: #f0f0f0; border: 1px solid #ccc; margin-top: 20px;">';
        echo "Error: " . $sql . "<br>" . $conn->error;
        echo '</div>';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Add New Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังที่เข้ม */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 50%;
            max-width: 600px;
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
            color: #fff; /* สีตัวอักษร */
        }
        h2 {
            text-align: center;
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #ffa500; /* สีของ label */
        }
        input[type=text], input[type=email], input[type=password] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #555; /* สีขอบของ input */
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #222; /* สีพื้นหลังของ input */
            color: #fff; /* สีตัวอักษรใน input */
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        input[type=submit], .cancel-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            width: 48%; /* ขนาดปุ่ม */
            margin-right: 10px; /* ระยะห่างระหว่างปุ่ม */
        }
        .cancel-button {
            background-color: #FF6347; /* สีของปุ่มยกเลิก */
            margin-left: 10px; /* ระยะห่างระหว่างปุ่ม */
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .cancel-button:hover {
            background-color: #FF4500; /* สีของปุ่มยกเลิกเมื่อเมาส์ชี้ */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เพิ่ม admin</h2>
        <form method="post" action="">
            <label for="a_user">Username:</label>
            <input type="text" id="a_user" name="a_user" required>

            <label for="a_na">Name:</label>
            <input type="text" id="a_na" name="a_na" required>

            <label for="a_email">Email:</label>
            <input type="email" id="a_email" name="a_email" required>

            <label for="a_pws">Password:</label>
            <input type="password" id="a_pws" name="a_pws" required>

            <!-- New status field -->
            <label for="a_st">สถานะ Admin:</label>
            <select id="a_st" name="a_st" required>
                <option value="0">เจ้าหน้าที่</option>
                <option value="1">อาจารย์</option>
            </select>

            <div class="button-group">
                <input type="submit" value="เพิ่ม">
                <a href="display_admin.php" class="cancel-button">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>