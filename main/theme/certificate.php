<?php
// ฟังก์ชันเพื่อดึงข้อมูลใบรับรองแพทย์จากฐานข้อมูล
function getMedicalCertificates() {
    // กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
    $host = 'localhost'; // เปลี่ยนตามต้องการ
    $db = 'companyx'; // ชื่อฐานข้อมูล
    $user = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
    $pass = ''; // รหัสผ่านฐานข้อมูล

    // สร้างการเชื่อมต่อฐานข้อมูล
    $conn = new mysqli($host, $user, $pass, $db);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // คำสั่ง SQL เพื่อดึงข้อมูลที่มีใบรับรองเท่านั้น
    $sql = "SELECT e.employee_name, DATE_FORMAT(lr.created_at, '%d/%l/%Y %H:%i:%s') as created_at, lr.certificate
    FROM leave_requests lr
    JOIN employee e ON lr.employee_id = e.employee_id
    WHERE lr.certificate IS NOT NULL AND lr.certificate != ''
    ORDER BY lr.created_at DESC "; // จัดเรียงตามวันที่ล่าสุดจากมากไปน้อย
    $stmt = $conn->prepare($sql);

    // ตรวจสอบว่าการเตรียมคำสั่งสำเร็จ
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // เก็บข้อมูลในอาเรย์
    $certificate = [];
    while ($row = $result->fetch_assoc()) {
        $certificate[] = $row;
    }

    $stmt->close();
    $conn->close();

    // ส่งข้อมูลกลับ
    return $certificate;
}

// ดึงข้อมูลใบรับรองแพทย์
$medicalCertificate = getMedicalCertificates();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>แดชบอร์ดผู้ดูแลระบบ</title>

    <!-- Mobile Specific Metas -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="แดชบอร์ดผู้ดูแลระบบ">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">

    <!-- CSS -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background: #f0f2f5; /* สีพื้นหลังของทั้งหน้า */
        }
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #1e1e1e; /* สีพื้นหลังของ Sidebar */
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed; /* Fix it to the left side */
            color: #f8f9fa; /* สีของข้อความใน Sidebar */
        }
        .sidebar h2 {
            color: #f8f9fa; /* สีของหัวข้อ Sidebar */
        }
        .sidebar a {
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #f8f9fa; /* สีของลิงก์ */
            background-color: #495057; /* สีพื้นหลังของลิงก์ */
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #dc3545;
            color: #fff;
        }
        .sidebar a.active {
            background-color: #dc3545;
            color: #fff;
        }
        /* Main Content Styles */
        .content {
            margin-left: 250px; /* Leave space for the sidebar */
            padding: 40px;
            background: url('images/slider-main/j&t22.jpg') no-repeat center center/cover;
            width: calc(100% - 250px); /* Adjust the width to account for the sidebar */
            color: #fff; /* สีของข้อความใน Main Content */
        }
        .content h1 {
            color: #fff;
        }
        .content p {
            color: #ddd; /* สีของข้อความอื่นๆ */
        }
        .certificate-table {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            color: #000;
        }
        .certificate-table th, .certificate-table td {
            padding: 10px;
            text-align: left;
        }
        .certificate-table th {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>เมนูผู้ดูแล</h2>
        <a href="admin_dashboard.php">หน้าหลัก</a>
        <a href="employee_list.php">ข้อมูลพนักงาน</a>
        <a href="approve_leave.php">จัดการการลาพนักงาน</a>
        <a href="addemployee.php">ลงทะเบียนพนักงานใหม่</a>
        <a href="leave_history.php">ประวัติการลาพนักงาน</a>
        <a href="certificate.php">ประวัติใบรับรองแพทย์</a>
        <a href="reset_leave.php">รีเซ็ตวันลาพักร้อน</a>
        <a href="leave_report.php">ยอดวันใช้ไป-คงเหลือพนักงาน</a>
        <a href="leave_report_employee.php">สถิติการลา</a>
        <a href="logout.php">ออกจากระบบ</a>
    </div>

    <!-- Main Content -->
    <!-- Main Content -->
<div class="content">
    
    <div class="certificate-table">
        <h2>ใบรับรองแพทย์</h2>
        <table border="1" width="100%">
            <tr>
                <th>ลำดับ</th> <!-- เพิ่มคอลัมน์สำหรับลำดับ -->
                <th>ชื่อพนักงาน</th>
                <th>วันที่สร้างใบลา</th>
                <th>ใบรับรองแพทย์</th>
            </tr>
            <?php if (count($medicalCertificate) > 0): ?>
                <?php 
                $index = 1; // เริ่มนับจาก 1
                foreach ($medicalCertificate as $certificate): ?>
                <tr>
                    <td><?php echo $index++; ?></td> <!-- แสดงลำดับและเพิ่มขึ้น -->
                    <td><?php echo htmlspecialchars($certificate['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($certificate['created_at']); ?></td>
                    <td><a href="uploads/<?php echo htmlspecialchars($certificate['certificate']); ?>" target="_blank">ดูใบรับรอง</a></td>
                </tr>
                
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">ไม่มีข้อมูลใบรับรองแพทย์</td> <!-- เปลี่ยนจำนวนคอลัมน์ที่ถูกควบรวม -->
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
</body>
</html>
