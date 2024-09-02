<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (isset($_GET['id'])) {
    $i_id = $_GET['id'];

    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลข่าวสารตาม id
    $sql = "SELECT i.i_head, i.i_deltail, i.itype_id, i.i_date, i.i_cover
            FROM information i
            WHERE i.i_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $i_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $i_head = $row['i_head'];
        $i_deltail = $row['i_deltail'];
        $itype_id = $row['itype_id'];
        $i_cover = $row['i_cover'];
        $i_date = $row['i_date']; // เก็บวันที่เดิมไว้
    } else {
        echo "ไม่พบข้อมูลข่าวสาร";
        exit();
    }
} else {
    echo "ไม่มี id ที่ต้องการแก้ไข";
    exit();
}

// ตรวจสอบว่ามีการส่งฟอร์มมาเพื่อแก้ไขข้อมูลหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $i_head = $_POST['i_head'];
    $i_deltail = $_POST['i_deltail'];
    $itype_id = $_POST['itype_id'];

    // ตรวจสอบและจัดการไฟล์ภาพปกที่อัปโหลด
    if (isset($_FILES['i_cover']) && $_FILES['i_cover']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["i_cover"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบว่าเป็นไฟล์ภาพหรือไม่
        $check = getimagesize($_FILES["i_cover"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["i_cover"]["tmp_name"], $target_file)) {
                $i_cover = $target_file;
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
                $i_cover = null;
            }
        } else {
            echo "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ";
            $i_cover = null;
        }
    }

    // อัปเดตข้อมูลในฐานข้อมูล โดยไม่เปลี่ยนแปลงวันที่
    if ($i_cover) {
        $sql = "UPDATE information SET i_head = ?, i_deltail = ?, itype_id = ?, i_cover = ? WHERE i_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $i_head, $i_deltail, $itype_id, $i_cover, $i_id);
    } else {
        $sql = "UPDATE information SET i_head = ?, i_deltail = ?, itype_id = ? WHERE i_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $i_head, $i_deltail, $itype_id, $i_id);
    }

    if ($stmt->execute()) {
        // บันทึกข้อมูลเรียบร้อยแล้ว ให้กลับไปยังหน้า display_information.php
        header("Location: display_information.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการแก้ไขข้อมูล: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #fff;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea, select {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dddddd;
        }
        input[type="file"] {
            margin-bottom: 20px;
        }
        input[type="submit"], .cancel-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 10px; /* เพิ่มระยะห่างระหว่างปุ่ม */
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .cancel-button {
            background-color: #2196F3; /* สีน้ำเงินของปุ่มยกเลิก */
            color: white;
            text-align: center;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .cancel-button:hover {
            background-color: #1e88e5; /* สีน้ำเงินเข้มเมื่อ hover */
        }
        .button-container {
            display: flex;
            justify-content: center; /* จัดตำแหน่งปุ่ม */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #ffa500;">แก้ไขข้อมูลข่าวสาร</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="i_head">หัวข้อข่าว:</label>
            <input type="text" name="i_head" value="<?php echo htmlspecialchars($i_head); ?>" required>

            <label for="i_deltail">เนื้อหาข่าว:</label>
            <textarea name="i_deltail" rows="5" required><?php echo htmlspecialchars($i_deltail); ?></textarea>

            <label for="itype_id">ประเภทข่าว:</label>
            <select name="itype_id" required>
                <?php
                // ดึงประเภทข่าวสารจากฐานข้อมูล
                $sql = "SELECT itype_id, itype_name FROM info_type";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($type = $result->fetch_assoc()) {
                        $selected = ($type['itype_id'] == $itype_id) ? 'selected' : '';
                        echo "<option value='" . $type['itype_id'] . "' $selected>" . $type['itype_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <label for="i_cover">รูปปกข่าวสาร:</label>
            <input type="file" name="i_cover">
            <?php if ($i_cover): ?>
                <p>รูปปัจจุบัน:</p>
                <img src="<?php echo htmlspecialchars($i_cover); ?>" alt="Cover Image" style="max-width: 100px;">
            <?php endif; ?>

            <div class="button-container">
                <input type="submit" value="บันทึก">
                <a href="display_information.php" class="cancel-button">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
