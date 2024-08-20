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
        header("Location: mainstd.php");
    } else {
        echo "Error: " . $stmt->error;
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
    <title>แก้ไขข้อมูลใบรับรอง</title>
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
    <form action="edit_cert.php?ce_id=<?php echo $ce_id; ?>" method="POST">
        <label>ชื่อใบรับรอง:</label>
        <input type="text" name="ce_na" value="<?php echo $row['ce_na']; ?>"><br>
        <label>หน่วยงานที่รับรอง:</label>
        <input type="text" name="og_na" value="<?php echo $row['og_na']; ?>"><br>
        <label>ปีที่ได้รับ:</label>
        <input type="date" name="ce_year" value="<?php echo $row['ce_year']; ?>"><br>
        <button type="submit">บันทึก</button>
        <a href="mainstd.php">ยกเลิก</a>
    </form>
</body>
</html>
<?php
$conn->close();
?>
