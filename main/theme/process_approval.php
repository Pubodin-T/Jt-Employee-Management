<?php
include("db_connection.php");

// ตรวจสอบการล็อกอิน
session_start();
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_request_id = $_POST['leave_request_id'];
    $action = $_POST['action'];
    $approver_id = $_SESSION['employee_id']; // หัวหน้าที่อนุมัติ

    // ดึงข้อมูลคำขอการลา
    $query = "SELECT * FROM leave_requests WHERE id = '$leave_request_id'";
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    $request = $result->fetch_assoc();

    if ($action == 'approve') {
        $employee_id = $request['employee_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $leave_days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;

        // อัปเดตวันลาคงเหลือของพนักงาน
        $update_query = "UPDATE employees SET vacation_days_taken = vacation_days_taken - $leave_days WHERE employee_id = '$employee_id'";
        if (!$conn->query($update_query)) {
            die("Update failed: " . $conn->error);
        }

        // อัปเดตสถานะคำขอการลา
        $update_request_query = "UPDATE leave_requests SET status = 'approved', approver_id = '$approver_id' WHERE id = '$leave_request_id'";
        if (!$conn->query($update_request_query)) {
            die("Update failed: " . $conn->error);
        }
    } else if ($action == 'reject') {
        // อัปเดตสถานะคำขอการลา
        $update_request_query = "UPDATE leave_requests SET status = 'rejected', approver_id = '$approver_id' WHERE id = '$leave_request_id'";
        if (!$conn->query($update_request_query)) {
            die("Update failed: " . $conn->error);
        }
    }

    $conn->close();
    header('Location: approve_leave.php');
    exit();
}
?>
