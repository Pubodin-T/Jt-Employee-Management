<?php
// เริ่มต้นเซสชัน
session_start();

// ทำลายเซสชันทั้งหมด
session_unset(); // ลบตัวแปรเซสชันทั้งหมด
session_destroy(); // ทำลายเซสชัน

// เปลี่ยนเส้นทางไปยังหน้า 'index.php'
header('Location: index.html');
exit();
?>
