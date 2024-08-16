<?php
// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// เมื่อมีการส่งข้อมูล POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a_user = $_POST['a_user'];
    $a_na = $_POST['a_na'];
    $a_email = $_POST['a_email'];
    $a_pws = $_POST['a_pws'];

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO admin (a_user, a_na, a_email, a_pws) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $a_user, $a_na, $a_email, $a_pws);

    if ($stmt->execute()) {
        echo '<div style="padding: 20px; background-color: #f0f0f0; border: 1px solid #ccc; margin-top: 20px;">';
        echo "New admin added successfully. <a href='display_admin.php'>Back to Admin Table</a>";
        echo '</div>';
    } else {
        echo '<div style="padding: 20px; background-color: #f0f0f0; border: 1px solid #ccc; margin-top: 20px;">';
        echo "Error: " . $sql . "<br>" . $conn->error;
        echo '</div>';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 10px;
        }
        input[type=text], input[type=email], input[type=password] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Admin</h2>
        <form method="post" action="">
            <label for="a_user">Username:</label>
            <input type="text" id="a_user" name="a_user" required><br>

            <label for="a_na">Name:</label>
            <input type="text" id="a_na" name="a_na" required><br>

            <label for="a_email">Email:</label>
            <input type="email" id="a_email" name="a_email" required><br>

            <label for="a_pws">Password:</label>
            <input type="password" id="a_pws" name="a_pws" required><br>

            <input type="submit" value="Add Admin">
        </form>
    </div>
</body>
</html>
