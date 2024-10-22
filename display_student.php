<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// ฟังก์ชันสำหรับลบข้อมูลนักศึกษา
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // ตรวจสอบว่า a_user อยู่ใน session
    if (isset($_SESSION['a_user'])) {
        $a_user = $_SESSION['a_user']; // ดึงข้อมูล a_user จาก session

        // ลบข้อมูลนักศึกษา
        $sql = "DELETE FROM student WHERE s_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $delete_id);
        if ($stmt->execute()) {
            // บันทึกการกระทำลง admin_logs โดยใช้ a_user เป็น a_id
            $action_type = 'ลบข้อมูล';
            $log_sql = "INSERT INTO admin_logs (a_id, action_type, student_id) VALUES (?, ?, ?)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("sss", $a_user, $action_type, $delete_id);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='display_student.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('ไม่สามารถลบข้อมูลได้เนื่องจากไม่มีข้อมูล a_user'); window.location='display_student.php';</script>";
    }
}

// ฟังก์ชันค้นหา
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
}

// กำหนดจำนวนรายการที่จะแสดงต่อหน้า
$items_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// ดึงข้อมูลจากตาราง student พร้อมการแบ่งหน้า
$sql = "SELECT s_id, s_pna, s_na, s_la, s_email, s_stat 
        FROM student 
        WHERE s_id LIKE ? OR s_na LIKE ? OR s_la LIKE ?
        LIMIT ? OFFSET ?";
$search_param = "%" . $search_query . "%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $search_param, $search_param, $search_param, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// ดึงจำนวนข้อมูลทั้งหมดเพื่อใช้ในการคำนวณหน้าทั้งหมด
$sql_total = "SELECT COUNT(*) FROM student WHERE s_id LIKE ? OR s_na LIKE ? OR s_la LIKE ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("sss", $search_param, $search_param, $search_param);
$stmt_total->execute();
$stmt_total->bind_result($total_items);
$stmt_total->fetch();
$total_pages = ceil($total_items / $items_per_page);

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
    if ($s_stat == 1) {
        return "<span class='btn-status green'>ยังคงศึกษาอยู่</span>"; // ปุ่มสีเขียว
    } elseif ($s_stat == 0) {
        return "<span class='btn-status blue'>จบการศึกษาแล้ว</span>"; // ปุ่มสีน้ำเงิน
    } elseif ($s_stat == 2) {
        return "<span class='btn-status gray'>ยังไม่ได้จัดการข้อมูล</span>"; // ปุ่มสีเทา
    } else {
        return "<span class='btn-status red'>ข้อมูลไม่ถูกต้อง</span>"; // ปุ่มสีแดง
    }
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <title>ข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังที่เข้ม */
            color: #fff; /* สีตัวอักษร */
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #555; /* ขอบของเซลล์ */
            padding: 12px;
            text-align: left;
            color: #fff; /* สีตัวอักษรในตาราง */
        }
        table th {
            background-color: #4CAF50; /* สีพื้นหลังของหัวข้อ */
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #2a2a2a; /* สีพื้นหลังของแถวคู่ */
        }
        table tr:hover {
            background-color: #383838; /* สีพื้นหลังเมื่อเมาส์ชี้ */
        }
        .add-buttons, .search-bar {
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
        .search-bar {
            text-align: right;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            margin-right: 5px;
        }
        .search-bar input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #45a049;
        }
        .btn-edit {
            background-color: #ffeb3b; /* ปรับสีปุ่มแก้ไขเป็นสีเหลือง */
            color: black; /* เปลี่ยนสีตัวอักษรให้เป็นสีดำ */
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }
        .btn-edit:hover {
            background-color: #fdd835; /* สีเมื่อเมาส์ชี้ที่ปุ่มแก้ไข */
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .btn-status {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }
        .green {
            background-color: #28a745; /* สีเขียว */
        }
        .blue {
            background-color: #007bff; /* สีน้ำเงิน */
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .gray {
            background-color: #808080; /* สีเทา */
        }

        .red {
            background-color: #ff0000; /* สีแดง */
        }
        .pagination a {
            color: #ffffff; /* สีของลิงก์ */
            text-decoration: none;
            margin: 0 5px;
        }

        .pagination span {
            color: #ffa500; /* สีของหน้าปัจจุบัน */
            margin: 0 5px;
        }

        .pagination a:hover {
            text-decoration: underline; /* ใส่ขีดเส้นใต้เมื่อ hover */
        }

        /* เพิ่มสไตล์สำหรับปุ่ม Log */
        .btn-log {
            background-color: #f39c12;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .btn-log:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ข้อมูลนักศึกษา</h2>
        <div class="add-buttons">
            <a href="add_student.php">เพิ่มนักศึกษา</a>
            <a href="logdetail.php" class="btn-log">เช็ค Log</a> <!-- ลิงก์เช็ค Log -->
            <a href="mainadmin.php">หน้าหลัก</a>
          
        </div>
        <!-- ปุ่มอัปโหลดไฟล์ Excel -->
        <form action="upload_excel.php" method="post" enctype="multipart/form-data" style="display:inline;">
            <input type="file" name="excel_file" accept=".xlsx, .xls" required>
            <input type="submit" value="เพิ่มนักศึกษาจาก Excel" name="upload_excel" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        </form>
        <!-- ปุ่มดาวน์โหลดคู่มือการใช้ -->
        <a href="uploads/คู่มือการใช้.pdf" download style="background-color: #ff5722; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-top: 10px; display: inline-block;">
            คู่มือการใช้
        </a>

        <!-- ปุ่มดาวน์โหลดไฟล์ตัวอย่าง -->
        <a href="uploads/ตัวอย่างรายชื่อนักศึกษา.xlsx" download style="background-color: #ff9800; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-top: 10px; display: inline-block;">
            ไฟล์ตัวอย่างรายชื่อนักศึกษา
        </a>

        <div class="search-bar">
            <form method="post">
                <input type="text" name="search_query" placeholder="ค้นหารหัสนักศึกษา, ชื่อ, นามสกุล" value="<?php echo htmlspecialchars($search_query); ?>">
                <input type="submit" value="ค้นหา" name="search">
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล์</th>
                    <th>สถานะนักศึกษา</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['s_id']) . "</td>";
                        echo "<td>" . getPrefix($row['s_pna']) . " " . htmlspecialchars($row['s_na']) . " " . htmlspecialchars($row['s_la']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['s_email']) . "</td>";
                        echo "<td>" . getStudentStatus($row['s_stat']) . "</td>";
                        echo "<td>
                                <a href='edit_student.php?s_id=" . htmlspecialchars($row['s_id']) . "' class='btn-edit'>แก้ไข</a>
                                <a href='display_student.php?delete_id=" . htmlspecialchars($row['s_id']) . "' class='btn-delete' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?\")'>ลบ</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลนักศึกษา</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<span>$i</span>"; // หน้าปัจจุบันเป็นแค่ตัวเลขไม่มีลิงก์
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
                if ($i < $total_pages) {
                    echo " | "; // ใส่ตัวแบ่งระหว่างลิงก์
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
