<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Add New Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818; /* สีพื้นหลังที่เข้ม */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 50%;
            max-width: 600px;
            background-color: #333; /* สีพื้นหลังของกล่อง */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
            color: #fff; /* สีตัวอักษร */
        }
        h2 {
            text-align: center;
            color: #ffa500; /* สีของหัวข้อ */
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #ffa500; /* สีของ label */
        }
        input[type=text], input[type=email], input[type=password], select {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #555; /* สีขอบของ input และ select */
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #222; /* สีพื้นหลังของ input และ select */
            color: #fff; /* สีตัวอักษรใน input และ select */
            font-size: 16px;
        }
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px; /* ระยะห่างระหว่างปุ่ม */
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            flex: 1; /* ปรับขนาดปุ่มให้เต็มพื้นที่ */
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .reset-button {
            background-color: #dc3545; /* สีแดงสำหรับปุ่มยกเลิก */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            flex: 1; /* ปรับขนาดปุ่มให้เต็มพื้นที่ */
        }
        .reset-button:hover {
            background-color: #c82333; /* สีแดงเข้มเมื่อเมาส์ชี้ */
        }
        .cancel-button {
            background-color: #007BFF; /* สีน้ำเงินสำหรับปุ่มย้อนกลับ */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            flex: 1; /* ปรับขนาดปุ่มให้เต็มพื้นที่ */
        }
        .cancel-button:hover {
            background-color: #0056b3; /* สีน้ำเงินเข้มเมื่อเมาส์ชี้ */
        }
    </style>
    <script>
    function validatePhoneNumber(input) {
        // ลบตัวอักษรที่ไม่ใช่ตัวเลข
        input.value = input.value.replace(/[^0-9]/g, '');
        // ตรวจสอบความยาวไม่เกิน 10 ตัว
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10);
        }
    }
    </script>
</head>
<body>
    <div class="container">
        <h2>เพิ่ม admin</h2>
        <form method="post" action="">
            <label for="a_user">ชื่อผู้ใช้:</label>
            <input type="text" id="a_user" name="a_user" required>

            <label for="a_na">ชื่อ:</label>
            <input type="text" id="a_na" name="a_na" required>

            <label for="a_la">นามสกุล:</label>
            <input type="text" id="a_la" name="a_la" required>

            <label for="a_email">อีเมล์:</label>
            <input type="email" id="a_email" name="a_email" required>

            <label for="a_pws">รหัสผ่าน:</label>
            <input type="password" id="a_pws" name="a_pws" required>

            <label for="phone_number">เบอร์โทร:</label>
            <input type="text" id="phone_number" name="phone_number" maxlength="10" required oninput="validatePhoneNumber(this)">

            <label for="a_st">สถานะ </label>
<select id="a_st" name="a_st" required>
    <option value="1">เจ้าหน้าที่</option>
    <option value="2">อาจารย์</option>
</select>

            <div class="button-group">
                <input type="submit" value="เพิ่ม">
                <input type="reset" value="ยกเลิก" class="reset-button">
                <a href="display_admin.php" class="cancel-button">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>
