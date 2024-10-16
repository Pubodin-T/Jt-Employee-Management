<?php
session_start();
include("db_connection.php");

// ตรวจสอบว่ามีการล็อกอิน
if (isset($_SESSION['employee_id'])) {
    $employee_id = $_SESSION['employee_id'];

    // สร้าง query
    $stmt = $conn->prepare("SELECT employee_name, employee_id, employee_position FROM employee WHERE employee_id = ?");
    $stmt->bind_param('s', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
} else {
    echo json_encode([]);
}

$conn->close();
?>
