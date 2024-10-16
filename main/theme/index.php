<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// โค้ดของคุณสำหรับหน้า index.php ที่นี่
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- ข้อมูลพื้นฐานของหน้า -->
  <meta charset="utf-8">
  <title>หน้าหลัก</title>
  <!-- CSS และไฟล์ที่จำเป็น -->
</head>
<body>
  <!-- เนื้อหา HTML ของคุณที่นี่ -->
</body>
</html>