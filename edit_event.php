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
        header("Location: stdaward.php");
    } else {
        echo "Error: " . $stmt->error;
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
    <title>แก้ไขข้อมูลกิจกรรม</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
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
        .form-group .buttons {
            display: flex;
            justify-content: space-between;
        }
        .form-group button,
        .form-group a.btn-cancel {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }
        .btn-save {
            background-color: #28a745;
            color: white;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>แก้ไขข้อมูลกิจกรรม</h2>
        <form action="edit_event.php?e_id=<?php echo $e_id; ?>" method="POST">
            <div class="form-group">
                <label for="e_na">ชื่อกิจกรรม:</label>
                <input type="text" id="e_na" name="e_na" value="<?php echo $row['e_na']; ?>" required>
            </div>
            <div class="form-group">
                <label for="e_add">สถานที่จัดกิจกรรม:</label>
                <input type="text" id="e_add" name="e_add" value="<?php echo $row['e_add']; ?>" required>
            </div>
            <div class="form-group">
                <label for="e_date">ปี:</label>
                <input type="date" id="e_date" name="e_date" value="<?php echo $row['e_date']; ?>" required>
            </div>
            <div class="form-group buttons">
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
