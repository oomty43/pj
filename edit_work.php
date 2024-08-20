<?php
session_start(); 
include 'std_con.php';

$w_id = $_GET['w_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $w_na = $_POST['w_na'];
    $w_date = $_POST['w_date'];
    
    $sql = "UPDATE wk SET w_na = ?, w_date = ? WHERE w_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $w_na, $w_date, $w_id);

    if ($stmt->execute()) {
        header("Location: stdaward.php");
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $sql = "SELECT * FROM wk WHERE w_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $w_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลการทำงาน</title>
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
        .btn-save,
        .btn-cancel {
            display: inline-block;
            width: 48%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            color: white;
            cursor: pointer;
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
        .button-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>แก้ไขข้อมูลการทำงาน</h2>
        <form action="edit_work.php?w_id=<?php echo $w_id; ?>" method="POST">
            <div class="form-group">
                <label>สถานที่ทำงาน:</label>
                <input type="text" name="w_na" value="<?php echo $row['w_na']; ?>" required>
            </div>
            <div class="form-group">
                <label>ปีที่เริ่มทำงาน:</label>
                <input type="date" name="w_date" value="<?php echo $row['w_date']; ?>" required>
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
