<?php

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "project");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันสำหรับแปลงเดือนเป็นภาษาไทย
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

    $year = substr($date, 0, 4) + 543; // แปลงปีเป็น พ.ศ.
    $month = $thai_months[substr($date, 5, 2)]; // หาชื่อเดือนจากฟังก์ชันข้างต้น
    $day = substr($date, 8, 2); // ดึงวันที่ออกมา

    return "$day $month $year";
}

// ตรวจสอบหมายเลขหน้าปัจจุบัน
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 16; // เปลี่ยนจำนวนรายการต่อหน้าเป็น 16 (4x4)
$offset = ($page - 1) * $items_per_page;

// ดึงข้อมูลข่าวสารจากฐานข้อมูล
$sql = "SELECT i_id, i_cover, i_head, i_date FROM information ORDER BY i_date DESC LIMIT $offset, $items_per_page";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $news_items = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $news_items = [];
}

// คำนวณจำนวนหน้าทั้งหมด
$sql_total = "SELECT COUNT(*) AS total FROM information";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_items = $row_total['total'];
$total_pages = ceil($total_items / $items_per_page);

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
            background-color: #f4f4f4; /* สีพื้นหลังเดิม */
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
            background-color: rgb(232, 98, 1); /* เปลี่ยนสีปุ่มเป็นสีส้ม */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .nav-buttons a:hover {
            background-color: rgb(186, 79, 1); /* เปลี่ยนสีปุ่มเมื่อ hover เป็นสีส้มเข้ม */
        }
        .news-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* จัดเรียงเป็น 4 คอลัมน์ */
            gap: 20px;
            padding: 20px;
        }
        .news-item {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .news-item img {
            max-width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 5px;
            display: block;
            margin: 0 auto;
        }
        .news-item h2 {
            font-size: 18px;
            color: #333;
        }
        .news-item p.date {
            font-size: 14px;
            color: #666;
        }
        .news-item a {
            display: inline-block;
            padding: 10px;
            background-color: rgb(232, 98, 1); /* เปลี่ยนสีปุ่มเป็นสีส้ม */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .news-item a:hover {
            background-color: rgb(186, 79, 1); /* เปลี่ยนสีปุ่มเมื่อ hover เป็นสีส้มเข้ม */
        }
        .welcome-message {
            margin: 20px;
            font-size: 20px;
            color: #333;
            text-align: right; /* ทำให้ข้อความชิดขวา */
        }
        .pagination {
            text-align: center;
            margin: 20px;
        }
        .pagination a {
            padding: 10px 15px;
            background-color: rgb(232, 98, 1); /* เปลี่ยนสีปุ่มเป็นสีส้ม */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }
        .pagination a:hover {
            background-color: rgb(186, 79, 1); /* เปลี่ยนสีปุ่มเมื่อ hover เป็นสีส้มเข้ม */
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/banner1.jpg" alt="Banner" class="banner">

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="gstdlist.php">รายชื่อนักศึกษา</a>
        <a href="login.php">เข้าสู่ระบบ</a>
    </div>

    <!-- News Section -->
    <div class="news-container">
        <?php
        if (!empty($news_items)) {
            foreach ($news_items as $item) {
                $formatted_date = thai_date($item["i_date"]); // ใช้ฟังก์ชันแปลงวันที่เป็นภาษาไทย
                echo "<div class='news-item'>";
                echo "<img src='uploads/" . htmlspecialchars($item["i_cover"]) . "' alt='ข่าวสาร'>";
                echo "<h2>" . htmlspecialchars($item["i_head"]) . "</h2>";
                echo "<p class='date'>" . $formatted_date . "</p>";
                echo "<p><a href='gnews_detail.php?i_id=" . htmlspecialchars($item["i_id"]) . "'>อ่านเพิ่มเติม</a></p>";
                echo "</div>";
            }
        } else {
            echo "ไม่มีข่าวสาร";
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">« ก่อนหน้า</a>
        <?php endif; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">ถัดไป »</a>
        <?php endif; ?>
    </div>

</body>
</html>
