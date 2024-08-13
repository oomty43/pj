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

// ฟังก์ชันสำหรับลบข้อมูลนักศึกษา
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM student WHERE s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว'); window.location='display_student.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

// ดึงข้อมูลจากตาราง student
$sql = "SELECT s_id, s_pic, s_pna, s_na, s_la, s_email, s_stat FROM student";
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
?>
<?php
// ฟังก์ชั่นแปลงค่าสถานะนักศึกษา
function getStudentStatus($s_stat)
{
    return $s_stat == 1 ? "ยังคงศึกษาอยู่" : "จบการศึกษาแล้ว";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ข้อมูลนักศึกษา</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
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
        .btn-edit {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }
        .btn-edit:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>ข้อมูลนักศึกษา</h2>
        <div class="add-buttons">
            <a href="add_student.php">เพิ่มนักศึกษา</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>รหัสนักศึกษา</th>
                    <th>รูปภาพ</th>
                    <th>คำนำหน้า</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
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
                        echo "<td>" . $row['s_id'] . "</td>";
                        echo "<td><img src='" . $row['s_pic'] . "' alt='Student Picture'></td>";
                        echo "<td>" . getPrefix($row['s_pna']) . "</td>";
                        echo "<td>" . $row['s_na'] . "</td>";
                        echo "<td>" . $row['s_la'] . "</td>";
                        echo "<td>" . $row['s_email'] . "</td>";
                        echo "<td>" . getStudentStatus($row['s_stat']) . "</td>";


                        echo "<td>
                                <a href='edit_student.php?s_id=" . $row['s_id'] . "' class='btn-edit'>แก้ไข</a>
                                <a href='display_student.php?delete_id=" . $row['s_id'] . "' class='btn-delete' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?\")'>ลบ</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>ไม่พบข้อมูลนักศึกษา</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
