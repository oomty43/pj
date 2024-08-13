

<?php
session_start();
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ MySQL
$password = "";      // รหัสผ่าน MySQL (ถ้ามี)
$dbname = "project"; // ชื่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
if(!isset($_SESSION['a_user'])){
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
    $i_date = $_POST['i_date'];
    $itype_id = $_POST['itype_id'];
    $a_user = $_POST['a_user'];
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

    // เชื่อมต่อกับฐานข้อมูล


    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูล
    $sql = "INSERT INTO information (i_head, i_deltail, i_cover, itype_id,i_date,a_id)
            VALUES ('$i_head', '$i_deltail', '$i_cover', '$itype_id', '$i_date','$a_user')";

    if ($conn->query($sql) === TRUE) {

        echo "<script>
        alert('บันทึกข้อมูลเรียบร้อยแล้ว');
      
      </script>";

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

   
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type=text],
        textarea,
        input[type=date],
        input[type=file] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type=submit]:hover {
            background-color: #45a049;
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
            <?php $sql = "select * from info_type";
            $result = $conn->query($sql);

            ?>
            <select name="itype_id" id="">
                <?php
                foreach ($result as $row):
                    ?>
                    <option value="<?php echo $row['itype_id'] ?>"><?php echo $row['itype_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="i_date">วันที่:</label>
            <input type="date" id="i_date" name="i_date" required>
            <input type="hidden" name="a_user" value="<?php echo $_SESSION['a_user']; ?>">
            <input type="submit" value="เพิ่มข้อมูล">
        </form>
    </div>
</body>

</html>