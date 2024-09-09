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
        $errors = [];
        foreach ($sheetData as $key => $row) {
            // ข้ามแถวแรก (หัวข้อ)
            if ($key == 0) continue;

            // ดึงข้อมูลแต่ละคอลัมน์จากแถว
            $s_id = $row[0];
            $s_pna = $row[1];
            $s_na = $row[2];
            $s_la = $row[3];
            $s_email = $row[4];
            $s_pws = !empty($row[5]) ? $row[5] : '1234'; // ตรวจสอบค่าว่าง หากว่างให้ใช้ 1234

            // ตรวจสอบความยาวของรหัสนักศึกษา
            if (strlen($s_id) < 12) {
                $errors[] = "รหัสนักศึกษา $s_id ไม่ครบ 12 หลัก (ปัจจุบัน " . strlen($s_id) . " หลัก)";
                continue; // ข้ามการเพิ่มข้อมูลนี้
            } elseif (strlen($s_id) > 12) {
                $errors[] = "รหัสนักศึกษา $s_id เกิน 12 หลัก (ปัจจุบัน " . strlen($s_id) . " หลัก)";
                continue; // ข้ามการเพิ่มข้อมูลนี้
            }

            // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
            $sql = "INSERT INTO student (s_id, s_pna, s_na, s_la, s_email, s_pws) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $s_id, $s_pna, $s_na, $s_la, $s_email, $s_pws);

            // ดำเนินการเพิ่มข้อมูล
            if (!$stmt->execute()) {
                $errors[] = "เกิดข้อผิดพลาดในการเพิ่มข้อมูลนักศึกษา $s_id: " . $stmt->error;
            }

            $stmt->close();
        }

        // แสดงผลลัพธ์การอัปโหลด
        if (empty($errors)) {
            echo "<script>alert('เพิ่มข้อมูลจาก Excel เรียบร้อยแล้ว'); window.location='display_student.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด:\\n" . implode("\\n", $errors) . "'); window.location='display_student.php';</script>";
        }

    } catch (Exception $e) {
        echo 'Error loading file: ', $e->getMessage();
    }
}

$conn->close();
?>
