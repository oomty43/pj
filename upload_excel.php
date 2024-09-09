<?php
session_start();
include 'db_connect.php';
require 'vendor/autoload.php'; // โหลด PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

// ตรวจสอบการอัปโหลดไฟล์ Excel
if (isset($_POST['upload_excel']) && isset($_FILES['excel_file'])) {
    $fileName = $_FILES['excel_file']['tmp_name'];

    // ตรวจสอบว่ามี session ของ a_user หรือไม่
    if (!isset($_SESSION['a_user'])) {
        echo "<script>alert('กรุณาล็อกอินก่อน'); window.location='loginadmin.php';</script>";
        exit();
    }

    $a_user = $_SESSION['a_user']; // ดึงค่า a_user จาก session

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
            $s_pws = !empty($row[5]) ? $row[5] : '1234';

            // ตรวจสอบความยาวของรหัสนักศึกษา
            if (strlen($s_id) < 12) {
                $errors[] = "รหัสนักศึกษา $s_id ไม่ครบ 12 หลัก";
                continue;
            }

            // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
            $sql = "INSERT INTO student (s_id, s_pna, s_na, s_la, s_email, s_pws) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $s_id, $s_pna, $s_na, $s_la, $s_email, $s_pws);

            // ดำเนินการเพิ่มข้อมูล
            if (!$stmt->execute()) {
                $errors[] = "เกิดข้อผิดพลาดในการเพิ่มข้อมูลนักศึกษา $s_id: " . $stmt->error;
            } else {
                // บันทึกการกระทำลง admin_logs โดยใช้ a_user เป็น a_id
                $action_type = "เพิ่มข้อมูลด้วย Excel";
                $log_sql = "INSERT INTO admin_logs (a_id, action_type, student_id) VALUES (?, ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("sss", $a_user, $action_type, $s_id); // ใช้ a_user แทน a_id
                $log_stmt->execute();
                $log_stmt->close();
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