<?php
session_start();
include("db_connection.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 2) {
    echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    exit();
}

// รับข้อมูลจากฟอร์มและทำความสะอาดข้อมูล
$employee_id = trim($_POST['employee_id'] ?? '');
$employee_name = trim($_POST['employee_name'] ?? '');
$employee_position = trim($_POST['employee_position'] ?? '');
$employee_age = intval($_POST['employee_age'] ?? 0);
$employee_dob = trim($_POST['employee_dob'] ?? ''); // วันที่ที่ส่งมาในฟอร์ม
$employee_phone = trim($_POST['employee_phone'] ?? '');
$employee_email = trim($_POST['employee_email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = 1; // กำหนดบทบาทเป็นพนักงาน (1)

// ตรวจสอบว่าค่าต่างๆ ถูกต้องหรือไม่
if (empty($employee_id) || empty($employee_name) || empty($username) || empty($password)) {
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    exit();
}

// ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
$stmt_account = $conn->prepare("INSERT INTO account (username, password, role) VALUES (?, ?, ?)");
if ($stmt_account) {
    $stmt_account->bind_param("ssi", $username, $password, $role); // ไม่เข้ารหัสรหัสผ่าน
    if ($stmt_account->execute()) {
        $account_id = $conn->insert_id; // รับ ID ของบัญชีที่สร้างใหม่

        // เพิ่มข้อมูลในตาราง employee โดยยังไม่ใส่ข้อมูล employee_dob
        $stmt_employee = $conn->prepare("INSERT INTO employee (employee_id, employee_name, employee_position, employee_age, employee_phone, employee_email, role, account_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt_employee) {
            $stmt_employee->bind_param("sssiissi", $employee_id, $employee_name, $employee_position, $employee_age, $employee_phone, $employee_email, $role, $account_id);
            if ($stmt_employee->execute()) {
                echo "เพิ่มพนักงานใหม่เรียบร้อยแล้ว";

                // บันทึกข้อมูลวันเกิดในบรรทัดแยก
                if (!empty($employee_dob)) {
                    // ตรวจสอบรูปแบบวันที่ (YYYY-MM-DD)
                    $date = DateTime::createFromFormat('Y-m-d', $employee_dob);
                    if ($date && $date->format('Y-m-d') === $employee_dob) {
                        $employee_dob_formatted = $date->format('Y-m-d'); // ใช้รูปแบบ DATE

                        // อัปเดตวันเดือนปีเกิดลงในฐานข้อมูล
                        $stmt_update_dob = $conn->prepare("UPDATE employee SET employee_dob = ? WHERE employee_id = ?");
                        if ($stmt_update_dob) {
                            $stmt_update_dob->bind_param("ss", $employee_dob_formatted, $employee_id);
                            if ($stmt_update_dob->execute()) {
                                echo "บันทึกวันเกิดเรียบร้อยแล้ว";
                            } else {
                                echo "Error updating DOB: " . $stmt_update_dob->error;
                            }
                        }
                    } else {
                        echo "รูปแบบวัน/เดือน/ปีเกิดไม่ถูกต้อง: " . htmlspecialchars($employee_dob);
                    }
                }
            } else {
                echo "Error inserting employee: " . $stmt_employee->error; // แสดงข้อผิดพลาด
            }
        } else {
            echo "Error preparing employee statement: " . $conn->error; // แสดงข้อผิดพลาด
        }
    } else {
        echo "Error inserting account: " . $stmt_account->error;
    }
    $stmt_account->close();
} else {
    echo "Error preparing account statement: " . $conn->error;
}

$conn->close();
?>
