<?php
session_start();
include('db_connection.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบแล้ว
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// รับข้อมูลจากฟอร์ม
$employee_id = $_SESSION['employee_id'];  // ใช้ ID จาก session
$query = "SELECT employee_name, position FROM employee WHERE employee_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$employee_name = $employee['employee_name'];
$position = $employee['position'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // ตรวจสอบประเภทการลาและจัดการไฟล์แนบ
    if ($leave_type === 'ลาป่วย' && isset($_FILES['certificate']) && $_FILES['certificate']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['certificate']['name'];
        $file_tmp = $_FILES['certificate']['tmp_name'];
        $file_path = 'uploads/' . $file_name;
        move_uploaded_file($file_tmp, $file_path);
    } else {
        $file_path = null;
    }

    // บันทึกข้อมูลการลา
    $query = "INSERT INTO leave_requests (employee_id, employee_name, position, leave_type, start_date, end_date, reason, certificate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssss', $employee_id, $employee_name, $position, $leave_type, $start_date, $end_date, $reason, $file_path);

    if ($stmt->execute()) {
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "สำเร็จ",
                        text: "คำขอการลาของคุณได้รับการส่งเรียบร้อยแล้ว",
                        type: "success"
                    }, function() {
                        window.location = "employee.php";
                    });
                }, 1000);
            </script>';
    } else {
        echo '<script>
                setTimeout(function() {
                    swal({
                        title: "เกิดข้อผิดพลาด",
                        text: "ไม่สามารถส่งคำขอได้ โปรดลองอีกครั้ง",
                        type: "error"
                    }, function() {
                        window.location = "form.php";
                    });
                }, 1000);
            </script>';
    }

    $stmt->close();
}
$conn->close();
?>
