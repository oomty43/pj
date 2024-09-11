<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';


// ฟังก์ชันสำหรับแปลงเดือนเป็นภาษาไทย โดยแสดงปีเป็น ค.ศ.
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
// เตรียมคำสั่ง SQL เพื่อดึงข้อมูลข่าวสาร
$sql = "SELECT i.i_id, i.i_head, i.a_id, it.itype_name, i.i_date
        FROM information i
        INNER JOIN info_type it ON i.itype_id = it.itype_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <title>Display Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังที่เข้ม */
            text-align: center;
            color: #fff; /* สีตัวอักษร */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }
        h2 {
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #555;
            padding: 12px;
            text-align: left;
            color: #fff; /* สีตัวอักษรในตาราง */
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #2a2a2a; /* สีพื้นหลังของแถวคู่ */
        }
        table tr:hover {
            background-color: #383838; /* สีพื้นหลังเมื่อเมาส์ชี้ */
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
            transition: background-color 0.3s ease;
        }
        .add-buttons a:hover {
            background-color: #45a049;
        }
        .action-buttons a {
            color: #ffa500; /* สีของลิงก์ */
            text-decoration: none;
            margin: 0 5px;
            transition: color 0.3s ease;
        }
        .action-buttons a:hover {
            color: #ff6347; /* สีของลิงก์เมื่อเมาส์ชี้ */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ข้อมูลข่าวประชาสัมพันธ์</h2>
        <div class="add-buttons">
            <a href="add_info_type.php">เพิ่มประเภทข่าว</a>
            <a href="add_information.php">เพิ่มข่าวประชาสัมพันธ์</a>
            <a href="mainadmin.php">หน้าหลัก</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>หัวข้อข่าว</th>
                    <th>ผู้เขียน</th>
                    <th>ประเภทข่าว</th>
                    <th>วันที่เผยแพร่</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $thai_date = thai_date($row['i_date']); 
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['i_head']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['a_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['itype_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($thai_date) . "</td>";
                        echo "<td class='action-buttons'>";
                        echo '<a href="view_information.php?id=' . $row['i_id'] . '">ดูรายละเอียด</a>';
                        echo '<a href="edit_information.php?id=' . $row['i_id'] . '">แก้ไข</a>';
                        echo '<a href="delete_information.php?id=' . $row['i_id'] . '">ลบ</a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลข่าว</td></tr>";
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
