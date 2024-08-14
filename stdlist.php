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
                if ($result->num_rows > 0) {
                    // แสดงข้อมูลนักศึกษา
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['s_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_la']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_stat']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่มีข้อมูลนักศึกษา</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
