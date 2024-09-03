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

// ฟังก์ชั่นคำนวณรุ่นปัจจุบัน
function calculateCurrentBatch() {
    $currentYear = date('Y');
    $currentMonth = date('m');

    if ($currentMonth >= 3) { // ถ้าเดือนปัจจุบันมีนาคมขึ้นไป
        return 67 + ($currentYear - 2024); // เริ่มต้นที่รุ่น 67 ในปี 2024
    } else {
        return 66 + ($currentYear - 2024); // ใช้รุ่นของปีที่แล้วถ้ายังไม่ถึงมีนาคม
    }
}

// ฟังก์ชั่นแสดงตัวเลือกใน select box
function generateBatchOptions() {
    $currentBatch = calculateCurrentBatch();
    $options = "";

    for ($i = $currentBatch; $i >= 50; $i--) {
        $options .= "<option value=\"$i\">$i</option>";
    }

    return $options;
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

// ฟังก์ชั่นแปลงค่าสถานะนักศึกษาเป็นปุ่ม
function getStudentStatus($s_stat) {
    if ($s_stat == 1) {
        return "<button style='background-color: green; color: white; border: none; padding: 5px 10px; border-radius: 5px;'>ยังคงศึกษาอยู่</button>";
    } else {
        return "<button style='background-color: blue; color: white; border: none; padding: 5px 10px; border-radius: 5px;'>จบการศึกษาแล้ว</button>";
    }
}

// ฟังก์ชั่นคำนวณปีการศึกษา
function calculateAcademicYear($batch) {
    return 2500 + intval($batch);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูรายชื่อนักศึกษา</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: right;
        }
        .search-bar select, .search-bar input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-bar input[type="submit"] {
            background-color: rgb(232, 98, 1); /* เปลี่ยนสีปุ่มค้นหาเป็นสีส้ม */
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: rgb(186, 79, 1); /* เปลี่ยนสีปุ่มค้นหาเมื่อ hover เป็นสีส้มเข้ม */
        }
        .back-link {
            margin-top: 20px;
            text-align: center;
        }
        .back-link a {
            text-decoration: none;
            color: #007BFF;
            font-size: 16px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .academic-year {
            margin-top: 10px;
            text-align: left;
            font-size: 18px;
            color: green; /* เปลี่ยนสีของข้อความเป็นสีเขียว */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>รายชื่อนักศึกษา</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="ค้นหารหัสนักศึกษา, ชื่อ, นามสกุล" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select name="status">
                    <option value="">เลือกสถานะทั้งหมด</option>
                    <option value="1" <?php echo isset($_GET['status']) && $_GET['status'] == 1 ? 'selected' : ''; ?>>ยังคงศึกษาอยู่</option>
                    <option value="0" <?php echo isset($_GET['status']) && $_GET['status'] == 0 ? 'selected' : ''; ?>>จบการศึกษาแล้ว</option>
                </select>
                <select name="batch">
                    <option value="">เลือกรุ่นทั้งหมด</option>
                    <?php echo generateBatchOptions(); ?>
                </select>
                <input type="submit" value="ค้นหา">
            </form>
        </div>

        <!-- Table for displaying student data -->
        <table>
            <thead>
                <tr>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // คำสั่ง SQL เพื่อค้นหาข้อมูลนักศึกษา
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                $status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
                $batch = isset($_GET['batch']) ? $conn->real_escape_string($_GET['batch']) : '';

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

                if ($result->num_rows > 0) {
                    // แสดงข้อมูลนักศึกษา
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['s_id']) . "</td>";
                        echo "<td>" . getPrefix($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . " " . htmlspecialchars($row['s_la']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_email']) . "</td>";
                        echo "<td>" . getStudentStatus($row['s_stat']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลนักศึกษาที่ค้นหา</td></tr>";
                }
                ?>
            </tbody>
        </table>

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

        <div class="back-link">
            <a href="mainstd.php">กลับไปที่หน้าหลัก</a>
        </div>
    </div>
    <?php
    // ปิดการเชื่อมต่อ
    $conn->close();
    ?>
</body>
</html>
