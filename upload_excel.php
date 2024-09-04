<?php
session_start();
require 'db_connect.php';
require 'vendor/autoload.php'; // โหลด PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (class_exists('ZipArchive')) {
    echo 'ZipArchive is enabled!';
} else {
    echo 'ZipArchive is not enabled.';
}


// ตรวจสอบว่ามีการอัปโหลดไฟล์ Excel หรือไม่
if (isset($_POST['upload_excel']) && isset($_FILES['excel_file'])) {
    $fileName = $_FILES['excel_file']['tmp_name'];

    try {
        // โหลดไฟล์ Excel
        $spreadsheet = IOFactory::load($fileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        // ลูปผ่านข้อมูลใน Excel
        foreach ($sheetData as $key => $row) {
            // ข้ามแถวแรก (หัวข้อ)
            if ($key == 0) continue;

            // ดึงข้อมูลแต่ละคอลัมน์จากแถว
            $s_id = $row[0];
            $s_pna = $row[1];
            $s_na = $row[2];
            $s_la = $row[3];
            $s_email = $row[4];

            // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
            $sql = "INSERT INTO student (s_id, s_pna, s_na, s_la, s_email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $s_id, $s_pna, $s_na, $s_la, $s_email);

            // ดำเนินการเพิ่มข้อมูล
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        echo "<script>alert('เพิ่มข้อมูลจาก Excel เรียบร้อยแล้ว'); window.location='display_student.php';</script>";

    } catch (Exception $e) {
        echo 'Error loading file: ', $e->getMessage();
    }
}

$conn->close();
?>