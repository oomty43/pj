<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// ฟังก์ชันสำหรับแปลงวันที่เป็นเดือนภาษาไทย
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

    $year = substr($date, 0, 4); 
    $month = $thai_months[substr($date, 5, 2)]; 
    $day = substr($date, 8, 2); 

    return "$day $month $year";
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

    // ดึงข้อมูลรูปภาพเพิ่มเติมจากตาราง information_pic
    $sql_pic = "SELECT i_pic FROM information_pic WHERE i_id = ?";
    $stmt_pic = $conn->prepare($sql_pic);
    $stmt_pic->bind_param("i", $i_id);
    $stmt_pic->execute();
    $result_pic = $stmt_pic->get_result();
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
            background-color: #181818;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 800px;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            margin: 50px auto;
        }
        h2 {
            color: #ffa500;
            margin-bottom: 20px;
        }
        .news-detail {
            text-align: left;
            margin-bottom: 20px;
        }
        .news-detail img.cover {
            width: 100%;
            max-width: 400px; /* ปรับขนาดรูปภาพปกให้เล็กลง */
            height: auto;
            margin: 10px 0;
            border-radius: 5px;
            object-fit: cover;
        }
        .news-detail img.additional {
            width: 100%;
            max-width: 120px; /* ขนาดรูปภาพเพิ่มเติม */
            height: auto;
            border-radius: 5px;
            object-fit: cover;
        }
        .news-detail p {
            margin: 10px 0;
        }
        .additional-images {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 20px;
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
            transition: background-color 0.3s ease;
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
                <!-- แสดงรูปภาพปก -->
                <img class="cover" src="uploads/<?php echo htmlspecialchars($row['i_cover']); ?>" alt="รูปภาพปก">
            <?php endif; ?>
            <!-- แสดงรายละเอียดข่าวประชาสัมพันธ์ -->
            <p><?php echo nl2br(htmlspecialchars($row['i_deltail'])); ?></p>

            <!-- แสดงรูปภาพเพิ่มเติมแบบคอลัมน์ 5x5 -->
            <div class="additional-images">
                <?php if ($result_pic->num_rows > 0): ?>
                    <?php while ($pic_row = $result_pic->fetch_assoc()): ?>
                        <img class="additional" src="uploads/<?php echo htmlspecialchars($pic_row['i_pic']); ?>" alt="รูปภาพเพิ่มเติม">
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- วันที่เผยแพร่ย้ายไปอยู่ด้านล่าง -->
            <p><strong>วันที่เผยแพร่:</strong> <?php echo htmlspecialchars(thai_date($row['i_date'])); ?></p>
        </div>
        <div class="back-link">
            <a href="display_information.php">กลับหน้าหลัก</a>
        </div>
    </div>
</body>
</html>
