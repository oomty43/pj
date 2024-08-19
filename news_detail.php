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

// รับ ID ของข่าวสารจาก URL
$i_id = isset($_GET['i_id']) ? (int)$_GET['i_id'] : 0;

// ดึงข้อมูลข่าวสารจากฐานข้อมูลตาม ID
$sql = "SELECT i_head, i_deltail, i_cover, i_date FROM information WHERE i_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $i_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $news_item = $result->fetch_assoc();
} else {
    echo "ไม่พบข้อมูลข่าวสาร";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดข่าวสาร</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .news-cover {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .news-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .news-date {
            font-size: 16px;
            color: #777;
            margin-bottom: 20px;
        }
        .news-detail {
            font-size: 18px;
            color: #333;
            line-height: 1.6;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <img src="uploads/<?php echo htmlspecialchars($news_item['i_cover']); ?>" alt="ข่าวสาร" class="news-cover">
        <div class="news-title"><?php echo htmlspecialchars($news_item['i_head']); ?></div>
        <div class="news-date"><?php echo htmlspecialchars($news_item['i_date']); ?></div>
        <div class="news-detail">
            <?php echo nl2br(htmlspecialchars($news_item['i_deltail'])); ?>
        </div>
        <a href="mainstd.php" class="back-link">กลับไปที่หน้าหลัก</a>
    </div>

</body>
</html>
