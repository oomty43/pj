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

// รับค่า i_id จาก URL
$i_id = isset($_GET['i_id']) ? (int)$_GET['i_id'] : 0;

// ดึงข้อมูลข่าวสารจากฐานข้อมูลตาม i_id
$sql = "SELECT i_head, i_cover, i_date, i_deltail FROM information WHERE i_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $i_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $news_item = $result->fetch_assoc();
} else {
    $news_item = null;
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
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .news-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .news-item h1 {
            font-size: 24px;
            color: #333;
            margin-top: 0;
        }
        .news-item .date {
            font-size: 14px;
            color: #777;
            margin: 10px 0;
        }
        .news-item p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <?php if ($news_item): ?>
            <div class="news-item">
                <h1><?php echo htmlspecialchars($news_item['i_head']); ?></h1>
                <img src="uploads/<?php echo htmlspecialchars($news_item['i_cover']); ?>" alt="ข่าวสาร">
                <div class="date"><?php echo htmlspecialchars(date('d F Y', strtotime($news_item['i_date']))); ?></div>
                <p><?php echo htmlspecialchars($news_item['i_deltail']); ?></p>
            </div>
        <?php else: ?>
            <p>ไม่มีข้อมูลข่าวสารที่พบ</p>
        <?php endif; ?>
        
        <a href="mainstd.php" class="back-link">กลับไปที่หน้าหลัก</a>
    </div>

</body>
</html>
