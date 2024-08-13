<?php
session_start();

if (!isset($_SESSION['s_id'])) {
    header("Location: login.php");
    exit();
}

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงชื่อและนามสกุลของผู้ใช้ที่ล็อกอิน
$s_id = $_SESSION['s_id'];
$sql = "SELECT s_na, s_la FROM student WHERE s_id = '$s_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['s_na'] . ' ' . $row['s_la'];
} else {
    $full_name = 'ผู้ใช้ไม่พบ';
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>หน้าแรก</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .popup button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        .popup button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ยินดีต้อนรับ</h2>
    </div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <h3>สวัสดี, <?php echo $full_name; ?>!</h3>
        <button onclick="hidePopup()">ปิด</button>
    </div>

    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        // แสดง popup อัตโนมัติเมื่อโหลดหน้า
        window.onload = function() {
            showPopup();
        }
    </script>
</body>
</html>
