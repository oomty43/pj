<?php
session_start(); // เริ่มต้น session

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "project");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// รับ ID ของข่าวสารจาก URL
$i_id = isset($_GET['i_id']) ? (int)$_GET['i_id'] : 0;

// ฟังก์ชันสำหรับแปลงเดือนเป็นภาษาไทย โดยแสดงปีเป็น ค.ศ.
function thai_date($date) {
    $thai_months = [
        "01" => "มกราคม",
        "02" => "กุมภาพันธ์",
        "03" => "มีนาคม",
        "04" => "เมษายน",
        "05" => "พฤษภาคม",
        "06" => "มิถุนายน",
        "07" => "กรกฎาคม",
        "08" => "สิงหาคม",
        "09" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม"
    ];

    $year = substr($date, 0, 4); // ไม่แปลงปีเป็น พ.ศ. แต่ให้เป็น ค.ศ.
    $month = $thai_months[substr($date, 5, 2)];
    $day = substr($date, 8, 2);

    return "$day $month $year";
}

// ดึงข้อมูลข่าวสารจากฐานข้อมูลตาม ID
$sql = "SELECT i_head, i_deltail, i_cover, i_date FROM information WHERE i_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $i_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $news_item = $result->fetch_assoc();
    $thai_date = thai_date($news_item['i_date']); // แปลงวันที่เป็นภาษาไทย
} else {
    echo "ไม่พบข้อมูลข่าวสาร";
    exit();
}

// ดึงข้อมูลรูปภาพเพิ่มเติมจากฐานข้อมูล
$sql_pic = "SELECT i_pic FROM information_pic WHERE i_id = ?";
$stmt_pic = $conn->prepare($sql_pic);
$stmt_pic->bind_param("i", $i_id);
$stmt_pic->execute();
$result_pic = $stmt_pic->get_result();

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
            height: auto;
            max-height: 300px;
            object-fit: contain;
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
            margin-bottom: 20px;
        }
        .additional-images {
            display: flex;
            flex-direction: column;
            gap: 15px; /* ช่องว่างระหว่างรูปภาพ */
            margin-top: 20px;
        }
        .additional-images img {
            width: 100%;
            max-width: 600px; /* ขนาดรูปภาพเพิ่มเติมใหญ่ขึ้น */
            height: auto;
            border-radius: 5px;
            object-fit: cover;
        }
        .back-link-container {
            text-align: center; /* จัดตำแหน่งปุ่มให้อยู่ตรงกลาง */
            margin-top: 20px;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #E76324; /* สีปุ่มตามที่กำหนด */
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background-color: #d35400; /* สีเมื่อเมาส์ชี้ */
        }
    </style>
</head>
<body>
    
    <!-- Banner -->
    <img src="uploads/banner1.jpg" alt="Banner" class="banner">

    <div class="container">
        <img src="uploads/<?php echo htmlspecialchars($news_item['i_cover']); ?>" alt="ข่าวสาร" class="news-cover">
        <div class="news-title"><?php echo htmlspecialchars($news_item['i_head']); ?></div>
        <div class="news-date"><?php echo htmlspecialchars($thai_date); ?></div>
        <div class="news-detail">
            <?php echo nl2br(htmlspecialchars($news_item['i_deltail'])); ?>
        </div>

        <!-- แสดงรูปภาพเพิ่มเติมแบบรายการ (list) -->
        <div class="additional-images">
            <?php if ($result_pic->num_rows > 0): ?>
                <?php while ($pic_row = $result_pic->fetch_assoc()): ?>
                    <img src="uploads/<?php echo htmlspecialchars($pic_row['i_pic']); ?>" alt="รูปภาพเพิ่มเติม">
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="back-link-container">
            <a href="index.php" class="back-link">กลับไปที่หน้าหลัก</a>
        </div>
    </div>

</body>
</html>
