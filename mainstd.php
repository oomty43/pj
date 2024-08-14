<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลักเว็บไซต์</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .banner {
            width: 100%;
            height: auto;
        }
        .nav-buttons {
            text-align: right;
            margin: 10px;
        }
        .nav-buttons a {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .nav-buttons a:hover {
            background-color: #0056b3;
        }
        .news-container {
            padding: 20px;
        }
        .news-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .news-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .news-item h2 {
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Banner -->
    <img src="uploads/te.jpg" alt="Banner" class="banner">

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <a href="stdmain.php">หน้าหลัก</a>
        <a href="stdprofile.php">ข้อมูลส่วนตัว</a>
        <a href="stdaward.php">ผลงานส่วนตัว</a>
    </div>

    <!-- News Section -->
    <div class="news-container">
        <?php
        // เชื่อมต่อฐานข้อมูล
        $conn = new mysqli("localhost", "root", "", "project");

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
        }

        // คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง information
        $sql = "SELECT i_cover, i_head FROM information";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // แสดงผลข้อมูล
            while($row = $result->fetch_assoc()) {
                echo "<div class='news-item'>";
                echo "<img src='upload/" . $row["i_cover"] . "' alt='ข่าวสาร'>";
                echo "<h2>" . $row["i_head"] . "</h2>";
                echo "</div>";
            }
        } else {
            echo "ไม่มีข่าวสาร";
        }

        // ปิดการเชื่อมต่อ
        $conn->close();
        ?>
    </div>

</body>
</html>
