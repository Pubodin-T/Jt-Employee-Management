<?php
include("db_connection.php");
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

$employee_id = $_SESSION['employee_id'];

// ดึงข้อมูลพนักงานและลาพักร้อนสะสม
$current_year = date('Y');
$employee_query = "SELECT sick_leave_remaining, personal_leave_remaining, vacation_leave_remaining, special_leave_remaining, vacation_days_accumulated 
                   FROM employee 
                   WHERE employee_id = '$employee_id'";
$employee_result = $conn->query($employee_query);

if ($employee_result === false || $employee_result->num_rows == 0) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูลพนักงาน");
}

$employee_row = $employee_result->fetch_assoc();
$sick_leave_remaining = $employee_row['sick_leave_remaining'] ?? 0;
$personal_leave_remaining = $employee_row['personal_leave_remaining'] ?? 0;
$vacation_leave_remaining = $employee_row['vacation_leave_remaining'] ?? 0;
$special_leave_remaining = $employee_row['special_leave_remaining'] ?? 0;
$vacation_days_accumulated = $employee_row['vacation_days_accumulated'] ?? 0;

// ดึงข้อมูลลาพักร้อนที่ใช้ในปีปัจจุบัน
$vacation_current_year_query = "
    SELECT SUM(DATEDIFF(end_date, start_date) + 1) as used_vacation_leave
    FROM leave_requests
    WHERE employee_id = '$employee_id' AND leave_type_id = 3 AND YEAR(start_date) = '$current_year'
";
$vacation_current_year_result = $conn->query($vacation_current_year_query);

$used_vacation_leave = 0;  // กำหนดค่าเริ่มต้น
if ($vacation_current_year_result && $vacation_current_year_result->num_rows > 0) {
    $vacation_current_year_row = $vacation_current_year_result->fetch_assoc();
    $used_vacation_leave = $vacation_current_year_row['used_vacation_leave'] ?? 0;  // ใช้ค่า 0 ถ้าไม่มีข้อมูล
}

// คำนวณยอดคงเหลือปีปัจจุบัน
$vacation_leave_remaining = ($vacation_leave_remaining ?? 0) - ($used_vacation_leave ?? 0);

// ดึงข้อมูลลาพักร้อนสะสมของปีที่แล้ว
$vacation_history_query = "
    SELECT YEAR(start_date) as leave_year, SUM(DATEDIFF(end_date, start_date) + 1) as total_vacation_days
    FROM leave_requests
    WHERE employee_id = '$employee_id' AND leave_type_id = 3 AND YEAR(start_date) < '$current_year'
    GROUP BY leave_year
";
$vacation_history_result = $conn->query($vacation_history_query);

// เก็บข้อมูลยอดสะสมรายปี
$vacation_history = [];
if ($vacation_history_result && $vacation_history_result->num_rows > 0) {
    while ($row = $vacation_history_result->fetch_assoc()) {
        $vacation_history[$row['leave_year']] = $row['total_vacation_days'];
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล (ย้ายไปที่ด้านล่างสุดของไฟล์)
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ประวัติการลา</title>
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
        background-image: url('images/slider-main/j&t555.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: 'Kanit', sans-serif; /* กำหนดฟอนต์เป็น Kanit */
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
        background-color: #870000;
        color: white;
        font-size: 1.25rem;
        border-bottom: 1px solid #ff0707;
    }
    .table thead th {
        background-color: #870000;
        color: white;
    }
    .table tbody tr td {
        background-color: white; /* เปลี่ยนพื้นหลังของเซลล์ให้เป็นสีขาว */
        color: black; /* เปลี่ยนสีตัวอักษรให้มองเห็นได้ชัดเจน */
    }
    /* เพิ่มขอบให้เซลล์ตารางดูมีมิติ */
    .table, .table th, .table td {
        border: 1px solid #ddd; /* ขอบสีเทาอ่อน */
        
    }
    
    .table {
        background-color: white; /* กำหนดสีพื้นหลังเป็นสีขาว */
    }

    .table-bordered {
        border: 1px solid #dee2e6; /* เปลี่ยนสีเส้นขอบได้ตามต้องการ */
    }
    h3 {
            color: white; /* เปลี่ยนเป็นสีที่คุณต้องการ */
            
   
    text-align: center; /* จัดกลางแนวนอน */
    margin-top: 20px; /* กำหนดระยะห่างด้านบน */
}
        
</style>
  </style>
</head>
<body>
  <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <nav class="navbar navbar-expand-lg navbar-dark p-0">
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                  </button>
                  <div id="navbar-collapse" class="collapse navbar-collapse">
                      <ul class="nav navbar-nav mr-auto">
                          <li class="nav-item dropdown active">
                              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">หน้าหลัก <i class="fa fa-angle-down"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                  <li class="active"><a href="index.html">หน้าหลัก</a></li>
                                  <li class="active"><a href="logout.php">ออกจากระบบ</a></li>
                              </ul>
                          </li>
                          <li class="nav-item dropdown">
                              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ข้อมูลพนักงาน <i class="fa fa-angle-down"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                  <li><a href="employee.php">ข้อมูลพนักงาน</a></li>
                              </ul>
                          </li>
                          <li class="nav-item dropdown">
                              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">แบบฟอร์มการลา <i class="fa fa-angle-down"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                  <li><a href="form.php">แบบฟอร์มการลา</a></li>
                              </ul>
                          </li>
                          <li class="nav-item dropdown">
                              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ประวัติการลา <i class="fa fa-angle-down"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                  <li><a href="history.php">ประวัติการลา</a></li>
                              </ul>
                          </li>
                          <li class="nav-item dropdown">
                              <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">เช็คสถานะการลา <i class="fa fa-angle-down"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                  <li><a href="status.php">เช็คสถานะการลา</a></li>
                              </ul>
                          </li>
                      </ul>
                  </div>
              </nav>
          </div>
          <!--/ Col end -->
      </div>
      <!--/ Row end -->

      <!-- ตารางยอดคงเหลือการลา -->
      <h3>ยอดคงเหลือการลา</h3>
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>ประเภทการลา</th>
                  <th>ยอดคงเหลือ</th>
                  <th>ยอดสะสม (รายปี)</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>ลาป่วย</td>
                  <td><?php echo htmlspecialchars($employee_row['sick_leave_remaining']); ?> วัน</td>
                  <td></td>
              </tr>
              <tr>
                  <td>ลากิจ</td>
                  <td><?php echo htmlspecialchars($employee_row['personal_leave_remaining']); ?> วัน</td>
                  <td></td>
              </tr>
              <tr>
                  <td>ลาพักร้อน</td>
                  <td><?php echo htmlspecialchars($employee_row['vacation_leave_remaining']); ?> วัน</td>
                  <td>
                      <?php echo htmlspecialchars($employee_row['vacation_days_accumulated']);
                      ?>
                      วัน
                  </td>
              </tr>
              <tr>
                  <td>ลาพิเศษ</td>
                  <td><?php echo htmlspecialchars($special_leave_remaining); ?> วัน</td>
                  <td></td>
              </tr>
          </tbody>
      </table>

      <!-- ตารางประวัติการลา -->
      <h3>เช็คประวัติการลา</h3>
      <table class="table table-striped">
          <thead>
              <tr>
                  <th>ประเภทการลา</th>
                  <th>วันที่เริ่มต้น</th>
                  <th>วันที่สิ้นสุด</th>
                  <th>วันที่ส่งคำขอลา</th>
                  <th>เหตุผล</th>
                  <th>สถานะ</th>
              </tr>
          </thead>
          <tbody>
              <?php
              // ดึงประวัติการลา
              $leave_history_query = " SELECT lr.leave_type_id, DATE_FORMAT(lr.start_date,'%d/%m/%Y') as start_date_t, DATE_FORMAT(lr.end_date,'%d/%m/%Y') as end_date_t, DATE_FORMAT(lr.submit_date,'%d/%m/%Y') as submit_date, lr.reason, lr.status, lt.leave_type_name
                  FROM leave_requests lr
                  JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id
                  WHERE lr.employee_id = '$employee_id'
                  ORDER BY lr.leave_requests_id DESC ";
              $leave_history_result = $conn->query($leave_history_query);

              while ($row = $leave_history_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['leave_type_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['start_date_t']) . "</td>";
                echo "<td>" . htmlspecialchars($row['end_date_t']) . "</td>";
                echo "<td>" . htmlspecialchars($row['submit_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                
                // ตรวจสอบค่า status และแสดงข้อความแทนตัวเลข
                if ($row['status'] == 1) {
                    echo "<td>อนุมัติ</td>";
                } elseif ($row['status'] == 2) {
                    echo "<td>ไม่อนุมัติ</td>";
                } else {
                    echo "<td>รอการอนุมัติ</td>"; // แสดงเมื่อ status เป็นค่าว่างหรือยังไม่ถูกพิจารณา
                }
            
                echo "</tr>";
            }
              
              ?>
          </tbody>
      </table>
  </div>
  <!--/ Container end -->

  <!-- Javascript Files -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
