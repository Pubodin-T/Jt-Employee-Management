<?php
// เชื่อมต่อฐานข้อมูล
include 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$leave_type = isset($_GET['leave_type']) ? mysqli_real_escape_string($conn, $_GET['leave_type']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($conn, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($conn, $_GET['end_date']) : '';

// สร้าง SQL query
// สร้าง SQL query
$query = "SELECT lr.leave_requests_id, e.employee_name, e.employee_position, lt.leave_type_name, lr.start_date, lr.end_date, lr.status, DATE_FORMAT(lr.created_at, '%d/%l/%Y %H:%i:%s') as submit_date,
                 DATE_FORMAT(lr.start_date, '%d/%m/%y') as format_start_date,
                 DATE_FORMAT(lr.end_date, '%d/%m/%y') as format_end_date,
                 CASE
                     WHEN lr.status = '0' THEN 'รออนุมัติ'
                     WHEN lr.status = '1' THEN 'อนุมัติ'
                     WHEN lr.status = '2' THEN 'ไม่อนุมัติ'
                 END as status_name
          FROM leave_requests lr
          JOIN employee e ON lr.employee_id = e.employee_id
          JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id
          WHERE (e.employee_name LIKE '%$search%' OR lr.employee_id LIKE '%$search%')
          AND lr.status != '0'"; // เพิ่มเงื่อนไขนี้เพื่อกรองคำขอลาที่สถานะไม่ใช่ 'รออนุมัติ'

if ($leave_type != '') {
    $query .= " AND lr.leave_type_id = '$leave_type'";
}

if ($status != '') {
    $query .= " AND lr.status = '$status'";
}

if ($start_date != '') {
    $query .= " AND lr.start_date >= '$start_date'";
}

if ($end_date != '') {
    $query .= " AND lr.end_date <= '$end_date'";
}

$query .= " ORDER BY lr.leave_requests_id DESC";
$result = mysqli_query($conn, $query);


// ตั้งค่าตัวแปร counter สำหรับแสดงลำดับ
$counter = 1;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>ประวัติการลา</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="ประวัติการลา">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">

    <!-- CSS -->
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background-image: url('images/slider-main/j&t22.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }
        .sidebar {
            width: 250px;
            background-color: #1e1e1e;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed;
        }
        .sidebar h2 {
            font-family: 'Kanit', sans-serif;
        }
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
        .content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            background-image: url('path/to/your/content-background.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .content h1 {
            font-family: 'Kanit', sans-serif;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #ff0000;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #e9ecef;
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
    <div class="content">
        <h1>ประวัติการลา</h1>

        <!-- Search Form -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="ค้นหาชื่อพนักงาน หรือ รหัสพนักงาน" style="padding: 10px; width: 300px;">
            <select name="leave_type" style="padding: 10px;">
                <option value="">ทั้งหมด</option>
                <option value="1">ลากิจ</option>
                <option value="2">ลาป่วย</option>
                <option value="3">ลาพักร้อน</option>
                <option value="4">ลากรณีพิเศษ</option>
            </select>
            <select name="status" style="padding: 10px;">
                <option value="">สถานะทั้งหมด</option>
                <option value="0">รออนุมัติ</option>
                <option value="1">อนุมัติ</option>
                <option value="2">ไม่อนุมัติ</option>
            </select>
            
            <input type="date" name="start_date" placeholder="วันที่เริ่มลา" style="padding: 10px;">
            <input type="date" name="end_date" placeholder="วันที่สิ้นสุดลา" style="padding: 10px;">
            <input type="submit" value="ค้นหา" style="padding: 10px;">
        </form>

        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อพนักงาน</th>
                    <th>ตำแหน่ง</th>
                    <th>ประเภทการลา</th>
                    <th>วันที่เริ่มลา</th>
                    <th>วันที่สิ้นสุดลา</th>
                    
                    <th>วันที่ส่งคำขอลา</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $row['employee_name']; ?></td>
                            <td><?php echo $row['employee_position']; ?></td>
                            <td><?php echo $row['leave_type_name']; ?></td>
                            <td><?php echo $row['format_start_date']; ?></td>
                            <td><?php echo $row['format_end_date']; ?></td>
                            <td><?php echo $row['submit_date']; ?></td>
                            <td><?php echo $row['status_name']; ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">ไม่พบข้อมูลการลา</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
