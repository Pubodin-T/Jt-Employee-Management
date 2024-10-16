<?php
session_start();
include("db_connection.php");

// ตรวจสอบว่ามีข้อมูลส่งมาจากฟอร์มหรือไม่
if (isset($_POST['username']) && isset($_POST['password'])) {
    // รับข้อมูลจากฟอร์มและทำการทำความสะอาดข้อมูลเพื่อป้องกัน SQL Injection
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // สร้าง SQL query เพื่อดึงข้อมูลที่ต้องการ
    $stmt = $conn->prepare("SELECT a.account_id, e.employee_id, e.employee_name, e.employee_position, a.role, a.is_active, a.password, e.status FROM account a JOIN employee e ON a.account_id = e.account_id WHERE a.username = ?");

    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // ตรวจสอบว่ามีผู้ใช้ที่ตรงกับ username หรือไม่
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // ตรวจสอบค่า is_active และ role
            if ($row['is_active'] != 1 && $row['role'] != 2) { // ถ้า is_active ไม่เท่ากับ 1 และ role ไม่เท่ากับ 2
                header('Location: login.html?error=account_inactive');
                exit();
            }

            // ตรวจสอบ password แบบตรงๆ (plaintext)
            if ($row['password'] === $password) { // เปรียบเทียบรหัสผ่านโดยตรง
                $_SESSION['employee_id'] = $row['employee_id'];
                $_SESSION['employee_name'] = $row['employee_name'];
                $_SESSION['employee_position'] = $row['employee_position'];
                $_SESSION['role'] = $row['role'];  // เก็บ role ไว้ใน session
                $_SESSION['status'] = $row['status'];

                // ตรวจสอบ role และเปลี่ยนเส้นทางตามนั้น
                if ($row['role'] == 2) { // admin
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: employee.php');
                }
                exit();
            } else {
                header('Location: login.html?error=invalid_credentials');
                exit();
            }
        } else {
            header('Location: login.html?error=invalid_credentials');
            exit();
        }

        $stmt->close();
    } else {
        // ถ้ามีข้อผิดพลาดในการเตรียม statement
        header('Location: login.html?error=server_error');
        exit();
    }
}
$conn->close();
?>
