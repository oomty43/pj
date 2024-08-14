<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (isset($_GET['id'])) {
    $i_id = $_GET['id'];

    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลข่าวสารตาม id
    $sql = "SELECT i.i_head, i.i_deltail, i.i_pic, i.i_cover, it.itype_name, i.i_date
            FROM information i
            INNER JOIN info_type it ON i.itype_id = it.itype_id
            WHERE i.i_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $i_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูล";
        exit();
    }
} else {
    echo "ไม่มีข้อมูลที่ร้องขอ";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดข่าวสาร</title>
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
        h2 {
            margin-bottom: 20px;
        }
        .news-detail {
            text-align: left;
            margin-bottom: 20px;
        }
        .news-detail img {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }
        .back-link {
            margin-top: 20px;
        }
        .back-link a {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($row['i_head']); ?></h2>
        <div class="news-detail">
            <p><strong>ประเภทข่าว:</strong> <?php echo htmlspecialchars($row['itype_name']); ?></p>
            <?php if (!empty($row['i_cover'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['i_cover']); ?>" alt="รูปภาพปก">
            <?php endif; ?>
            <?php if (!empty($row['i_pic'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['i_pic']); ?>" alt="รูปภาพข่าว">
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars($row['i_deltail'])); ?></p>
            <p><strong>วันที่ลงข่าว:</strong> <?php echo htmlspecialchars($row['i_date']); ?></p>
        </div>
        <div class="back-link">
            <a href="display_information.php">กลับหน้ารายการข่าวสาร</a>
        </div>
    </div>
</body>
</html>
