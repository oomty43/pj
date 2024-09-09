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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px; /* ระยะห่างระหว่างปุ่ม */
        }
        .btn-save, .btn-cancel, .btn-back {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            flex: 1;
            color: white;
            border: none;
            text-align: center;
        }
        .btn-save {
            background-color: #28a745;
            margin-right: 10px;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #dc3545;
            margin-right: 10px;
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
                <input type="text" id="eh_end" name="eh_end" maxlength="4" placeholder="ค.ศ." value="<?php echo $row['eh_end']; ?>" required>
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
