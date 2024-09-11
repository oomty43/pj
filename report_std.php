<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
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

// ดึงข้อมูลนักศึกษาจากฐานข้อมูล
$s_id = $_SESSION['s_id'];
$sql = "SELECT s_pic, s_pna, s_na, s_la FROM student WHERE s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['s_pna'] = $row['s_pna'];
    $_SESSION['s_na'] = $row['s_na'];
    $_SESSION['s_la'] = $row['s_la'];
}


// ฟังก์ชั่นแปลงค่าสถานะนักศึกษา
function getStudentStatus($s_stat) {
    switch ($s_stat) {
        case 0:
            return "จบการศึกษาแล้ว";
        case 1:
            return "ยังคงศึกษาอยู่";
        case 2:
            return "ยังไม่ได้จัดการข้อมูล";
        default:
            return "ข้อมูลไม่ถูกต้อง";
    }
}

// ดึงค่าจาก query string
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$batch = isset($_GET['batch']) ? $conn->real_escape_string($_GET['batch']) : '';

// คำสั่ง SQL เพื่อค้นหาข้อมูลนักศึกษา
$sql = "SELECT s_id, s_pna, s_na, s_la, s_email, s_stat 
        FROM student 
        WHERE (s_id LIKE '%$search%' 
        OR s_na LIKE '%$search%' 
        OR s_la LIKE '%$search%')";

if ($status !== '') {
    $sql .= " AND s_stat = '$status'";
}

if ($batch !== '') {
    $sql .= " AND LEFT(s_id, 2) = '$batch'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานรายชื่อนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .no-print {
            display: inline-block;
            margin-top: 20px;
        }
        .no-print button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .no-print button:hover {
            background-color: #0056b3;
        }
        .no-print {
            display: inline-block;
            margin-top: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">รายงานรายชื่อนักศึกษา</h2>

    <table>
        <thead>
            <tr>
                <th>รหัสนักศึกษา</th>
                <th>ชื่อ-นามสกุล</th>
                <th>อีเมล์</th>
                <th>สถานะการศึกษา</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['s_id']) . "</td>";
                    echo "<td>" . getPrefix($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . " " . htmlspecialchars($row['s_la']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['s_email']) . "</td>";
                    echo "<td>" . getStudentStatus($row['s_stat']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>ไม่พบข้อมูลนักศึกษาที่ค้นหา</td></tr>";
            }

            // ฟังก์ชั่นคำนวณปีการศึกษา
            function calculateAcademicYear($batch) {
                return 2500 + intval($batch);
            }
            ?>
            
                    <!-- แสดงปีการศึกษาที่ค้นหา -->
        <div class="academic-year">
            <?php
            if ($batch !== '') {
                echo "ปีการศึกษา: " . calculateAcademicYear($batch);
            } else {
                echo "ปีการศึกษา: ทั้งหมด";
            }
            ?>
        </div>

        </tbody>
    </table>

    <div class="no-print">
        <button onclick="window.print()">พิมพ์รายงาน</button>
    </div>

    <div>
        <p style="text-align: right; margin-top: 20px;">วันที่พิมพ์: <?php echo date("d/m/Y"); ?></p>
        <p style="text-align: right;">พิมพ์โดย: 
    <?php 
    if (isset($_SESSION['s_pna']) && isset($_SESSION['s_na']) && isset($_SESSION['s_la'])) {
        echo getPrefix($_SESSION['s_pna']) . " " . htmlspecialchars($_SESSION['s_na']) . " " . htmlspecialchars($_SESSION['s_la']); 
    } else {
        echo "ไม่ทราบ";
    }
    ?>
    </p>
    
    </div>

    

    <?php
    // ปิดการเชื่อมต่อ
    $conn->close();
    ?>
</body>
</html>
