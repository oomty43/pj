<?php
// เชื่อมต่อกับฐานข้อมูล
include 'conn.php';
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

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
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        .academic-year {
            margin-top: 10px;
            text-align: left;
            font-size: 18px;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>

    <h2 style="text-align: center;">รายงานรายชื่อนักศึกษา</h2>

    <table>
        <thead>
            <tr>
                <th>รหัสนักศึกษา</th>
                <th>ชื่อ-นามสกุล</th>
                <th>อีเมล์</th>
                <th>สถานะ</th>
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
                echo "<tr><td colspan='4'>ไม่พบข้อมูล</td></tr>";
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

    <button class="no-print" onclick="printPage()">สั่งพิมพ์</button>

    <p style="text-align: right; margin-top: 20px;">วันที่พิมพ์: <?php echo date("d/m/Y"); ?></p>
    <p style="text-align: right;">ผู้พิมพ์: บุคคลภายนอก</p>

    <?php
    // ปิดการเชื่อมต่อ
    $conn->close();
    ?>

</body>
</html>
