<?php
session_start();
include("db_connection.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 2) {
    echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    exit();
}

// ตรวจสอบว่าได้ส่งค่ามาจริงหรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reset_leave'])) {
   echo('123');
} else {
    // ถ้าไม่มีการส่งข้อมูล
    header("Location: employee_list.php?status=invalid_request");
    exit();
}

$conn->close();
?>
