<?php
session_start();
// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if (!isset($_SESSION['a_user'])) {
    echo "
        <script>
            alert('กรุณา login');
            window.location='loginadmin.php';
        </script>
    ";
}

// ตรวจสอบการส่งข้อมูล POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตัวแปรที่รับค่าจากฟอร์ม
    $i_head = $_POST['i_head'];
    $i_deltail = $_POST['i_deltail'];
    $itype_id = $_POST['itype_id'];
    $a_user = $_POST['a_user'];

    // ดึงวันที่ปัจจุบัน
    $i_date = date("Y-m-d H:i:s"); // รูปแบบเวลาจะเป็นปี-เดือน-วัน ชั่วโมง:นาที:วินาที

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
    if ($_FILES['i_cover']['name']) {
        $i_cover = $_FILES['i_cover']['name'];
        $i_cover_tmp = $_FILES['i_cover']['tmp_name'];

        // อัปโหลดไฟล์รูปภาพ
        $upload_directory = "uploads/";
        move_uploaded_file($i_cover_tmp, $upload_directory . $i_cover);
    } else {
        $i_cover = ""; // ถ้าไม่ได้อัปโหลดรูปภาพ
    }

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูล
    $sql = "INSERT INTO information (i_head, i_deltail, i_cover, itype_id, i_date, a_id)
            VALUES ('$i_head', '$i_deltail', '$i_cover', '$itype_id', '$i_date','$a_user')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('บันทึกข้อมูลเรียบร้อยแล้ว');
            window.location = 'display_information.php'; // พากลับไปยังหน้าจัดการข้อมูล
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Add Information</title>
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
            width: 50%;
            max-width: 600px;
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type=text],
        textarea,
        input[type=file],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #555;
            border-radius: 4px;
            background-color: #222; /* สีพื้นหลังของ input */
            color: #fff; /* สีตัวอักษรใน input */
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
        }

        input[type=submit],
        .cancel-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 45%; /* ขนาดปุ่ม */
            text-align: center;
            text-decoration: none;
        }

        input[type=submit] {
            margin-right: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .cancel-button {
            background-color: #FF6347; /* สีของปุ่มยกเลิก */
            margin-left: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }

        .cancel-button:hover {
            background-color: #FF4500; /* สีของปุ่มยกเลิกเมื่อเมาส์ชี้ */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>เพิ่มข่าวสาร</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="i_head">หัวข้อข่าวสาร:</label>
            <input type="text" id="i_head" name="i_head" required>

            <label for="i_deltail">รายละเอียด:</label>
            <textarea id="i_deltail" name="i_deltail" rows="4" required></textarea>

            <label for="i_cover">รูปปก:</label>
            <input type="file" id="i_cover" name="i_cover" accept="image/*" required>

            <?php 
            $sql = "SELECT * FROM info_type";
            $result = $conn->query($sql);
            ?>
            <label for="itype_id">ประเภทข่าวสาร:</label>
            <select name="itype_id" id="itype_id" required>
                <?php foreach ($result as $row): ?>
                    <option value="<?php echo $row['itype_id']; ?>"><?php echo $row['itype_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="a_user" value="<?php echo $_SESSION['a_user']; ?>">

            <div class="button-group">
                <input type="submit" value="เพิ่มข้อมูล">
                <a href="display_information.php" class="cancel-button">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>
