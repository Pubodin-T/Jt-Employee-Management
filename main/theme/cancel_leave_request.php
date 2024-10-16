<?php
include("db_connection.php");
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า id ของคำขอหรือไม่
if (isset($_GET['id'])) {
    $leave_requests_id = $_GET['id'];

    // ตรวจสอบว่าคำขอมีอยู่ในฐานข้อมูลหรือไม่
    $checkQuery = "SELECT * FROM leave_requests WHERE leave_requests_id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $leave_requests_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // ถ้ามีคำขอนี้อยู่จริง ให้ทำการยกเลิกคำขอ
        $query = "UPDATE leave_requests SET status = '2' WHERE leave_requests_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $leave_requests_id);
        
        if ($stmt->execute()) {
            // สำเร็จ ให้ส่งผู้ใช้กลับไปยังหน้าหลักหรือหน้าประวัติการลา
            header('Location: status.php?message=cancel_success');
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการยกเลิกคำขอ: " . $conn->error;
        }
    } else {
        echo "ไม่พบคำขอที่ต้องการยกเลิก";
    }
} else {
    echo "ไม่พบคำขอที่ต้องการยกเลิก";
}
?>
