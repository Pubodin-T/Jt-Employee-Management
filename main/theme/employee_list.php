<?php
session_start();
include("db_connection.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 2 && $_SESSION['status'] !== 'active') {
    echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    exit();
}

// ดึงข้อมูลพนักงานทั้งหมดจากฐานข้อมูล
$sql = "SELECT employee_id, employee_name, employee_position, employee_dob, DATE_FORMAT(employee_dob, '%d/%m/%Y') as employee_dob_s, employee_phone, employee_email, vacation_days_taken, sick_leave_remaining, personal_leave_remaining, vacation_leave_remaining, special_leave_remaining, role,
        CASE
            WHEN status = '1' THEN 'ทำงาน'
            WHEN status = '0' THEN 'ยุติการทำงาน'
        END AS status_name 
        FROM employee"; // เพิ่ม status
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert2 -->
    <script src="status_change.js" defer></script> <!-- เชื่อมโยงไฟล์ JavaScript -->
    <style>
        /* สไตล์ CSS ของคุณ */
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('images/slider-main/j&t17.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        h1 {
            text-align: center;
            color: #292929;
            margin-top: 20px;
            font-weight: 600;
        }
        
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #8b0b0b;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        td {
            border-right: 1px solid #ddd;
        }
        td:last-child {
            border-right: none;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }
        /* สไตล์สำหรับปุ่มและเลือก */
        .styled-select, .styled-button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #5c5c5c;
            color: white;
            transition: background-color 0.3s ease;
            margin-top: 5px;
        }
        .styled-select:hover, .styled-button:hover {
            background-color: #4b4b4b;
        }
        .styled-button {
            background-color: #8b0b0b;
            margin-left: 5px;
        }
        .styled-button:hover {
            background-color: #7a0a0a;
        }
        /* ปรับปรุงปุ่มแก้ไข */
        .edit-button {
            background-color: #972400;
            color: white; /* สีฟ้า */
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .edit-button:hover {
            background-color: #0056b3; /* สีเข้มขึ้นเมื่อ hover */
        }
    </style>
</head>
<body>
    
    <h1>ข้อมูลพนักงาน</h1>
    
    <?php if (isset($_GET['status'])): ?>
        <script>
            <?php if ($_GET['status'] === 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'สถานะถูกเปลี่ยนเรียบร้อยแล้ว',
                });
            <?php elseif ($_GET['status'] === 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ',
                });
            <?php elseif ($_GET['status'] === 'prepare_error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL',
                });
            <?php elseif ($_GET['status'] === 'invalid_request'): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'คำขอไม่ถูกต้อง',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อพนักงาน</th>
                <th>ตำแหน่ง</th>
                <th>อายุ</th>
                <th>วัน/เดือน/ปีเกิด</th>
                <th>เบอร์โทร</th>
                <th>Email</th>
                <th>ลาป่วยคงเหลือ</th>
                <th>ลากิจคงเหลือ</th>
                <th>ลาพักร้อน</th>
                <th>ลากรณีพิเศษ</th>
                <th>สถานะ</th> <!-- เพิ่มสถานะ -->
                <th>เปลี่ยนสถานะ</th>
                <th>แก้ไข</th> <!-- เพิ่มคอลัมน์สำหรับปุ่มแก้ไข -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if ($row['role'] !== 'admin') {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_position']) . "</td>";
                        
                        $dob = new DateTime($row['employee_dob']);
                        $today = new DateTime('today');
                        $age = $dob->diff($today)->y;

                        echo "<td>" . htmlspecialchars($age) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_dob_s']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_email']) . "</td>";
                        echo "<td>" . (isset($row['sick_leave_remaining']) ? htmlspecialchars($row['sick_leave_remaining']) : 'N/A') . "</td>";
                        echo "<td>" . (isset($row['personal_leave_remaining']) ? htmlspecialchars($row['personal_leave_remaining']) : 'N/A') . "</td>";
                        echo "<td>" . (isset($row['vacation_leave_remaining']) ? htmlspecialchars($row['vacation_leave_remaining']) : 'N/A') . "</td>";
                        echo "<td>" . (isset($row['special_leave_remaining']) ? htmlspecialchars($row['special_leave_remaining']) : 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['status_name']) . "</td>"; // แสดงสถานะ
                        echo "<td>
                            <form method='POST' action='change_status.php'>
                                <input type='hidden' name='employee_id' value='" . htmlspecialchars($row['employee_id']) . "'>
                                <select name='new_status'>
                                    <option value='1' " . ($row['status_name'] == '1' ? 'ทำงาน' : '') . ">ทำงาน</option>
                                    <option value='0' " . ($row['status_name'] == '0' ? 'ลาออก' : '') . ">ลาออก</option>
                                </select>
                                <button type='submit'>เปลี่ยนสถานะ</button>
                            </form>
                        </td>";
                        echo "<td>
                            <form method='POST' action='edit_employee.php'>
                                <input type='hidden' name='employee_id' value='" . htmlspecialchars($row['employee_id']) . "'>
                                <button type='submit' class='edit-button'>แก้ไข</button>
                            </form>
                        </td>"; // ปุ่มแก้ไข
                        echo "</tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='13' class='no-data'>ไม่พบข้อมูลพนักงาน</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>


<?php
$conn->close();
?>
