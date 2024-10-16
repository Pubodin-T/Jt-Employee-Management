<?php
session_start();
include("db_connection.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// รับข้อมูลจากแบบฟอร์ม
$employee_id = $_POST['employee_id'];
$employee_name = $_POST['employee_name'];
$position = $_POST['position'];
$leave_type_id = $_POST['leave_type_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$reason = $_POST['reason'];

// ตรวจสอบการอัปโหลดไฟล์
$attachment = '';
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['attachment']['tmp_name'];
    $file_name = basename($_FILES['attachment']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'pdf'];
    
    if (in_array($file_ext, $allowed_exts)) {
        $upload_dir = 'uploads/';
        $file_path = $upload_dir . uniqid() . '.' . $file_ext;
        if (move_uploaded_file($file_tmp, $file_path)) {
            $attachment = $file_path;
        } else {
            $attachment = 'ไม่สามารถอัปโหลดไฟล์';
        }
    } else {
        $attachment = 'ประเภทไฟล์ไม่ถูกต้อง';
    }
}

// บันทึกข้อมูลการลาในฐานข้อมูล
$query = "INSERT INTO leave_requests (employee_id, employee_name, position, leave_type_id, start_date, end_date, reason, certificate, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssss", $employee_id, $employee_name, $position, $leave_type_id, $start_date, $end_date, $reason, $attachment);

if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'message' => 'คำขอลาถูกบันทึกเรียบร้อยแล้ว'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'เกิดข้อผิดพลาดในการบันทึกคำขอลา'
    ];
}

$stmt->close();
$conn->close();

// ส่งผลลัพธ์กลับเป็น JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
<?php
session_start();
include("db_connection.php");
/////////////////////////////////////////////////////////////////////////
// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบการส่งแบบฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'];
    $position = $_POST['position'];
    $leave_type_id = $_POST['leave_type_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // จัดการการอัปโหลดไฟล์
    $attachment = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($_FILES['attachment']['name']);
        
        // ตรวจสอบว่าไดเรกทอรีที่ใช้สำหรับการอัปโหลดไฟล์มีอยู่และเขียนได้
        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            echo "<script>
                    alert('ไดเรกทอรีสำหรับการอัปโหลดไฟล์ไม่พร้อมใช้งาน');
                    window.history.back();
                  </script>";
            exit();
        }

        // ย้ายไฟล์ที่อัปโหลดไปยังไดเรกทอรี
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_file)) {
            $attachment = basename($_FILES['attachment']['name']);
        } else {
            echo "<script>
                    alert('ไม่สามารถอัปโหลดไฟล์ได้');
                    window.history.back();
                  </script>";
            exit();
        }
    }

    // เตรียมคำสั่ง SQL
    $query = "INSERT INTO leave_requests (employee_id, employee_name, position, leave_type_id, start_date, end_date, reason, certificate, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // ผูกพารามิเตอร์
    $stmt->bind_param("ssssssss", $employee_id, $employee_name, $position, $leave_type_id, $start_date, $end_date, $reason, $attachment);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // การส่งข้อมูลสำเร็จ
        $stmt->close();
        $conn->close();

        // เปลี่ยนเส้นทางไปยังหน้าเช็คสถานะการลา
        header('Location: status.php');
        exit();
    } else {
        // เกิดข้อผิดพลาดในการส่งข้อมูล
        echo "<script>
                alert('เกิดข้อผิดพลาดในการส่งคำขอการลา');
                window.history.back();
              </script>";
    }
}

?>
