<?php
// เชื่อมต่อฐานข้อมูล
include 'db_connection.php';

// กำหนดวันลาที่พนักงานมีสิทธิ์ในหนึ่งปี
$vacationDaysAllowed = 6; // วันลาพักร้อน
$sickDaysAllowed = 30;     // วันลาป่วย
$personalDaysAllowed = 3;  // วันลากิจ
$specialDaysAllowed = 5;   // วันลากรณีพิเศษ

// SQL Query สำหรับดึงข้อมูลการลาและยอดคงเหลือ
$query = "SELECT 
            e.employee_id, 
            e.employee_name,
            e.sick_leave_remaining,
            e.personal_leave_remaining,
            e.vacation_leave_remaining,
            e.special_leave_remaining,
            SUM(CASE WHEN lr.leave_type_id = 1 AND lr.status = 2  THEN DATEDIFF(lr.end_date, lr.start_date) + 1 ELSE 0 END) AS personal_days_taken,
            SUM(CASE WHEN lr.leave_type_id = 2 AND lr.status = 2 THEN DATEDIFF(lr.end_date, lr.start_date) + 1 ELSE 0 END) AS sick_days_taken,
            SUM(CASE WHEN lr.leave_type_id = 3 AND lr.status = 2 THEN DATEDIFF(lr.end_date, lr.start_date) + 1 ELSE 0 END) AS vacation_days_taken,
            SUM(CASE WHEN lr.leave_type_id = 4 AND lr.status = 2 THEN DATEDIFF(lr.end_date, lr.start_date) + 1 ELSE 0 END) AS special_days_taken


          FROM 
            employee e
          RIGHT JOIN 
            leave_requests lr ON e.employee_id = lr.employee_id
          GROUP BY 
            e.employee_id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานการลา - รายละเอียด</title>
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
            background: url('images/slider-main/j&t22.jpg') 
        }
        .sidebar {
            width: 250px;
            background-color: #1e1e1e; /* สีพื้นหลังของ Sidebar */
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed; /* Fix it to the left side */
            color: #f8f9fa; /* สีของข้อความใน Sidebar */
            font-family: 'Kanit', sans-serif;
        }
        .sidebar h2 {
            color: #f8f9fa; /* สีของหัวข้อ Sidebar */
            font-family: 'Kanit', sans-serif;
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
        /* Table Styles */
        h3 {
            margin-left: 270px; /* ให้มีระยะห่างจาก Sidebar */
            padding: 20px 0;
        }
        table {
            width: calc(100% - 270px); /* ปรับความกว้างของตารางให้เหมาะสม */
            margin-left: 270px; /* ให้มีระยะห่างจาก Sidebar */
            margin-top: 20px;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff; /* สีพื้นหลังของตาราง */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #000000;
            transition: background-color 0.3s;
        }
        th {
            background-color: #ff0000; /* สีพื้นหลังของหัวตาราง */
            color: #ffffff; /* สีข้อความของหัวตาราง */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* สีพื้นหลังของแถวที่คู่ */
        }
        tr:hover {
            background-color: #e9ecef; /* เปลี่ยนสีเมื่อเอาเมาส์ไปวาง */
        }
        .content {
            width: 80%;         /* กำหนดความกว้างของเนื้อหา */
    text-align: center; /* จัดตำแหน่งข้อความให้อยู่กลาง */
    padding: 20px;      /* เพิ่มระยะห่างภายใน */
    background-color: url('images/slider-main/j&t22.jpg') 
        }
        h3 {
    color: #ffffff;
    font-weight: 700;
    font-family: "Montserrat", sans-serif;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased !important;
    font-family: 'Kanit', sans-serif;
}
    </style>
</head>
<body>
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

    <div class="content">
        <h3>รายงานวันลาที่ใช้ไปของพนักงาน</h3>
        <table>
            <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อพนักงาน</th>
                    <th>วันลาป่วยที่ใช้ไป</th>
                    <th>วันลากิจที่ใช้ไป</th>
                    <th>วันลาพักร้อนที่ใช้ไป</th>
                    <th>วันลากรณีพิเศษที่ใช้ไป</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['employee_id']}</td>
                                <td>{$row['employee_name']}</td>
                                <td>{$row['sick_days_taken']}</td>
                                <td>{$row['personal_days_taken']}</td>
                                <td>{$row['vacation_days_taken']}</td>
                                <td>{$row['special_days_taken']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่มีข้อมูลการลา</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>รายงานวันลาคงเหลือของพนักงาน</h3>
        <table>
            <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อพนักงาน</th>
                    <th>วันลาป่วยคงเหลือ</th>
                    <th>วันลากิจคงเหลือ</th>
                    <th>วันลาพักร้อนคงเหลือ</th>
                    <th>วันลากรณีพิเศษคงเหลือ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                mysqli_data_seek($result, 0); // Reset result pointer to the start
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // คำนวณวันลาคงเหลือ
                        $sickDaysRemaining = $row['sick_leave_remaining'];
                        $personalDaysRemaining = $row['personal_leave_remaining'];
                        $vacationDaysRemaining = $row['vacation_leave_remaining'];
                        $specialDaysRemaining = $row['special_leave_remaining'];

                        echo "<tr>
                                <td>{$row['employee_id']}</td>
                                <td>{$row['employee_name']}</td>
                                <td>{$sickDaysRemaining}</td>
                                <td>{$personalDaysRemaining}</td>
                                <td>{$vacationDaysRemaining}</td>
                                <td>{$specialDaysRemaining}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่มีข้อมูลการลา</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
