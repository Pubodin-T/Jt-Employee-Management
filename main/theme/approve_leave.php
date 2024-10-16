<?php
include("db_connection.php");
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบบทบาทของผู้ใช้
$employee_id = $_SESSION['employee_id'];
$query = "SELECT role FROM employee WHERE employee_id = '$employee_id'";
$result = $conn->query($query);

if ($result === false || $result->num_rows == 0) {
    die("เกิดข้อผิดพลาดในการตรวจสอบสิทธิ์");
}

$row = $result->fetch_assoc();
$role = $row['role'];

// ตรวจสอบให้แน่ใจว่าผู้ใช้มีบทบาทที่สามารถเข้าถึงหน้านี้ได้
if ($role !== '2') { // แก้ไขเงื่อนไขเป็นเพียง '2' เท่านั้น
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้");
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_requests_id = $conn->real_escape_string($_POST['leave_requests_id']);
    $action = $_POST['action'];
    $reason = isset($_POST['reason']) ? $conn->real_escape_string($_POST['reason']) : ''; // รับเหตุผลจากฟอร์ม

    if ($action == 'approve') {
        $update_query = "UPDATE leave_requests SET status = '1' WHERE leave_requests_id = '$leave_requests_id'";
    } elseif ($action == 'reject') {
        $update_query = "UPDATE leave_requests SET status = '2', reason = '$reason' WHERE leave_requests_id = '$leave_requests_id'";

        $select_req = "SELECT employee_id, leave_type_id, start_date, end_date  FROM leave_requests WHERE leave_requests_id = '$leave_requests_id'";
        $result_req = mysqli_query($conn, $select_req);
        $row_req = mysqli_fetch_assoc($result_req);
        $employee_id = $row_req['employee_id'];
        $start_date = $row_req['start_date'];
        $end_date = $row_req['end_date'];
        $select_emp  = "SELECT personal_leave_remaining, sick_leave_remaining, vacation_leave_remaining, special_leave_remaining FROM employee WHERE employee_id = $employee_id";
        $result_emp = mysqli_query($conn, $select_emp);
        $row_emp = mysqli_fetch_assoc($result_emp);
        
        if($row_req['leave_type_id'] == 1){
          $personal = $row_emp['personal_leave_remaining'];
          $employee_id = $row_req['employee_id'];
          $days_requested = (new DateTime($end_date))->diff(new DateTime($start_date))->days+1;
          $sum = ($personal+$days_requested);
          $update_emp = "UPDATE employee SET personal_leave_remaining = '$sum' WHERE employee_id ='$employee_id'";
          mysqli_query($conn, $update_emp);
        }
        if($row_req['leave_type_id'] == 2){
          $sick = $row_emp['sick_leave_remaining'];
          $employee_id = $row_req['employee_id'];
          $days_requested = (new DateTime($end_date))->diff(new DateTime($start_date))->days+1;
          print_r($days_requested);
          $sum = ($sick+$days_requested);
          $update_emp = "UPDATE employee SET sick_leave_remaining = '$sum' WHERE employee_id = '$employee_id'";
          mysqli_query($conn, $update_emp);
        }
        if($row_req['leave_type_id'] == 3){
          $vacation = $row_emp['vacation_leave_remaining'];
          $employee_id = $row_req['employee_id'];
          $days_requested = (new DateTime($end_date))->diff(new DateTime($start_date))->days+1;
          $sum = ($vacation+$days_requested);
          $update_emp = "UPDATE employee SET vacation_leave_remaining = '$sum' WHERE employee_id = '$employee_id'";
          mysqli_query($conn, $update_emp);
        }
        if($row_req['leave_type_id'] == 4){
          $special = $row_emp['special_leave_remaining'];
          $employee_id = $row_req['employee_id'];
          $days_requested = (new DateTime($end_date))->diff(new DateTime($start_date))->days+1;
          $sum = ($special+$days_requested);
          $update_emp = "UPDATE employee SET special_leave_remaining = '$sum' WHERE employee_id = '$employee_id'";
          mysqli_query($conn, $update_emp);
        }
        
    }

    if ($conn->query($update_query) === TRUE) {
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'การลาได้รับการอัปเดตเรียบร้อยแล้ว!',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location = 'approve_leave.php';
            });
            </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'ไม่สามารถอัปเดตการลาได้! " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
            </script>";
        echo "Error: " . $conn->error;
    }
}

// ดึงข้อมูลคำขอการลาที่รออนุมัติ
$query = "SELECT lr.*, e.employee_name, e.vacation_days_taken, lt.leave_type_name, lt.leave_type_id, 
                 DATE_FORMAT(lr.start_date, '%d/%m/%y') as format_start_date,
                 DATE_FORMAT(lr.end_date, '%d/%m/%y') as format_end_date,
                 DATE_FORMAT(lr.created_at, '%d/%m/%y') as format_submit_date, 
                 CASE
                   WHEN lr.status = '0' THEN 'รออนุมัติ'
                   WHEN lr.status = '1' THEN 'อนุมัติ'
                   WHEN lr.status = '2' THEN 'ไม่อนุมัติ'
                 END as status_name
          FROM leave_requests lr
          JOIN employee e ON lr.employee_id = e.employee_id
          JOIN leave_types lt ON lr.leave_type_id = lt.leave_type_id
          WHERE lr.status = '0' ORDER BY lr.leave_requests_id ASC";
$result = $conn->query($query);

// ตรวจสอบว่าการ query สำเร็จหรือไม่
if ($result === false) {
    die("เกิดข้อผิดพลาดในการเรียกข้อมูล: " . $conn->error);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'reject') {
  $leave_requests_id = $_POST['leave_requests_id'];
  $reason = $_POST['reason'];

  // ทำการอัปเดตสถานะและบันทึกเหตุผลในการไม่อนุมัติ
  $query = "UPDATE leave_requests SET status = '2', reason = ? WHERE leave_requests_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("si", $reason, $leave_requests_id);
  
  if ($stmt->execute()) {
      // สำเร็จ ให้ส่งผู้ใช้กลับไปยังหน้าหลักหรือหน้าประวัติการลา
      header('Location: approve_leave.php?message=reject_success');
      exit();
  } else {
      echo "เกิดข้อผิดพลาดในการไม่อนุมัติคำขอ: " . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>อนุมัติการลา</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">
  <style>
    body {
      background-image: url('images/slider-main/j&t17.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit สำหรับข้อความทั้งหมด */
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
      background-color: #262626;
      color: white;
      font-size: 1.25rem;
      border-bottom: 1px solid #ff0707;
      font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit สำหรับหัวการ์ด */
    }
    .table thead th {
      background-color: #303030;
      color: white;
      font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit สำหรับหัวตาราง */
    }
    .btn-approve {
      background-color: #28a745;
      color: white;
      border: none;
    }
    .btn-approve:hover {
      background-color: #218838;
    }
    .btn-reject {
      background-color: #dc3545;
      color: white;
      border: none;
    }
    .btn-reject:hover {
      background-color: #c82333;
    }
    .reason-container {
      display: none; /* ซ่อนกล่องเหตุผลเริ่มต้น */
      margin-top: 10px;
    }
  </style>
</head>
<body>
<div class="site-navigation">
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
                            <li class="active"><a href="admin_dashboard.php">หน้าหลัก</a></li>
                            <li class="active"><a href="logout.php">ออกจากระบบ</a></li>
                          </ul>
                      </li>

                      <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ข้อมูลพนักงาน <i class="fa fa-angle-down"></i></a>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="employee_list.php">ข้อมูลพนักงาน</a></li>
                          </ul>
                      </li>
              
                      <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ลงทะเบียนพนักงานใหม่ <i class="fa fa-angle-down"></i></a>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="addemployee.php">ลงทะเบียนพนักงานใหม่</a></li>
                          </ul>
                      </li>
              
                      <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ประวัติการลา <i class="fa fa-angle-down"></i></a>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="leave_history.php">เช็คประวัติการลาพนักงาน</a></li>
                          </ul>
                      </li>
              
                      <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">เช็คสถานะการลา <i class="fa fa-angle-down"></i></a>
                          <ul class="dropdown-menu" role="menu">
                            <li><a href="approve_leave.php">ใบคำขอลา</a></li>
                          </ul>
                      </li>
                    </ul>
                </div>
              </nav>
          </div>
        </div>

    </div>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        <h1>อนุมัติการลา</h1>
      </div>
      <div class="card-body">
        <!-- ตารางการแสดงคำขอการลา -->
        <table class="table">
    <thead>
        <tr>
            <th>ลำดับ</th>
            <th>ชื่อพนักงาน</th>
            <th>ประเภทการลา</th>
            <th>วันเริ่มลา</th>
            <th>วันสิ้นสุดลา</th>
            <th>วันที่ส่งคำขอ</th> <!-- เพิ่มคอลัมน์วันที่ส่งคำขอ -->
            <th>ใบรับรองแพทย์</th> <!-- เพิ่มคอลัมน์ใบรับรองแพทย์ -->
            <th>สถานะ</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $index = 1; // เริ่มต้นลำดับที่ 1
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $index++; ?></td> <!-- แสดงลำดับและเพิ่มค่าขึ้น -->
            <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
            <td><?php echo htmlspecialchars($row['leave_type_name']); ?></td>
            <td><?php echo htmlspecialchars($row['format_start_date']); ?></td>
            <td><?php echo htmlspecialchars($row['format_end_date']); ?></td>
            <td><?php echo htmlspecialchars($row['format_submit_date']); ?></td> <!-- แสดงวันที่ส่งคำขอ -->
            <td>
                <?php 
                // เพิ่มการดีบักเพื่อแสดงเส้นทางใบรับรองแพทย์
                // echo "Certificate Path: " . htmlspecialchars($row['certificate']) . "<br>";
                if (!empty($row['certificate'])): ?>
                    <a href="<?php echo 'uploads/' . htmlspecialchars($row['certificate']); ?>" target="_blank">ดูใบรับรอง</a>
                <?php else: ?>
                    ไม่มีใบรับรอง
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($row['status_name']); ?></td>
            <td>
            <form method="post" class="d-inline">
                    <input type="hidden" name="leave_requests_id" value="<?php echo htmlspecialchars($row['leave_requests_id']); ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-approve">อนุมัติ</button>
                </form>

            <form method="post" class="d-inline">
    <input type="hidden" name="leave_requests_id" value="<?php echo htmlspecialchars($row['leave_requests_id']); ?>">
    <input type="hidden" name="action" value="reject">
    <button type="button" class="btn btn-reject">ไม่อนุมัติ</button>
    <div class="reason-container">
        <label for="reason">เหตุผล:</label>
        <select name="reason" required>
            <option value="">-- กรุณาเลือกเหตุผล --</option>
            <option value="พนักงานไม่เพียงพอ">พนักงานไม่เพียงพอ</option>
            <option value="ติดต่อหัวหน้า">ติดต่อหัวหน้า</option>
            <option value="เกินกำหนด">กิจกรรมสำคัญในวันที่ขอลา</option>
            <option value="ลาซ้ำ">คำขอลาซ้ำ</option>

            <!-- คุณสามารถเพิ่มเหตุผลอื่น ๆ ได้ที่นี่ -->
        </select>
        <button type="submit" class="btn btn-danger mt-2">ยืนยัน</button>
    </div>
</form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // แสดงกล่องเหตุผลเมื่อกดปุ่มไม่อนุมัติ
    document.querySelectorAll('.btn-reject').forEach(button => {
      button.addEventListener('click', function() {
        const reasonContainer = this.closest('form').querySelector('.reason-container');
        reasonContainer.style.display = 'block';
      });
    });
  </script>
</body>
</html>
