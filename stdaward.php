<?php
// เริ่มต้น session
session_start(); 

include 'std_con.php';

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

// ดึงข้อมูลนักศึกษาจากฐานข้อมูล
$s_id = $_SESSION['s_id'];
$sql = "SELECT s_pic, s_pna, s_na, s_la FROM student WHERE s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $welcome_message = ": " . getPrefix($row["s_pna"]) . " " . $row["s_na"] . " " . $row["s_la"];
} else {
    $welcome_message = "ไม่พบข้อมูลนักศึกษา";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .banner {
            width: 100%;
            height: auto;
        }
        .nav-buttons {
            text-align: right;
            margin: 10px;
        }
        .nav-buttons a {
            padding: 10px 20px;
            background-color: #D35400; /* สีปุ่มปกติ */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .nav-buttons a:hover {
            background-color: #E07B00; /* สีปุ่มเมื่อ hover */
        }

        .center-text {
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            color: #333;
        }
        .form-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="radio"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .form-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }
        .btn-save,
        .btn-cancel {
            display: inline-block;
            width: 48%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .welcome-message {
            margin: 20px;
            font-size: 20px;
            color: #333;
            text-align: right; /* ทำให้ข้อความชิดขวา */
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-edit {
            background-color: #ffc107;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-add:hover {
            background-color: #218838;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/banner1.jpg" alt="Banner" class="banner">

    <div class="nav-buttons">
        <a href="mainstd.php">หน้าหลัก</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="logout.php">ออกจากระบบ</a>
    </div>
    <!-- แสดงข้อความต้อนรับ -->
    <div class="welcome-message">
        <?php echo $welcome_message; ?>
    </div>

    <div class="form-container">   
        <div id="courseTable">
            <h2>การเข้าอบรม (Course)</h2>
            <table>
                <tr>
                    <th>ชื่อโครงการอบรม </th>
                    <th>ชื่อสถานที่อบรม </th>
                    <th>ปีที่อบรม </th>
                    <th>การจัดการ</th>
                </tr>
                <?php
                $sql = "SELECT c.c_id, c.c_na, c.c_add,c.c_date
                        FROM course c
                        WHERE c.s_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $s_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['c_na']}</td>";
                    echo "<td>{$row['c_add']}</td>";
                    echo "<td>{$row['c_date']}</td>";
                    echo "<td>";
                    echo "<a class='btn-edit' href='edit_course.php?c_id={$row['c_id']}'>แก้ไข</a>";
                    echo "<a class='btn-delete' href='delete_course.php?c_id={$row['c_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <a href="add_course.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->

        <h2>ทักษะพิเศษ (Skill)</h2>
        <table>
            <tr>
                <th>ทักษะ </th>
                <th>การจัดการ</th>
            </tr>
            <?php
            $sql = "SELECT sk.sk_id, sk.sk_na
                    FROM skill sk
                    WHERE sk.s_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $s_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['sk_na']}</td>";
                echo "<td>";
                echo "<a class='btn-edit' href='edit_skill.php?sk_id={$row['sk_id']}'>แก้ไข</a>";
                echo "<a class='btn-delete' href='delete_skill.php?sk_id={$row['sk_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="add_skill.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->

        
        <h2>ประวัติการฝึกงาน (Internship History)</h2>
        <table>
    <tr>
        <th>สถานที่ฝึกงาน </th>
        <th>จังหวัด</th>
        <th>ปีที่ฝึกงาน </th>
        <th>โปรเจคฝึกงาน</th>
        <th>การจัดการ</th>
    </tr>
    <?php
    $sql = "SELECT its.its_id, its.its_name, its.its_province, its.its_date, its.its_file
            FROM its_history its
            WHERE its.s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $s_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['its_name']}</td>";
        echo "<td>{$row['its_province']}</td>";
        echo "<td>{$row['its_date']}</td>";
        echo "<td><a href='{$row['its_file']}'>ดาวน์โหลด</a></td>";
        echo "<td>";
        echo "<a class='btn-edit' href='edit_intern.php?its_id={$row['its_id']}'>แก้ไข</a>";
        echo " ";
        echo "<a class='btn-delete' href='delete_intern.php?its_id={$row['its_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
        <a href="add_intern.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->
        <style>
    .btn-edit {
        margin-right: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
    }
</style>
        <h2>การเขียนภาษาโปรแกรม (Programming Skills)</h2>
        <table>
            <tr>
                <th>ทักษะภาษาโปรแกรม</th>
                <th>การจัดการ</th>
            </tr>
            <?php
            $sql = "SELECT pg.pg_id, pg.pg_na
                    FROM program pg
                    WHERE pg.s_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $s_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['pg_na']}</td>";
                echo "<td>";
                echo "<a class='btn-edit' href='edit_program.php?pg_id={$row['pg_id']}'>แก้ไข</a>";
                echo "<a class='btn-delete' href='delete_program.php?pg_id={$row['pg_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="add_program.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->

        <h2>การทำงาน (Work History)</h2>
        <table>
            <tr>
                <th>สถานที่ทำงาน </th>
                <th>ปีที่ทำงาน </th>
                <th>การจัดการ</th>
            </tr>
            <?php
            $sql = "SELECT w.w_id, w.w_na, w.w_date
                    FROM wk w
                    WHERE w.s_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $s_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['w_na']}</td>";
                echo "<td>{$row['w_date']}</td>";
                echo "<td>";
                echo "<a class='btn-edit' href='edit_work.php?w_id={$row['w_id']}'>แก้ไข</a>";
                echo "<a class='btn-delete' href='delete_work.php?w_id={$row['w_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="add_work.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->

        <h2>ใบรับรอง (Certificates)</h2>
        <table>
            <tr>
                <th>ชื่อใบรับรอง </th>
                <th>หน่วยงานที่รับรอง </th>
                <th>ปีที่ได้รับ </th>
                <th>เอกสารแนบ </th>
                <th>การจัดการ</th>
            </tr>
            <?php
            $sql = "SELECT ce.ce_id, ce.ce_na, ce.og_na, ce.ce_year, ce.ce_file
                    FROM certi ce
                    WHERE ce.s_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $s_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['ce_na']}</td>";
                echo "<td>{$row['og_na']}</td>";
                echo "<td>{$row['ce_year']}</td>";
                echo "<td><a href='{$row['ce_file']}'>ดาวน์โหลด</a></td>";
                echo "<td>";
                echo "<a class='btn-edit' href='edit_cert.php?ce_id={$row['ce_id']}'>แก้ไข</a>";
                echo "<a class='btn-delete' href='delete_cert.php?ce_id={$row['ce_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="add_cert.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->

        <h2>กิจกรรม (Events)</h2>
        <table>
            <tr>
                <th>กิจกรรม </th>
                <th>สถานที่จัดกิจกรรม </th>
                <th>ปี </th>
                <th>การจัดการ</th>
            </tr>
            <?php
            $sql = "SELECT e.e_id, e.e_na, e.e_add, e.e_date
                    FROM ev e
                    WHERE e.s_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $s_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['e_na']}</td>";
                echo "<td>{$row['e_add']}</td>";
                echo "<td>{$row['e_date']}</td>";
                echo "<td>";
                echo "<a class='btn-edit' href='edit_event.php?e_id={$row['e_id']}'>แก้ไข</a>";
                echo "<a class='btn-delete' href='delete_event.php?e_id={$row['e_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="add_event.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->

        <h2>ประวัติการศึกษา (Education History)</h2>
        <table>
            <tr>
                <th>สถานที่ศึกษา </th>
                <th>ระดับการศึกษา </th>
                <th>ปีที่จบการศึกษา </th>
                <th>การจัดการ</th>
            </tr>
            <?php
            $sql = "SELECT eh.eh_id, eh.eh_na, eh.eh_level, eh.eh_end
                    FROM edu_history eh
                    WHERE eh.s_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $s_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['eh_na']}</td>";
                echo "<td>{$row['eh_level']}</td>";
                echo "<td>{$row['eh_end']}</td>";
                echo "<td>";
                echo "<a class='btn-edit' href='edit_eh.php?eh_id={$row['eh_id']}'>แก้ไข</a>";
                echo "<a class='btn-delete' href='delete_eh.php?eh_id={$row['eh_id']}' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\");'>ลบ</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="add_eh.php"><button class="btn-add">เพิ่มข้อมูล</button></a> <!-- ปุ่มเพิ่ม -->
    </div>
    

        

</body>
</html>

<?php
$conn->close();
?>
