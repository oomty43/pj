<?php
session_start(); // เริ่มต้น session

include 'std_con.php';

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
            background-color: #d35400; /* สีส้มจากโลโก้ */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .nav-buttons a:hover {
            background-color: #e67e22; /* สีส้มอ่อน */
        }
        .news-container {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* กำหนดให้แสดง 4 คอลัมน์ */
            grid-gap: 20px; /* ระยะห่างระหว่างกล่องข่าว */
        }
        .news-item {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .news-item img {
            width: 100%;
            height: 150px; /* ความสูงของภาพ */
            object-fit: cover;
            border-radius: 5px;
        }
        .news-item h2 {
            font-size: 16px;
            color: #333;
            margin: 10px 0;
        }
        .news-item p.date {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .news-item a {
            display: block;
            padding: 10px;
            background-color: #d35400; /* สีส้มจากโลโก้ */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .news-item a:hover {
            background-color: #e67e22; /* สีส้มอ่อน */
        }
        .welcome-message {
            margin: 20px;
            font-size: 20px;
            color: #333;
            text-align: right;
        }
        .pagination {
            text-align: center;
            margin: 20px;
        }
        .pagination a {
            padding: 10px 15px;
            background-color: #d35400; /* สีส้มจากโลโก้ */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }
        .pagination a:hover {
            background-color: #e67e22; /* สีส้มอ่อน */
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/banner1.jpg" alt="Banner" class="banner">

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="stdlist.php">ดูรายชื่อนักศึกษา</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
        <a href="logout.php">ออกจากระบบ</a>
    </div>
  
    <!-- แสดงข้อความต้อนรับ -->
    <div class="welcome-message">
        <?php echo $welcome_message; ?>
    </div>

    <!-- News Section -->
    <div class="news-container">
        <?php
        if (!empty($news_items)) {
            foreach ($news_items as $item) {
                $formatted_date = thai_date($item["i_date"]); // แปลงวันที่เป็นภาษาไทย
                echo "<div class='news-item'>";
                echo "<img src='uploads/" . htmlspecialchars($item["i_cover"]) . "' alt='ข่าวสาร'>";
                echo "<h2>" . htmlspecialchars($item["i_head"]) . "</h2>";
                echo "<p class='date'>" . $formatted_date . "</p>"; // แสดงวันที่
                echo "<a href='news_detail.php?i_id=" . htmlspecialchars($item["i_id"]) . "'>อ่านเพิ่มเติม</a>";
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
