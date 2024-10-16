<?php
session_start();
include("db_connection.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 2) {
    echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    exit();
}

// ตรวจสอบว่าได้ส่งค่ามาจริงหรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['employee_id']) && isset($_POST['new_status'])) {
    $employee_id = $_POST['employee_id'];
    $new_status = $_POST['new_status'];

    // คำสั่ง SQL สำหรับเปลี่ยนสถานะ
    $sql = "UPDATE employee SET status = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $new_status, $employee_id);

        if ($stmt->execute()) {
            // เปลี่ยนสถานะสำเร็จ
            header("Location: employee_list.php?status=success");
            exit();
        } else {
            // เปลี่ยนสถานะไม่สำเร็จ
            header("Location: employee_list.php?status=error");
            exit();
        }

        $stmt->close();
    } else {
        // ถ้าเตรียมคำสั่ง SQL ไม่สำเร็จ
        header("Location: employee_list.php?status=prepare_error");
        exit();
    }
} else {
    // ถ้าไม่มีการส่งข้อมูล
    header("Location: employee_list.php?status=invalid_request");
    exit();
}

$conn->close();
?>
