<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ MySQL
$password = "";      // รหัสผ่าน MySQL (ถ้ามี)
$dbname = "project"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// เตรียมคำสั่ง SQL เพื่อดึงข้อมูลข่าวสาร
$sql = "SELECT i.i_id, i.i_head, i.a_id, it.itype_name, i.i_date
        FROM information i
        INNER JOIN info_type it ON i.itype_id = it.itype_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Display Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
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
        .add-buttons {
            margin-bottom: 20px;
        }
        .add-buttons a {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .add-buttons a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ข้อมูลข่าวสาร</h2>
        <div class="add-buttons">
            <a href="add_information.php">เพิ่มข่าวสาร</a>
            <a href="add_info_type.php">เพิ่มประเภทข่าวสาร</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>หัวข้อข่าว</th>
                    <th>ผู้เขียน</th>
                    <th>ประเภทข่าว</th>
                    <th>วันที่ลงข่าว</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['i_head'] . "</td>";
                        echo "<td>" . $row['a_id'] . "</td>";
                        echo "<td>" . $row['itype_name'] . "</td>";
                        echo "<td>" . $row['i_date'] . "</td>";
                        echo "<td>";
                        echo '<a href="edit_information.php?id=' . $row['i_id'] . '">แก้ไข</a>';
                        echo ' | ';
                        echo '<a href="delete_information.php?id=' . $row['i_id'] . '">ลบ</a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลข่าวสาร</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
