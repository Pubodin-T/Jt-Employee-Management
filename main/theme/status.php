<?php
include("db_connection.php");
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบการดึง employee_id จาก session
$employee_id = $_SESSION['employee_id'];
echo "Employee ID: " . $employee_id . "<br>"; // เพิ่มการแสดง employee_id เพื่อตรวจสอบว่า session มีค่า

// ตรวจสอบบทบาทของผู้ใช้
$query = "SELECT role FROM employee WHERE employee_id = '$employee_id'";
$result = $conn->query($query);

if ($result === false || $result->num_rows == 0) {
    die("เกิดข้อผิดพลาดในการตรวจสอบสิทธิ์");
}

$row = $result->fetch_assoc();
$role = $row['role'];
echo "Role: " . $role . "<br>"; // แสดง role เพื่อตรวจสอบสิทธิ์

// ดึงข้อมูลประวัติการลาที่รออนุมัติหรืออนุมัติ
$query = "SELECT lr.*, e.employee_name, lt.leave_type_name,
        DATE_FORMAT(lr.start_date, '%d/%m/%y') as format_start_date,
        DATE_FORMAT(lr.end_date, '%d/%m/%y') as format_end_date,
        DATE_FORMAT(lr.created_at, '%d/%m/%y') as format_submit_date,
        CASE
          WHEN lr.status = '0' THEN 'รออนุมัติ'
          WHEN lr.status = '1' THEN 'อนุมัติ'
        END as status_name
        FROM leave_requests lr
        JOIN employee e ON lr.employee_id = e.employee_id
        JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id
        WHERE lr.employee_id = '$employee_id' 
        AND lr.status IN ('0', '1')  -- แสดงเฉพาะคำขอที่รออนุมัติหรืออนุมัติ
        ORDER BY lr.leave_requests_id ASC";

$result = $conn->query($query);
if ($result === false) {
    die("เกิดข้อผิดพลาดในการเรียกข้อมูล: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ประวัติการลา</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="icon" type="image/png" href="images/logoj&t.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    body {
      background-image: url('images/slider-main/j&t555.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
    .container {
      margin-top: 50px;
    }
    .card {
      border: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
    .card-header {
      background-color: #970000;
      color: white;
      font-size: 1.25rem;
      border-bottom: 1px solid #ff0707;
      font-family: 'Kanit', sans-serif;
    }
    .table thead th {
      background-color: #b50314;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        <h1>เช็คสถานะการลา</h1>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ลำดับ</th>
              <th>วันที่เริ่มต้น</th>
              <th>วันที่สิ้นสุด</th>
              <th>วันที่ส่งใบลา</th>
              <th>ประเภทการลา</th>
              <th>เหตุผล</th>
              <th>สถานะ</th>
              
            </tr>
          </thead>
          <tbody>
            <?php
           if ($result->num_rows > 0) {
            $counter = 1; // เริ่มนับจาก 1
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$counter}</td>"; // แสดงเลขลำดับ
                echo "<td>{$row['format_start_date']}</td>";
                echo "<td>{$row['format_end_date']}</td>";
                echo "<td>{$row['format_submit_date']}</td>";
                echo "<td>{$row['leave_type_name']}</td>";
                echo "<td>{$row['reason']}</td>";
        
                // เช็คสถานะของคำขอ
                if ($row['status'] == '0') { // รออนุมัติ
                    echo '<td>
                            <button class="btn btn-info"><i class="fas fa-spinner fa-spin"></i> รออนุมัติ</button>
                            <a href="edit_leave_request.php?id=' . $row['leave_requests_id'] . '" class="btn btn-warning">
                                <i class="fas fa-edit"></i> แก้ไข
                            </a>
                            <a href="cancel_leave_request.php?id=' . $row['leave_requests_id'] . '" 
                                class="btn btn-danger" 
                                onclick="return confirm(\'คุณต้องการยกเลิกคำขอนี้ใช่หรือไม่?\')">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                          </td>';
                } elseif ($row['status'] == '1') { // อนุมัติ
                    echo '<td><button class="btn btn-success"><i class="fas fa-check"></i> อนุมัติ</button></td>';
                } elseif ($row['status'] == '2') { // ไม่อนุมัติ
                    echo '<td><button class="btn btn-danger"><i class="fas fa-times"></i> ไม่อนุมัติ</button></td>';
                }
        
                echo "</tr>";
                $counter++; // เพิ่มเลขลำดับในแต่ละรอบ
            }
        } else {
            echo "<tr><td colspan='8' class='no-data'>ไม่มีข้อมูลการลา</td></tr>";
        }
        
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
