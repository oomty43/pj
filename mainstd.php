<?php
session_start(); // เริ่มต้น session

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['s_id'])) {
    header('Location: login.php'); // หากไม่ได้เข้าสู่ระบบ ให้กลับไปที่หน้า login
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "project");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชั่นแปลงค่า s_pna
function getPrefix($s_pna) {
    switch ($s_pna) {
        case 1:
            return "นาย";
        case 2:
            return "นาง";
        case 3:
            return "นางสาว";
        default:
            return "ไม่ทราบ";
    }
}

// ดึงข้อมูลนักศึกษาจากฐานข้อมูลตาม user id ใน session
$s_id = $_SESSION['s_id'];
$sql = "SELECT s_pna, s_na, s_la FROM student WHERE s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $welcome_message = "ยินดีต้อนรับ : " . getPrefix($row["s_pna"]) . " " . $row["s_na"] . " " . $row["s_la"];
} else {
    $welcome_message = "ไม่พบข้อมูลนักศึกษา";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลักเว็บไซต์</title>
    <style>
        /* สไตล์เดิม */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .banner {
            width: 100%;
            height: auto;
        }
        .nav-buttons {
            text-align: right;
            margin: 10px;
        }
        .nav-buttons a {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .nav-buttons a:hover {
            background-color: #0056b3;
        }
        .news-container {
            padding: 20px;
        }
        .news-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .news-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .news-item h2 {
            font-size: 18px;
            color: #333;
        }
        .welcome-message {
            margin: 20px;
            font-size: 20px;
            color: #333;
            text-align: right; /* ทำให้ข้อความชิดขวา */
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/testb.jpg" alt="Banner" class="banner">

    <!-- แสดงข้อความต้อนรับ -->
    <div class="welcome-message">
        <?php echo $welcome_message; ?>
    </div>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="stdlist.php">ดูรายชื่อนักศึกษา</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
    </div>

    <!-- News Section -->
    <div class="news-container">
        <?php
        // เชื่อมต่อฐานข้อมูล
        $conn = new mysqli("localhost", "root", "", "project");

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
        }

        // คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง information
        $sql = "SELECT i_cover, i_head FROM information";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // แสดงผลข้อมูล
            while($row = $result->fetch_assoc()) {
                echo "<div class='news-item'>";
                echo "<img src='upload/" . $row["i_cover"] . "' alt='ข่าวสาร'>";
                echo "<h2>" . $row["i_head"] . "</h2>";
                echo "</div>";
            }
        } else {
            echo "ไม่มีข่าวสาร";
        }

        // ปิดการเชื่อมต่อ
        $conn->close();
        ?>
    </div>

</body>
</html>
