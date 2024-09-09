<?php
session_start(); 

include 'std_con.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eh_na = $_POST['eh_na'];
    $eh_level = $_POST['eh_level'];
    $eh_end = $_POST['eh_end'];
    $s_id = $_SESSION['s_id'];

    // Insert the new education history into the database
    $sql = "INSERT INTO edu_history (eh_na, eh_level, eh_end, s_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $eh_na, $eh_level, $eh_end, $s_id);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลประวัติการศึกษาสำเร็จ!'); window.location.href='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล! กรุณาลองใหม่');</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มประวัติการศึกษา</title>
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
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 15px; /* ลดระยะห่างด้านบนของกลุ่มปุ่ม */
            gap: 10px; /* เพิ่มช่องว่างระหว่างปุ่ม */
        }
        .btn-submit,
        .btn-cancel,
        .btn-back {
            padding: 10px;
            flex: 1; /* ปุ่มแต่ละปุ่มมีขนาดเท่ากัน */
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            border: none;
            color: white;
        }
        .btn-submit {
            background-color: #28a745;
        }
        .btn-submit:hover {
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
        <h2>เพิ่มประวัติการศึกษา</h2>
        <form id="educationForm" action="add_eh.php" method="post">
            <div class="form-group">
                <label for="eh_na">ชื่อสถานที่ศึกษา:</label>
                <input type="text" id="eh_na" name="eh_na" required>
            </div>
            <div class="form-group">
                <label for="eh_level">ระดับการศึกษา:</label>
                <input type="text" id="eh_level" name="eh_level" required>
            </div>
            <div class="form-group">
    <label for="eh_end">ปีที่จบการศึกษา:</label>
    <input type="text" id="eh_end" name="eh_end" maxlength="4" placeholder="ค.ศ." required>
</div>

            <div class="button-group">
                <button type="submit" class="btn-submit">บันทึก</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('educationForm').reset();">ยกเลิก</button>
                <a href="stdaward.php" class="btn-back">ย้อนกลับ</a>
            </div>
        </form>
    </div>

</body>
</html>
