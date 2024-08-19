<?php
session_start();

include 'std_con.php';

// รับค่าการค้นหาจากฟอร์ม
$search = isset($_POST['search']) ? $_POST['search'] : '';
$statusFilter = isset($_POST['status']) ? $_POST['status'] : '';

// คำสั่ง SQL เพื่อดึงข้อมูลนักศึกษา
$sql = "SELECT s_id, s_pna, s_na, s_la, s_email, s_stat FROM student WHERE (s_id LIKE ? OR s_na LIKE ? OR s_la LIKE ?)";
if ($statusFilter !== '') {
    $sql .= " AND s_stat = ?";
}
$stmt = $conn->prepare($sql);
$searchParam = '%' . $search . '%';

if ($statusFilter !== '') {
    $stmt->bind_param('ssss', $searchParam, $searchParam, $searchParam, $statusFilter);
} else {
    $stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();

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
            background-color: #f0f0f0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f8f8;
        }
        .back-link {
            margin-top: 20px;
            text-align: center;
        }
        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .search-form {
            margin-bottom: 20px;
            text-align: right;
        }
        .search-form form {
            display: inline-block;
        }
        .search-form input[type="text"],
        .search-form select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            margin-right: 10px;
            background-color: #ffffff;
            color: #333;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>รายชื่อนักศึกษา</h2>

        <!-- ฟอร์มค้นหา -->
        <div class="search-form">
            <form method="post">
                <input type="text" name="search" placeholder="ค้นหาตามรหัสนักศึกษา, ชื่อ, นามสกุล" value="<?php echo htmlspecialchars($search); ?>">
                <select name="status">
                    <option value="">-- เลือกสถานะ --</option>
                    <option value="1" <?php echo ($statusFilter === '1') ? 'selected' : ''; ?>>ยังคงศึกษาอยู่</option>
                    <option value="0" <?php echo ($statusFilter === '0') ? 'selected' : ''; ?>>จบการศึกษาแล้ว</option>
                </select>
                <input type="submit" value="ค้นหา">
            </form>
        </div>

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
            <a href="mainstd.php">กลับไปที่หน้าหลัก</a>
        </div>
    </div>
    <?php
    // ปิดการเชื่อมต่อ
    $conn->close();
    ?>
</body>
</html>
