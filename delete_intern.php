<?php
session_start();
include 'std_con.php';

if (isset($_GET['its_id'])) {
    $its_id = $_GET['its_id'];

    // ลบข้อมูลการฝึกงานจากฐานข้อมูล
    $sql = "DELETE FROM its_history WHERE its_id = ? AND s_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $its_id, $_SESSION['s_id']);

    if ($stmt->execute()) {
        echo "ลบข้อมูลการฝึกงานสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
} else {
    echo "ไม่มีข้อมูลการฝึกงานที่เลือก";
}

$conn->close();
header("Location: stdaward.php");
exit();
?>
