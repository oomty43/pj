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

// ตรวจสอบหมายเลขหน้าปัจจุบัน
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 3;
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
            max-width: 100%; /* จำกัดความกว้างไม่ให้เกิน 100% ของคอนเทนเนอร์ */
            max-height: 300px; /* จำกัดความสูงสูงสุดที่ 300px */
            object-fit: cover; /* ให้รูปภาพไม่บิดเบี้ยวแต่ครอบคลุมพื้นที่ */
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
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .news-item a:hover {
            background-color: #0056b3;
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
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/banner.jpg" alt="Banner" class="banner">

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
        if (!empty($news_items)) {
            foreach ($news_items as $item) {
                $formatted_date = date('d-m-Y', strtotime($item["i_date"])); // แปลงวันที่เป็นรูปแบบที่ต้องการ
                echo "<div class='news-item'>";
                echo "<img src='uploads/" . htmlspecialchars($item["i_cover"]) . "' alt='ข่าวสาร'>";
                echo "<h2>" . htmlspecialchars($item["i_head"]) . "</h2>";
                echo "<p class='date'>" . $formatted_date . "</p>"; // แสดงวันที่
                echo "<p><a href='news_detail.php?i_id=" . htmlspecialchars($item["i_id"]) . "'>อ่านเพิ่มเติม</a></p>";
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
