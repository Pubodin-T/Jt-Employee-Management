<?php
session_start();
include("db_connection.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 2) {
    echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้';
    exit();
}

// ตรวจสอบว่ามีการส่ง employee_id มาหรือไม่
if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // ดึงข้อมูลพนักงานจากฐานข้อมูล
    $sql = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $account_id = $employee['account_id'];
        $sql_account = "SELECT * FROM account WHERE account_id = '$account_id'";
    } else {
        echo 'ไม่พบข้อมูลพนักงาน';
        exit();
    }
} else {
    echo 'คำขอไม่ถูกต้อง';
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลพนักงาน</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-image: url('images/slider-main/j&t555.jpg'); /* เปลี่ยน URL เป็นลิงก์ภาพพื้นหลังที่คุณต้องการ */
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #fff;
            margin: 20px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* ทำให้พื้นหลังของกล่องโปร่งแสง */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="email"]:focus {
            border-color: #ff3d00; /* เปลี่ยนเป็นสีแดง */
            outline: none;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #ff3d00; /* สีแดง */
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c62828; /* สีแดงเข้มเมื่อเลื่อนเมาส์ */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .back-button {
            background-color: transparent;
            color: #ff3d00; /* สีแดง */
            border: 2px solid #ff3d00;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin: 20px auto;
            width: 200px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-button:hover {
            background-color: #ff3d00; /* เปลี่ยนเป็นสีแดงเมื่อเลื่อนเมาส์ */
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>แก้ไขข้อมูลพนักงาน</h1>
        <form method="POST" action="update_employee.php">
            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
            <div class="form-group">
                <label for="employee_name">ชื่อพนักงาน:</label>
                <input type="text" name="employee_name" value="<?php echo htmlspecialchars($employee['employee_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="employee_password">รหัสผ่าน:</label>
                <input type="password" name="employee_password" value="<?php echo htmlspecialchars($account_id['password']); ?>" >
            </div>
            <div class="form-group">
                <label for="employee_position">ตำแหน่ง:</label>
                <input type="text" name="employee_position" value="<?php echo htmlspecialchars($employee['employee_position']); ?>" required>
            </div>
            <div class="form-group">
                <label for="employee_dob">วัน/เดือน/ปีเกิด:</label>
                <input type="date" name="employee_dob" value="<?php echo htmlspecialchars($employee['employee_dob']); ?>" required>
            </div>
            <div class="form-group">
                <label for="employee_phone">เบอร์โทร:</label>
                <input type="text" name="employee_phone" value="<?php echo htmlspecialchars($employee['employee_phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="employee_email">Email:</label>
                <input type="email" name="employee_email" value="<?php echo htmlspecialchars($employee['employee_email']); ?>" required>
            </div>
            <!-- เพิ่มฟิลด์อื่น ๆ ตามต้องการ -->
            <button type="submit">บันทึกการเปลี่ยนแปลง</button>
        </form>
        <a href="employee_list.php" class="back-button">ย้อนกลับ</a> <!-- เปลี่ยน previous_page.php เป็นหน้าก่อนหน้า -->
    </div>
</body>
</html> -->
