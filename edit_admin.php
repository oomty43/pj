<?php
// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['a_user'])) {
    $a_user = $_GET['a_user'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a_na = $_POST['a_na'];
        $a_la = $_POST['a_la'];
        $a_email = $_POST['a_email'];
        $a_pws = $_POST['a_pws'];
        $a_st = $_POST['a_st']; // รับค่าของสถานะ

        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE admin SET a_na=?, a_la=?, a_email=?, a_pws=?, a_st=? WHERE a_user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $a_na, $a_la, $a_email, $a_pws, $a_st, $a_user);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Record updated successfully.');
                    window.location.href='display_admin.php';
                  </script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt->close();
    } else {
        // ดึงข้อมูลปัจจุบันจากฐานข้อมูล
        $sql = "SELECT a_user, a_na, a_la, a_email, a_pws, a_st FROM admin WHERE a_user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $a_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "No records found";
            exit();
        }

        $stmt->close();
    }
} else {
    echo "Invalid request";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Admin</title>
</head>
<body>
    <h2>Edit Admin</h2>
    <form method="post" action="">
        <label for="a_na">Name:</label>
        <input type="text" id="a_na" name="a_na" value="<?php echo $row['a_na']; ?>" required><br><br>
        
        <label for="a_la">Lastname:</label>
        <input type="text" id="a_la" name="a_la" value="<?php echo $row['a_la']; ?>" required><br><br>
        
        <label for="a_email">Email:</label>
        <input type="email" id="a_email" name="a_email" value="<?php echo $row['a_email']; ?>" required><br><br>
        
        <label for="a_pws">Password:</label>
        <input type="password" id="a_pws" name="a_pws" value="<?php echo $row['a_pws']; ?>" required><br><br>
        
        <label for="a_st">Status:</label>
        <select id="a_st" name="a_st" required>
            <option value="0" <?php echo ($row['a_st'] == 0) ? 'selected' : ''; ?>>เจ้าหน้าที่ (Staff)</option>
            <option value="1" <?php echo ($row['a_st'] == 1) ? 'selected' : ''; ?>>อาจารย์ (Teacher)</option>
        </select><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
