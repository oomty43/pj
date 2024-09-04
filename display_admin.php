<?php
// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง admin
$sql = "SELECT a_user, a_na, a_la, a_email, a_st FROM admin";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังที่เข้ม */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
            width: 90%;
            max-width: 600px; /* จำกัดขนาดความกว้าง */
            color: #fff; /* สีตัวอักษร */
        }
        h2 {
            text-align: center;
            color: #ffa500; /* สีของหัวข้อ */
        }
        a.button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s ease; /* เพิ่มการเคลื่อนไหว */
        }
        a.button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #555; /* สีของเส้นตาราง */
        }
        th, td {
            padding: 12px;
            text-align: left;
            color: #ddd; /* สีตัวอักษรในตาราง */
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #2a2a2a; /* สีพื้นหลังของแถวคู่ */
        }
        tr:hover {
            background-color: #383838; /* สีพื้นหลังเมื่อเมาส์ชี้ */
        }
        .actions a {
            color: #ffa500; /* สีของลิงก์ */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .actions a:hover {
            color: #ff6347; /* สีของลิงก์เมื่อเมาส์ชี้ */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>รายชื่อผู้ดูแลระบบ</h2>
        <a href="add_admin.php" class="button">เพิ่มข้อมูลผู้ดูแลระบบ</a>
        <a href="mainadmin.php" class="button">หน้าหลัก</a>
        <table>
            <tr>
                <th>ชื่อผู้ใช้</th>
                <th>ชื่อ-นามสกุล</th>
                <th>อีเมล์</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                // แสดงข้อมูลในตาราง
                while($row = $result->fetch_assoc()) {
                    // กำหนดชื่อสถานะจาก a_st
                    $status = ($row["a_st"] == 1) ? "เจ้าหน้าที่" : 
                              (($row["a_st"] == 2) ? "อาจารย์" : "ผู้ดูแลระบบ");
                    
                    echo "<tr>";
                    echo "<td>" . $row["a_user"] . "</td>";
                    echo "<td>" . $row["a_na"] . " " . $row["a_la"] . "</td>"; // แสดงชื่อและนามสกุล
                    echo "<td>" . $row["a_email"] . "</td>";
                    echo "<td>" . $status . "</td>"; // แสดงสถานะ
                    echo "<td class='actions'>
                            <a href='edit_admin.php?a_user=" . $row["a_user"] . "'>แก้ไข</a> |
                            <a href='delete_admin.php?a_user=" . $row["a_user"] . "' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>ไม่พบข้อมูล</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
