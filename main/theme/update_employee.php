<?php
session_start();
include("db_connection.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 2) {
    echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['employee_id'], $_POST['employee_name'], $_POST['employee_position'], $_POST['employee_dob'], $_POST['employee_phone'], $_POST['employee_email'])) {
        $employee_id = $_POST['employee_id'];
        $employee_name = $_POST['employee_name'];
        $employee_position = $_POST['employee_position'];
        $employee_dob = $_POST['employee_dob'];
        $employee_phone = $_POST['employee_phone'];
        $employee_email = $_POST['employee_email'];
        $employee_password = $_POST['employee_password'];

        // สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูลพนักงาน
        $sql = "UPDATE employee SET employee_name = ?, employee_position = ?, employee_dob = ?, employee_phone = ?, employee_email = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $employee_name, $employee_position, $employee_dob, $employee_phone, $employee_email, $employee_id);

        if($employee_password){
            $employee_id = $_POST['employee_id'];
            $sql_acc = "SELECT * FROM employee WHERE employee_id = ?";
            $stmt_acc = $conn->prepare($sql_acc);
            $stmt_acc->bind_param('s', $employee_id);
            $stmt_acc->execute();
            $result_acc = $stmt_acc->get_result();
            $result_acc2 = $result_acc->fetch_assoc();
            $account_id = $result_acc2['account_id'];

            $update_acc = $conn->prepare("UPDATE account SET password = ? WHERE account_id = ?");
            $update_acc->bind_param("ss", $employee_password, $account_id);
            $update_acc->execute();
        }

        if ($stmt->execute()) {
            header("Location: employee_list.php?status=success");
        } else {
            header("Location: employee_list.php?status=error");
        }
        $stmt->close();
    } else {
        header("Location: employee_list.php?status=invalid_request");
    }
} else {
    header("Location: employee_list.php?status=invalid_request");
}

$conn->close();
?>
