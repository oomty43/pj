<?php
session_start(); // เริ่มต้น session

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['s_id'])) {
    header('Location: login.php'); // หากไม่ได้เข้าสู่ระบบ ให้กลับไปที่หน้า login
    exit();
}

// เชื่อมต่อฐานข้อมูล
try {
    $dsn = "mysql:host=localhost;dbname=project;charset=utf8mb4";
    $username = "root";
    $password = "";

    // Create a new PDO instance
    $conn = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// ตรวจสอบและรับข้อมูลจาก POST
$s_id = $_SESSION['s_id'];
$s_pna = $_POST['s_pna'];
$s_na = $_POST['s_na'];
$s_la = $_POST['s_la'];
$s_pws = $_POST['s_pws'];
$s_email = $_POST['s_email'];
$s_stat = $_POST['s_stat'];
$s_bloodtype = $_POST['s_bloodtype'];
$s_race = $_POST['s_race'];
$s_birth = $_POST['s_birth'];
$s_nationlity = $_POST['s_nationlity'];
$religious = $_POST['religious'];
$s_marriage = $_POST['s_marriage'];
$s_province = $_POST['s_province'];
$s_country = $_POST['s_country'];

// อัพโหลดรูปภาพ
if (isset($_FILES['s_pic']) && $_FILES['s_pic']['error'] == UPLOAD_ERR_OK) {
    $file_tmp_name = $_FILES['s_pic']['tmp_name'];
    $file_name = basename($_FILES['s_pic']['name']);
    $file_target = "uploads/" . $file_name;

    if (move_uploaded_file($file_tmp_name, $file_target)) {
        $s_pic = $file_name;
    } else {
        echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์.";
        exit();
    }
} else {
    // หากไม่เลือกไฟล์ใหม่ ให้ใช้ไฟล์เดิม
    $s_pic = $_POST['s_pic_old'];
}

// อัพเดทข้อมูลนักศึกษาในฐานข้อมูล
$sql = "UPDATE student SET s_pna = :s_pna, s_na = :s_na, s_la = :s_la, s_pws = :s_pws, s_email = :s_email, s_stat = :s_stat, s_bloodtype = :s_bloodtype, s_race = :s_race, s_birth = :s_birth, s_nationlity = :s_nationlity, religious = :religious, s_marriage = :s_marriage, s_province = :s_province, s_country = :s_country, s_pic = :s_pic WHERE s_id = :s_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':s_pna', $s_pna, PDO::PARAM_INT);
$stmt->bindParam(':s_na', $s_na, PDO::PARAM_STR);
$stmt->bindParam(':s_la', $s_la, PDO::PARAM_STR);
$stmt->bindParam(':s_pws', $s_pws, PDO::PARAM_STR);
$stmt->bindParam(':s_email', $s_email, PDO::PARAM_STR);
$stmt->bindParam(':s_stat', $s_stat, PDO::PARAM_INT);
$stmt->bindParam(':s_bloodtype', $s_bloodtype, PDO::PARAM_INT);
$stmt->bindParam(':s_race', $s_race, PDO::PARAM_STR);
$stmt->bindParam(':s_birth', $s_birth, PDO::PARAM_STR);
$stmt->bindParam(':s_nationlity', $s_nationlity, PDO::PARAM_STR);
$stmt->bindParam(':religious', $religious, PDO::PARAM_STR);
$stmt->bindParam(':s_marriage', $s_marriage, PDO::PARAM_INT);
$stmt->bindParam(':s_province', $s_province, PDO::PARAM_STR);
$stmt->bindParam(':s_country', $s_country, PDO::PARAM_STR);
$stmt->bindParam(':s_pic', $s_pic, PDO::PARAM_STR);
$stmt->bindParam(':s_id', $s_id, PDO::PARAM_STR);

if ($stmt->execute()) {
    // แสดง popup และกลับไปหน้าหลักหรือหน้าอื่นๆ
    echo "<script>
            alert('ทำการแก้ไขข้อมูลสำเร็จ');
            window.location.href = 'stdprofile.php';
          </script>";
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->errorInfo()[2];
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn = null;
?>
