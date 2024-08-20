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
        header("Location: mainstd.php");
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
        }
        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
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
        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-save {
            display: inline-block;
            width: 48%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-cancel {
            display: inline-block;
            width: 48%;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="edit_event.php?e_id=<?php echo $e_id; ?>" method="POST">
        <label>ชื่อกิจกรรม:</label>
        <input type="text" name="e_na" value="<?php echo $row['e_na']; ?>"><br>
        <label>สถานที่จัดกิจกรรม:</label>
        <input type="text" name="e_add" value="<?php echo $row['e_add']; ?>"><br>
        <label>ปี:</label>
        <input type="date" name="e_date" value="<?php echo $row['e_date']; ?>"><br>
        <button type="submit">บันทึก</button>
        <a href="mainstd.php">ยกเลิก</a>
    </form>
</body>
</html>
<?php
$conn->close();
?>
