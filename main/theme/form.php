<?php
session_start();
include("db_connection.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id']) && $_SESSION['status'] !== '1') {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลพนักงานจากฐานข้อมูล
$employee_id = $_SESSION['employee_id'];

$query = "SELECT employee_name, employee_position FROM employee WHERE employee_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();
    $_SESSION['employee_name'] = $employee['employee_name'];
    $_SESSION['employee_position'] = $employee['employee_position'];
} else {
    $_SESSION['employee_name'] = 'ข้อมูลไม่พบ';
    $_SESSION['employee_position'] = 'ข้อมูลไม่พบ';
}

$employee_name = $_SESSION['employee_name'];
$employee_position = $_SESSION['employee_position'];
$stmt->close();

// ดึงข้อมูลประเภทการลา
$query = "SELECT leave_type_id, leave_type_name FROM leave_types";
$leave_type_result = $conn->query($query);

if (!$leave_type_result) {
    die("Error fetching leave types: " . $conn->error);
}

// ตรวจสอบการส่งข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // print_r('TEST_FFF');
    $employee_id = $_SESSION['employee_id'];
    $leave_type_id = $_POST['leave_type_id']; // ประเภทการลาที่พนักงานเลือกจากฟอร์ม
    $start_date = $_POST['start_date']; // วันที่เริ่มลา
    $end_date = $_POST['end_date']; // วันที่สิ้นสุดลา
    $reason = $_POST['reason'] == null ? '' : $_POST['reason']; // เหตุผลการลา
    $contact = $_POST['contact'] == null ? '' : $_POST['contact'];
    $days_requested = (new DateTime($end_date))->diff(new DateTime($start_date))->days+1; // คำนวณจำนวนวันลาที่ขอ

    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] == 0) {
        $target_dir = "uploads/"; // โฟลเดอร์สำหรับเก็บไฟล์ที่อัปโหลด
        $file_name = basename($_FILES["certificate"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบประเภทไฟล์ที่อนุญาต (เช่น jpg, png, pdf)
        $allowed_types = array('jpg', 'jpeg', 'png', 'pdf');
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["certificate"]["tmp_name"], $target_file)) {
                $certificate = $file_name;
            } else {
                echo "ขออภัย, เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
                $certificate = null;
            }
        } else {
            echo "ประเภทไฟล์ที่อัปโหลดไม่ถูกต้อง. รองรับเฉพาะไฟล์ประเภท: jpg, jpeg, png, pdf.";
            $certificate = null;
        }
    } else {
        echo " ";
        $certificate = null;
    }

    if ($start_date && $end_date) {
        
        // ตรวจสอบประเภทการลาและวันลาคงเหลือ
        $query = "SELECT sick_leave_remaining, personal_leave_remaining, vacation_leave_remaining, special_leave_remaining FROM employee WHERE employee_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();

        if (!$employee) {
            echo "<script>
                    Swal.fire({
                        title: 'ล้มเหลว',
                        text: 'ไม่พบข้อมูลพนักงาน.',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                  </script>";
            exit();
        }

        // ตรวจสอบว่ามีวันลาคงเหลือเพียงพอหรือไม่
        $has_sufficient_leave = false;
        switch ($leave_type_id) {
            case '2':
                $has_sufficient_leave = $employee['sick_leave_remaining'] >= $days_requested;
                break;
            case '1':
                $has_sufficient_leave = $employee['personal_leave_remaining'] >= $days_requested;
                break;
            case '3':
                $has_sufficient_leave = $employee['vacation_leave_remaining'] >= $days_requested;
                break;
            case '4':
                $has_sufficient_leave = $employee['special_leave_remaining'] >= $days_requested;
                break;
            default:
                echo "<script>
                        Swal.fire({
                            title: 'ล้มเหลว',
                            text: 'ประเภทการลาที่เลือกไม่ถูกต้อง.',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    </script>";
                exit();
        }

        if ($has_sufficient_leave) {
            // อัปเดตจำนวนวันลาคงเหลือในตาราง employees
            switch ($leave_type_id) {
                case '2':
                    $update_query = "UPDATE employee SET sick_leave_remaining = sick_leave_remaining - ? WHERE employee_id = ?";
                    break;
                case '1':
                    $update_query = "UPDATE employee SET personal_leave_remaining = personal_leave_remaining - ? WHERE employee_id = ?";
                    break;
                case '3':
                    $update_query = "UPDATE employee SET vacation_leave_remaining = vacation_leave_remaining - ? WHERE employee_id = ?";
                    break;
                case '4':
                    $update_query = "UPDATE employee SET special_leave_remaining = special_leave_remaining - ? WHERE employee_id = ?";
                    break;
            }
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("is", $days_requested, $employee_id);
            $stmt->execute();
            

            // SQL สำหรับการบันทึกข้อมูลการลา
            $insert_query = "INSERT INTO leave_requests (employee_id, employee_name, leave_type_id, start_date, end_date, reason, certificate, contact) 
            VALUES ('$employee_id', '$employee_name', '$leave_type_id', '$start_date', '$end_date', '$reason', '$certificate', $contact)"; // เปลี่ยน 'pending' เป็น '0'
            
            if ($conn->query($insert_query) === TRUE) {
            echo "<script>
                alert('ส่งใบคำขอลาเรียบร้อย');
                window.location.href = 'history.php';
                </script>";
                exit(); // Make sure to use exit() to stop the script execution after the redirect
            } else {
            echo "เกิดข้อผิดพลาด: " . $stmt->error;
            }
        } else {
          echo "<script>
          alert('วันลาของคุณไม่เพียงพอ');
            window.location.href = 'history.php';
            </script>";
            exit(); // Make sure to use exit() to stop the script execution after the redirect อสวกาหวก Sarangg
        }



        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>แบบฟอร์มการลา - J&T</title>
  <link rel="icon" type="image/png" href="images/logoj&t.png">
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      background-image: url('images/slider-main/j&t555.jpg');
      background-size: cover; 
      background-position: center; 
      background-repeat: no-repeat; 
      background-attachment: fixed; 
    }
    .employee-form-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #f9f9f9;
    }
    .employee-form-container h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #000000;
    }
    .employee-form-container .form-group {
      margin-bottom: 15px;
    }
    .employee-form-container label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .employee-form-container input,
    .employee-form-container select,
    .employee-form-container textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    .employee-form-container .btn {
      background-color: #28a745;
      color: #ffffff;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    .employee-form-container .btn:hover {
      background-color: #218838;
    }
    .footer {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<div class="employee-form-container">
    <h1>แบบฟอร์มการลา</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="leave_type_id">ประเภทการลา:</label>
            <select name="leave_type_id" id="leave_type_id" required>
                <option value="">เลือกประเภทการลา</option>
                <?php while ($leave_type = $leave_type_result->fetch_assoc()): ?>
                    <option value="<?php echo $leave_type['leave_type_id']; ?>"><?php echo $leave_type['leave_type_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">วันที่เริ่มลา:</label>
            <input type="date" name="start_date" id="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">วันที่สิ้นสุดลา:</label>
            <input type="date" name="end_date" id="end_date" required>
        </div>
        <div class="form-group">
            <label for="reason">เหตุผลการลา:</label>
            <textarea name="reason" id="reason" rows="4" ></textarea>
        </div>
        <div class="form-group">
            <label for="contact">ข้อมูลการติดต่อ(กรอกหมายเลขโทรศัพท์):</label>
            <input type="text" name="contact" id="contact" >
        </div>
        <div class="form-group">
            <label for="certificate">ไฟล์ใบรับรองแพทย์ (ถ้ามี):</label>
            <input type="file" name="certificate" id="certificate" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <button type="submit" class="btn">ส่งคำขอ</button>
    </form>
    <div class="footer">
        <p>© 2024 J&T Express</p>
    </div>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/bootstrap.bundle.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const leaveTypeSelect = document.getElementById("leave_type_id");
        const certificateField = document.getElementById("certificate").parentElement;
        const reasonField = document.getElementById("reason").parentElement;

        // ฟังก์ชันซ่อน/แสดงฟิลด์ตามประเภทการลา
        function updateFields() {
            const leaveType = leaveTypeSelect.value;
            
            if (leaveType === '2') { // ลาป่วย
                certificateField.style.display = 'block';
                reasonField.style.display = 'block';
            } else if (leaveType === '1' || leaveType === '3') { // ลากิจ, ลาพักร้อน
                certificateField.style.display = 'none';
                reasonField.style.display = 'none';
            } else if (leaveType === '4') { // ลากรณีพิเศษ
                certificateField.style.display = 'block';
                reasonField.style.display = 'block';
            } else {
                // กรณีเลือกไม่มีประเภท
                certificateField.style.display = 'none';
                reasonField.style.display = 'block';
            }
        }

        // เรียกใช้ฟังก์ชันเมื่อมีการเปลี่ยนแปลงประเภทการลา
        leaveTypeSelect.addEventListener("change", updateFields);

        // เรียกใช้ฟังก์ชันตอนโหลดหน้า
        updateFields();
    });
</script>

</body>
</html>
