<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'conn.php';
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

if (isset($_GET['s_id'])) {
    $s_id = $conn->real_escape_string($_GET['s_id']);
    
    // ดึงข้อมูลนักศึกษาจากฐานข้อมูล
    $sql = "SELECT * FROM student WHERE s_id = '$s_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
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
        .course-table, .history-table, .skill-table, .programming-skill-table, 
        .certificate-table, .event-table, .work-history-table, .education-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .course-table th, .course-table td, 
        .history-table th, .history-table td, 
        .skill-table th, .skill-table td,
        .programming-skill-table th, .programming-skill-table td,
        .certificate-table th, .certificate-table td,
        .event-table th, .event-table td,
        .work-history-table th, .work-history-table td,
        .education-history-table th, .education-history-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .course-table th, .history-table th, .skill-table th, .programming-skill-table th,
        .certificate-table th, .event-table th, .work-history-table th, .education-history-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<!-- เปลี่ยนปุ่ม Print ข้อมูลเป็นลิงก์ที่ไปยัง print_std_detail.php -->
<?php if (isset($s_id)): ?>
<a href="print_gstd_detail.php?s_id=<?php echo $s_id; ?>" style="margin: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none;">
    Print ข้อมูล
</a>
<?php endif; ?>

<!-- แสดงแบนเนอร์ -->
<img src="uploads/banner1.jpg" alt="แบนเนอร์" class="banner">

<div class="nav-buttons">
    <a href="index.php">หน้าหลัก</a>
    <a href="login.php">เข้าสู่ระบบ</a>
</div>

<div class="container">
    <?php
    if (isset($s_id)) {
        $sql = "SELECT * FROM student WHERE s_id = '$s_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            echo '<div class="profile-section">';
            
            // แสดงรูปภาพนักศึกษา
            $imagePath = !empty($row['s_pic']) ? 'uploads/' . basename(htmlspecialchars($row['s_pic'])) : 'uploads/icon.jpg';
            echo "<img src='$imagePath' alt='รูปภาพนักศึกษา' class='profile'>";

            // แสดงข้อมูลนักศึกษา
            echo '<div class="details">';
            echo "<h1>" . getPrefix($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . " " . htmlspecialchars($row['s_la']) . "</h1>";
            echo "<p>Email: " . htmlspecialchars($row['s_email']) . "</p>";
            echo "<p>Status: " . getStudentStatus($row['s_stat']) . "</p>";
            echo '</div>'; // ปิด .details

            echo '</div>'; // ปิด .profile-section

            // ดึงข้อมูลการเข้าอบรมจากตาราง Course
            $courseSql = "SELECT c_na, c_add, c_date FROM course WHERE s_id = '$s_id'";
            $courseResult = $conn->query($courseSql);
            
            if ($courseResult->num_rows > 0) {
                echo "<h2>การเข้าอบรม</h2>";
                echo "<table class='course-table'>";
                echo "<thead><tr><th>ชื่อโครงการอบรม</th><th>ชื่อสถานที่อบรม</th><th>ปีที่อบรม</th></tr></thead>";
                echo "<tbody>";
            
                while ($courseRow = $courseResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($courseRow['c_na']) . "</td>";
                    echo "<td>" . htmlspecialchars($courseRow['c_add']) . "</td>";
                    echo "<td>" . htmlspecialchars($courseRow['c_date']) . "</td>";
                    echo "</tr>";
                }
            
                echo "</tbody></table>";
            } else {
                echo "<p>ไม่มีข้อมูลการเข้าอบรม</p>";
            }
            
            // ดึงข้อมูลการฝึกงานจากตาราง its_history
            $internshipSql = "SELECT its_name, its_province, its_date, its_file FROM its_history WHERE s_id = '$s_id'";
            $internshipResult = $conn->query($internshipSql);

            if ($internshipResult->num_rows > 0) {
                echo "<h2>ประวัติการฝึกงาน</h2>";
                echo "<table class='history-table'>";
                echo "<thead><tr><th>สถานที่ฝึกงาน</th><th>จังหวัด</th><th>ปีที่ฝึกงาน</th><th>โปรเจคฝึกงาน</th></tr></thead>";
                echo "<tbody>";

                while ($internshipRow = $internshipResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($internshipRow['its_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($internshipRow['its_province']) . "</td>";
                    echo "<td>" . htmlspecialchars($internshipRow['its_date']) . "</td>";
                    echo "<td><a href='" . basename(htmlspecialchars($internshipRow['its_file'])) . "' target='_blank'>ดูโปรเจค</a></td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p>ไม่มีข้อมูลการฝึกงาน</p>";
            }

            // ดึงข้อมูลใบรับรองจากตาราง certi
            $certificateSql = "SELECT ce_na, og_na, ce_year, ce_file FROM certi WHERE s_id = '$s_id'";
            $certificateResult = $conn->query($certificateSql);

            if ($certificateResult->num_rows > 0) {
                echo "<h2>ใบรับรอง</h2>";
                echo "<table class='certificate-table'>";
                echo "<thead><tr><th>ชื่อใบรับรอง</th><th>หน่วยงานที่รับรอง</th><th>ปีที่ได้รับ</th><th>เอกสารแนบ</th></tr></thead>";
                echo "<tbody>";

                while ($certificateRow = $certificateResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($certificateRow['ce_na']) . "</td>";
                    echo "<td>" . htmlspecialchars($certificateRow['og_na']) . "</td>";
                    echo "<td>" . htmlspecialchars($certificateRow['ce_year']) . "</td>";
                    echo "<td><a href='" . htmlspecialchars($certificateRow['ce_file']) . "' target='_blank'>ดูเอกสาร</a></td>";

                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p>ไม่มีข้อมูลใบรับรอง</p>";
            }

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
