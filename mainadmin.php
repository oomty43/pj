<!DOCTYPE html>
<html lang="en">
<head>
    <?php session_start(); 
    if(!isset($_SESSION['a_user'])){
        echo "
            <script>
                alert('กรุณา login');
                window.location='loginadmin.php';s
            </script>
        ";
    }?>
    
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="display_admin.php">จัดการข้อมูลผู้ดูแลระบบ</a></li>
            <li><a href="display_information.php">จัดการข้อมูลข่าวสาร</a></li>
            <li><a href="display_student.php">จัดการข้อมูลนักศึกษา</a></li>
        </ul>
    </div>
   
</body>
</html>
