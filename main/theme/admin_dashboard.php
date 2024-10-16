<?php
// ฟังก์ชันเพื่อดึงจำนวนคำขอลาที่รอการอนุมัติจากฐานข้อมูล
function getPendingLeaveRequests() {
    // กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
    $host = 'localhost';
    $db = 'companyx';
    $user = 'root';
    $pass = '';

    // สร้างการเชื่อมต่อฐานข้อมูล
    $conn = new mysqli($host, $user, $pass, $db);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // คำสั่ง SQL เพื่อดึงจำนวนใบลาที่มีสถานะ 'รออนุมัติ'
    $sql = "SELECT COUNT(*) AS pending_count FROM leave_requests WHERE status = '0'";
    
    // เตรียมคำสั่ง SQL
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // ดึงจำนวนคำขอลา
        $row = $result->fetch_assoc();
        return $row['pending_count'];
    } else {
        return 0; // ไม่มีคำขอลาที่รออนุมัติ
    }

    $conn->close();
}

// เรียกฟังก์ชันเพื่อดึงจำนวนใบลาที่รออนุมัติ
$pendingCount = getPendingLeaveRequests();
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
            background-color: #ff0000;
            color: #fff;
        }
        
    .notification {
        margin-top: 20px;
        padding: 15px;
    }
    .notification a {
    display: block; /* ให้ลิงก์เป็นบล็อกเพื่อให้คลิกได้ทั้งกล่อง */
    padding: 15px; /* เพิ่ม padding รอบๆ ข้อความ */
    border-radius: 5px; /* มุมมน */
    transition: background-color 0.3s, box-shadow 0.3s; /* เอฟเฟกต์การเปลี่ยนแปลง */
}

.notification a:hover {
    background-color: #ff0000; /* เปลี่ยนพื้นหลังเมื่อวางเมาส์ */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อวางเมาส์ */
}
    .alert {
    display: inline-block; /* ให้กล่องมีขนาดตามเนื้อหาข้างใน */
    max-width: 400px; /* กำหนดความกว้างสูงสุดของกล่อง */
    padding: 15px; /* เพิ่ม padding รอบๆ ข้อความในกล่อง */
    margin-bottom: 20px;
    border-radius: 10px; /* ปรับรัศมีมุมให้โค้งมน */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2), /* เงาที่มุมด้านล่าง */
                0 6px 6px rgba(0, 0, 0, 0.1); /* เงาที่เบลอ */
    transition: transform 0.2s; /* เอฟเฟกต์ในการขยายขนาด */
    word-wrap: break-word; /* ให้ข้อความแสดงในบรรทัดใหม่เมื่อมีความยาวเกินกล่อง */
    cursor: pointer; /* เปลี่ยนเคอร์เซอร์เมื่อเลื่อนเมาส์ไปบนกล่องแจ้งเตือน */
}

.alert:hover {
    transform: scale(1.05); /* ขยายขนาดกล่องเมื่อชี้เมาส์ไปที่มัน */
    background-color: #ff0000;
}
    
    .alert-warning {
        background-color: #272727;
        color: #856404;
        border-color: #2b2b2b;
    }
    .alert-success {
        background-color: #000000;
        color: #fff;
    }
    .alert i {
    margin-right: 10px; /* เพิ่มระยะห่างระหว่างไอคอนและข้อความ */
    font-size: 20px; /* ขนาดของไอคอน */
    vertical-align: middle; /* จัดให้ไอคอนอยู่กึ่งกลางกับข้อความ */
}
.alert i {
    margin-right: 10px; /* ระยะห่างระหว่างไอคอนและข้อความ */
    font-size: 20px; /* ขนาดของไอคอน */
    vertical-align: middle; /* จัดไอคอนให้กึ่งกลางข้อความ */
    color: #ffc107; /* สีของไอคอนรูประฆัง */
}
/* การสั่นของไอคอนระฆัง */
@keyframes shake {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(-10deg); }
    50% { transform: rotate(10deg); }
    75% { transform: rotate(-10deg); }
    100% { transform: rotate(0deg); }
}

.bell-shake {
    animation: shake 0.5s ease-in-out infinite;
    color: #ffc107; /* สีของไอคอนเมื่อมีการแจ้งเตือน */
}

#request-count {
    background-color: red;
    color: white;
    padding: 2px 6px;
    border-radius: 50%;
    margin-left: 10px;
    font-size: 14px;
}
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>ผู้ดูแลระบบ</h2>
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
    <div class="content">
        <h1>แดชบอร์ดผู้ดูแลระบบ</h1>
        <p>ยินดีต้อนรับเข้าสู่แดชบอร์ดสำหรับผู้ดูแลระบบ!</p>
        <div class="notification">
            <?php if ($pendingCount > 0): ?>
                <a href="approve_leave.php" class="alert alert-warning" style="text-decoration: none; color: inherit;">
                    <i class="fas fa-bell"></i> 
                    คำขอลาใหม่จำนวน <strong><?php echo $pendingCount; ?></strong> รายการที่รอการอนุมัติ
                
                <span id="request-count"><?php echo $pendingCount; ?></span> <!-- จำนวนคำขอลา -->
        </a>
    <?php else: ?>
        <div class="alert alert-success">
            <i class="fas fa-bell"></i> <!-- ไอคอนรูประฆัง -->
            ไม่มีคำขอลาที่รออนุมัติในขณะนี้
        </div>
    <?php endif; ?>
</div>
    <script>
        window.onload = function() {
            var pendingCount = <?php echo $pendingCount; ?>;
            var bellIcon = document.querySelector('.fas.fa-bell');

            // ถ้ามีใบคำขอลาให้ไอคอนสั่น
            if (pendingCount > 0) {
                bellIcon.classList.add('bell-shake');
            }
        };
    </script>

</body>
</html>
