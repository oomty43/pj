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
        header("Location: stdaward.php");
        exit();
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
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-save {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px;
        }
        .btn-cancel {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            margin-right: 10px;
            text-decoration: none;
            text-align: center;
        }
        .btn-back {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            text-decoration: none;
            text-align: center;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .btn-back:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>แก้ไขประวัติการศึกษา</h2>
        <form action="edit_eh.php?eh_id=<?php echo $eh_id; ?>" method="POST">
            <div class="form-group">
                <label for="eh_na">ชื่อสถานที่ศึกษา:</label>
                <input type="text" id="eh_na" name="eh_na" value="<?php echo $row['eh_na']; ?>" required>
            </div>
            <div class="form-group">
                <label for="eh_level">ระดับการศึกษา:</label>
                <input type="text" id="eh_level" name="eh_level" value="<?php echo $row['eh_level']; ?>" required>
            </div>
            <div class="form-group">
                <label for="eh_end">ปีที่จบการศึกษา:</label>
                <input type="date" id="eh_end" name="eh_end" value="<?php echo $row['eh_end']; ?>" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-save">บันทึก</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('eh_na').value=''; document.getElementById('eh_level').value=''; document.getElementById('eh_end').value='';">ยกเลิก</button>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
