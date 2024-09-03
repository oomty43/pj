<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันแปลงค่า s_pna
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

// ฟังก์ชั่นแปลงค่าสถานะนักศึกษาเป็นปุ่ม
function getStudentStatus($s_stat) {
    if ($s_stat == 1) {
        return "<button style='background-color: green; color: white; border: none; padding: 5px 10px; border-radius: 5px;'>ยังคงศึกษาอยู่</button>";
    } else {
        return "<button style='background-color: blue; color: white; border: none; padding: 5px 10px; border-radius: 5px;'>จบการศึกษาแล้ว</button>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        img.banner {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .profile-section {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        img.profile {
            width: 200px;
            height: auto;
            border-radius: 10px;
        }
        .details {
            flex-grow: 1;
        }
        .details h1 {
            margin-top: 0;
        }
    </style>
</head>
<body>

<!-- แสดงแบนเนอร์ -->
<img src="uploads/banner1.jpg" alt="แบนเนอร์" class="banner">

<div class="container">
    <?php
    if (isset($_GET['s_id'])) {
        $s_id = $conn->real_escape_string($_GET['s_id']);
        
        $sql = "SELECT * FROM student WHERE s_id = '$s_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            echo '<div class="profile-section">';
            
            // แสดงรูปภาพนักศึกษา
            $imagePath = !empty($row['s_pic']) ? 'uploads/' . htmlspecialchars($row['s_pic']) : 'uploads/icon.jpg';
            echo "<img src='$imagePath' alt='รูปภาพนักศึกษา' class='profile'>";

            // แสดงข้อมูลนักศึกษา
            echo '<div class="details">';
            echo "<h1>" . getPrefix($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . " " . htmlspecialchars($row['s_la']) . "</h1>";
            echo "<p>อีเมล์: " . htmlspecialchars($row['s_email']) . "</p>";
            echo "<p>สถานะนักศึกษา " . getStudentStatus($row['s_stat']) . "</p>";
            echo '</div>'; // ปิด .details

            echo '</div>'; // ปิด .profile-section
        } else {
            echo "ไม่พบข้อมูลนักศึกษา";
        }
    } else {
        echo "ไม่ได้ระบุรหัสนักศึกษา";
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
    ?>
</div>

</body>
</html>
