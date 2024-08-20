<?php
session_start(); 

include 'std_con.php';

if (!isset($_GET['pg_id'])) {
    echo "<script>alert('ไม่มี ID ที่ต้องการแก้ไข!'); window.location.href='stdaward.php';</script>";
    exit();
}

$pg_id = $_GET['pg_id'];

// ดึงข้อมูลโปรแกรมจากฐานข้อมูล
$sql = "SELECT pg_na FROM program WHERE pg_id = ? AND s_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $pg_id, $_SESSION['s_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('ไม่พบข้อมูลที่ต้องการแก้ไข!'); window.location.href='stdaward.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pg_na = $_POST['pg_na'];

    // อัพเดทข้อมูลโปรแกรม
    $sql = "UPDATE program SET pg_na = ? WHERE pg_id = ? AND s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $pg_na, $pg_id, $_SESSION['s_id']);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location.href='stdaward.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล! กรุณาลองใหม่');</script>";
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
    <title>แก้ไขทักษะการเขียนโปรแกรม</title>
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
        .btn-submit {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-cancel {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>แก้ไขทักษะการเขียนโปรแกรม</h2>
        <form action="edit_program.php?pg_id=<?php echo $pg_id; ?>" method="post">
            <div class="form-group">
                <label for="pg_na">ชื่อทักษะการเขียนโปรแกรม</label>
                <input type="text" id="pg_na" name="pg_na" value="<?php echo $row['pg_na']; ?>" required>
            </div>
            <button type="submit" class="btn-submit">บันทึก</button>
            <a href="stdaward.php" class="btn-cancel">ยกเลิก</a>
        </form>
    </div>

</body>
</html>
