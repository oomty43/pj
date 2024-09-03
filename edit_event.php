<?php
session_start(); 
include 'std_con.php';

$e_id = $_GET['e_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $e_na = $_POST['e_na'];
    $e_add = $_POST['e_add'];
    $e_date = $_POST['e_date'];
    
    $sql = "UPDATE ev SET e_na = ?, e_add = ?, e_date = ? WHERE e_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $e_na, $e_add, $e_date, $e_id);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขข้อมูลกิจกรรมสำเร็จ!'); window.location.href='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล! กรุณาลองใหม่');</script>";
    }
} else {
    $sql = "SELECT * FROM ev WHERE e_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $e_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลกิจกรรม</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
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
        .form-group input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        .btn-save,
        .btn-cancel,
        .btn-back {
            padding: 10px;
            width: 30%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            color: white;
            border: none;
        }
        .btn-save {
            background-color: #28a745;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .btn-back {
            background-color: blue;
        }
        .btn-back:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>แก้ไขข้อมูลกิจกรรม</h2>
        <form id="editEventForm" action="edit_event.php?e_id=<?php echo $e_id; ?>" method="POST">
            <div class="form-group">
                <label for="e_na">ชื่อกิจกรรม:</label>
                <input type="text" id="e_na" name="e_na" value="<?php echo htmlspecialchars($row['e_na']); ?>" required>
            </div>
            <div class="form-group">
                <label for="e_add">สถานที่จัดกิจกรรม:</label>
                <input type="text" id="e_add" name="e_add" value="<?php echo htmlspecialchars($row['e_add']); ?>" required>
            </div>
            <div class="form-group">
                <label for="e_date">ปี:</label>
                <input type="date" id="e_date" name="e_date" value="<?php echo htmlspecialchars($row['e_date']); ?>" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('editEventForm').reset();">ยกเลิก</button>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
