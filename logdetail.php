<?php
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบแล้วหรือไม่
if (!isset($_SESSION['a_user'])) {
    echo "<script>alert('กรุณาล็อกอินก่อน'); window.location='loginadmin.php';</script>";
    exit();
}

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// ตรวจสอบสถานะการเข้าถึง
$a_st = $_SESSION['a_st']; // ดึงค่า a_st จาก session

// ฟังก์ชันแปลงเดือนเป็นภาษาไทย
function convertDateToThai($date) {
    $months = array(
        "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน",
        "05" => "พฤษภาคม", "06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม",
        "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
    );

    $year = date("Y", strtotime($date));
    $month = date("m", strtotime($date));
    $day = date("d", strtotime($date));

    $thai_month = $months[$month];

    return "$day $thai_month $year";
}

function convertTime($date) {
    return date("H:i:s", strtotime($date));
}

// กำหนดจำนวนรายการที่จะแสดงต่อหน้า
$items_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// ดึงข้อมูลจำนวนทั้งหมดสำหรับการแบ่งหน้า
$sql_total = "SELECT COUNT(*) FROM admin_logs";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_row();
$total_items = $row_total[0];
$total_pages = ceil($total_items / $items_per_page);

// ดึงข้อมูลจากตาราง admin_logs และเรียงตามวันที่ล่าสุด โดยแบ่งหน้า
$sql = "SELECT log_id, a_id, action_type, student_id, timestamp 
        FROM admin_logs 
        ORDER BY timestamp DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// ฟังก์ชันจัดการการลบ
$delete_mode = false;
if (isset($_POST['toggle_delete'])) {
    $delete_mode = true; // เปิดโหมดลบ
}

// ถ้ามีการลบข้อมูล
if (isset($_POST['confirm_delete'])) {
    if (isset($_POST['delete_ids'])) {
        $delete_ids = implode(",", $_POST['delete_ids']);
        $delete_sql = "DELETE FROM admin_logs WHERE log_id IN ($delete_ids)";
        $conn->query($delete_sql);
        echo "<script>alert('ลบประวัติเรียบร้อยแล้ว'); window.location='logdetail.php';</script>";
    }
}

// ลบประวัติทั้งหมด
if (isset($_POST['delete_all'])) {
    $conn->query("DELETE FROM admin_logs");
    echo "<script>alert('ลบประวัติทั้งหมดเรียบร้อยแล้ว'); window.location='logdetail.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดบันทึกการกระทำ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #ffffff;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            background-color: #333333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #ffa500;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #555;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #4CAF50;
            color: #ffffff;
        }
        table tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        table tr:hover {
            background-color: #383838;
        }
        .btn {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #1976d2;
        }
        .btn.delete {
            background-color: #e74c3c;
        }
        .btn.delete-all {
            background-color: #e67e22;
        }
        .btn:hover.delete {
            background-color: #c0392b;
        }
        .btn:hover.delete-all {
            background-color: #d35400;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            color: #ffffff;
            padding: 10px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #007bff;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        .pagination span {
            color: #ffa500;
            padding: 10px;
            margin: 0 5px;
            background-color: #4CAF50;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ประวัติเเก้ไขข้อมูลนักศึกษา</h2>
        <form method="post" action="">
            <table>
                <thead>
                    <tr>
                        <?php if ($delete_mode && ($a_st == 2 || $a_st == 3)): ?>
                            <th>เลือก</th>
                        <?php endif; ?>
                        <th>วันที่</th>
                        <th>เวลา</th>
                        <th>ผู้ดูแลระบบ</th>
                        <th>การกระทำ</th>
                        <th>รหัสนักศึกษาที่แก้ไข</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            if ($delete_mode && ($a_st == 2 || $a_st == 3)) {
                                echo "<td><input type='checkbox' name='delete_ids[]' value='{$row['log_id']}'></td>";
                            }
                            echo "<td>" . convertDateToThai($row['timestamp']) . "</td>";
                            echo "<td>" . convertTime($row['timestamp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['a_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['action_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>ไม่พบบันทึกการกระทำ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- แสดงปุ่มล้างประวัติเมื่อ a_st เป็น 2 หรือ 3 -->
            <?php if ($a_st == 2 || $a_st == 3): ?>
                <?php if ($delete_mode): ?>
                    <input type="submit" name="confirm_delete" value="ยืนยันการลบ" class="btn delete">
                <?php else: ?>
                    <input type="submit" name="toggle_delete" value="ลบ" class="btn delete">
                <?php endif; ?>
                <input type="submit" name="delete_all" value="ลบทั้งหมด" class="btn delete-all">
            <?php endif; ?>
        </form>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            if ($total_pages > 1) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span>$i</span>";
                    } else {
                        echo "<a href='logdetail.php?page=$i'>$i</a>";
                    }
                }
            }
            ?>
        </div>

        <a href="display_student.php" class="btn">ย้อนกลับ</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
