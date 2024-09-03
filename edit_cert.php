<?php
session_start(); 
include 'std_con.php';

$ce_id = $_GET['ce_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ce_na = $_POST['ce_na'];
    $og_na = $_POST['og_na'];
    $ce_year = $_POST['ce_year'];
    
    $sql = "UPDATE certi SET ce_na = ?, og_na = ?, ce_year = ? WHERE ce_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $ce_na, $og_na, $ce_year, $ce_id);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location.href='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล! กรุณาลองใหม่');</script>";
    }
} else {
    $sql = "SELECT * FROM certi WHERE ce_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ce_id);
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
    <title>แก้ไขข้อมูลใบรับรอง</title>
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
        .btn-cancel {
            padding: 10px;
            width: 48%;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>แก้ไขข้อมูลใบรับรอง</h2>
        <form action="edit_cert.php?ce_id=<?php echo $ce_id; ?>" method="POST">
            <div class="form-group">
                <label>ชื่อใบรับรอง:</label>
                <input type="text" name="ce_na" value="<?php echo htmlspecialchars($row['ce_na']); ?>" required>
            </div>
            <div class="form-group">
                <label>หน่วยงานที่รับรอง:</label>
                <input type="text" name="og_na" value="<?php echo htmlspecialchars($row['og_na']); ?>" required>
            </div>
            <div class="form-group">
                <label>ปีที่ได้รับ:</label>
                <input type="date" name="ce_year" value="<?php echo htmlspecialchars($row['ce_year']); ?>" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <a href="stdaward.php" class="btn-cancel">ยกเลิก</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
