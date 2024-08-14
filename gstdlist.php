<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// คำสั่ง SQL เพื่อดึงข้อมูลนักศึกษา
$sql = "SELECT s_id, s_pna, s_na, s_la, s_email, s_stat FROM student";
$result = $conn->query($sql);

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
function getStudentStatus($s_stat)
{
    return $s_stat == 1 ? "ยังคงศึกษาอยู่" : "จบการศึกษาแล้ว";
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
        .search-bar input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }
        .search-bar input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>รายชื่อนักศึกษา</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="ค้นหารหัสนักศึกษา, ชื่อ, นามสกุล" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <input type="submit" value="ค้นหา">
            </form>
        </div>

        <!-- Table for displaying student data -->
        <table>
            <thead>
                <tr>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อเต็ม</th>
                    <th>นามสกุล</th>
                    <th>อีเมล</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // คำสั่ง SQL เพื่อค้นหาข้อมูลนักศึกษา
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                $sql = "SELECT s_id, s_pna, s_na, s_la, s_email, s_stat 
                        FROM student 
                        WHERE s_id LIKE '%$search%' 
                        OR s_na LIKE '%$search%' 
                        OR s_la LIKE '%$search%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // แสดงข้อมูลนักศึกษา
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['s_id']) . "</td>";
                        echo "<td>" . getPrefix($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_la']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_email']) . "</td>";
                        echo "<td>" . getStudentStatus($row['s_stat']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่มีข้อมูลนักศึกษา</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="guestmain.php">กลับไปที่หน้าหลัก</a>
        </div>
    </div>
    <?php
    // ปิดการเชื่อมต่อ
    $conn->close();
    ?>
</body>
</html>
