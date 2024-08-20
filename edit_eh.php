<?php
session_start(); 
include 'std_con.php';

$eh_id = $_GET['eh_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eh_na = $_POST['eh_na'];
    $eh_level = $_POST['eh_level'];
    $eh_end = $_POST['eh_end'];
    
    $sql = "UPDATE edu_history SET eh_na = ?, eh_level = ?, eh_end = ? WHERE eh_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $eh_na, $eh_level, $eh_end, $eh_id);

    if ($stmt->execute()) {
        header("Location: mainstd.php");
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $sql = "SELECT * FROM edu_history WHERE eh_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eh_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขประวัติการศึกษา</title>
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
    <form action="edit_eh.php?eh_id=<?php echo $eh_id; ?>" method="POST">
        <label>ชื่อสถานที่ศึกษา:</label>
        <input type="text" name="eh_na" value="<?php echo $row['eh_na']; ?>"><br>
        <label>ระดับการศึกษา:</label>
        <input type="text" name="eh_level" value="<?php echo $row['eh_level']; ?>"><br>
        <label>ปีที่จบการศึกษา:</label>
        <input type="date" name="eh_end" value="<?php echo $row['eh_end']; ?>"><br>
        <button type="submit">บันทึก</button>
        <a href="mainstd.php">ยกเลิก</a>
    </form>
</body>
</html>
<?php
$conn->close();
?>
