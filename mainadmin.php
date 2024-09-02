<?php 
session_start(); 
if (!isset($_SESSION['a_user'])) {
    echo "
        <script>
            alert('กรุณาเข้าสู่ระบบ');
            window.location='loginadmin.php';
        </script>
    ";
}

// ตรวจสอบว่ามีการส่งคำสั่งออกจากระบบหรือไม่
if (isset($_GET['logout'])) {
    session_destroy(); // ทำลาย session
    header("Location: loginadmin.php"); // กลับไปยังหน้าเข้าสู่ระบบ
    exit();
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดผู้ดูแลระบบ</title>
    <style>
        body {
            font-family: Arial, sans-serif; 
            background-color: #121212; /* พื้นหลังสีดำ */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #1e1e1e; /* พื้นหลังกล่องสีเทาเข้ม */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            width: 300px;
            text-align: center;
        }
        h2 {
            color: #fbc02d; /* สีเหลือง */
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #fff; /* สีตัวอักษรปกติ */
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        a.btn-manage {
            background-color: #4CAF50; /* ปุ่มสีเขียว */
        }
        a.btn-manage:hover {
            background-color: #388E3C; /* สีเขียวเข้มเมื่อโฮเวอร์ */
        }
        a.btn-info {
            background-color: #2196F3; /* ปุ่มสีน้ำเงิน */
            color: #fff; /* ตัวอักษรสีขาว */
        }
        a.btn-info:hover {
            background-color: #1976D2; /* สีน้ำเงินเข้มเมื่อโฮเวอร์ */
        }
        a.btn-student {
            background-color: #FF5722; /* ปุ่มสีส้ม */
        }
        a.btn-student:hover {
            background-color: #E64A19; /* สีส้มเข้มเมื่อโฮเวอร์ */
        }
        a.btn-logout {
            background-color: #dc3545; /* สีแดงสำหรับปุ่มออกจากระบบ */
        }
        a.btn-logout:hover {
            background-color: #c82333; /* สีแดงเข้มเมื่อโฮเวอร์ */
        }
    </style>
    <script>
        function checkAccess() {
            var status = <?php echo $_SESSION['ast']; ?>;
            if (status == 1) {
                alert('คุณไม่มีสิทธิ์เข้าถึง');
                return false; // ยกเลิกการเปลี่ยนหน้า
            }
            return true; // อนุญาตให้เข้าถึง
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Menu Admin</h2>
        <ul>
            <li><a href="display_admin.php" class="btn-manage" onclick="return checkAccess();">จัดการข้อมูลผู้ดูแลระบบ</a></li>
            <li><a href="display_information.php" class="btn-info">จัดการข่าวประชาสัมพันธ์</a></li>
            <li><a href="display_student.php" class="btn-student">จัดการข้อมูลนักศึกษา</a></li>
            <li><a href="?logout=true" class="btn-logout">ออกจากระบบ</a></li> <!-- ปุ่มออกจากระบบ -->
        </ul>
    </div>
</body>
</html>
