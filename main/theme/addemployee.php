<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มพนักงานใหม่</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('images/slider-main/j&t22.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .sidebar {
            width: 250px;
            background-color: #121212;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 {
            color: #fff;
            margin-top: 0;
        }
        .sidebar a {
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #495057;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #007bff;
            color: #fff;
        }
        .sidebar a.active {
            background-color: #ff0000;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            background-image: url('path/to/your/content-background.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border: 2px solid #000000;
        }
        h1 {
            text-align: center;
            font-weight: 300;
            font-family: 'Kanit', sans-serif;
           
           color: #000000;
           margin: 20px 0;

        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="number"], input[type="date"], input[type="password"], select {
            width: 90%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #dc3741;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #212121;
        }
    </style>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Sidebar -->
    <div class="sidebar">
    <h2>ผู้ดูแลระบบ</h2>
    <a href="admin_dashboard.php"><i class="fas fa-home"></i> หน้าหลัก</a>
    <a href="employee_list.php"><i class="fas fa-users"></i> ข้อมูลพนักงาน</a>
    <a href="approve_leave.php"><i class="fas fa-check-circle"></i> จัดการการลาพนักงาน</a>
    <a href="addemployee.php"><i class="fas fa-user-plus"></i> ลงทะเบียนพนักงานใหม่</a>
    <a href="leave_history.php"><i class="fas fa-history"></i> ประวัติการลาพนักงาน</a>
    <a href="certificate.php"><i class="fas fa-file-medical"></i> ประวัติใบรับรองแพทย์</a>
    <a href="reset_leave.php"><i class="fas fa-refresh"></i> รีเซ็ตวันลาพักร้อน</a>
    <a href="leave_report.php"><i class="fas fa-chart-bar"></i> ยอดวันใช้ไป-คงเหลือพนักงาน</a>
    <a href="leave_report_employee.php"><i class="fas fa-chart-pie"></i> สถิติการลา</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
</div>

    <div class="content">
        <div class="container">
            <h1>ลงทะเบียนพนักงานใหม่</h1>
            <form id="addEmployeeForm" action="add_employee_process.php" method="post">
                <label for="employee_id">รหัสพนักงาน:</label>
                <input type="text" id="employee_id" name="employee_id" required>

                <label for="employee_name">ชื่อพนักงาน:</label>
                <input type="text" id="employee_name" name="employee_name" required>

                <label for="employee_position">ตำแหน่ง:</label>
                <select id="employee_position" name="employee_position" required>
                    <option value="วิ่งงาน">วิ่งงาน</option>
                    <option value="ผู้จัดการ">ผู้จัดการ</option>
                    <option value="หัวหน้าบ้านเกาะ">หัวหน้าบ้านเกาะ</option>
                    <option value="รองหัวหน้าบ้านเกาะ">รองหัวหน้าบ้านเกาะ</option>
                    <option value="พาร์ทไทม์">พาร์ทไทม์</option>
                </select>

                <label for="employee_age">อายุ:</label>
                <input type="number" id="employee_age" name="employee_age" required>

                <label for="employee_dob">วัน/เดือน/ปีเกิด:</label>
                <input type="date" name="employee_dob" required>


                <label for="employee_phone">เบอร์โทร:</label>
                <input type="text" id="employee_phone" name="employee_phone" required>

                <label for="employee_email">Email:</label>
                <input type="text" id="employee_email" name="employee_email" required>

                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="ยืนยัน">
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('addEmployeeForm').onsubmit = function(event) {
            event.preventDefault(); // Prevent the form from submitting the traditional way
            
            // Show success message
            Swal.fire({
                title: 'เพิ่มพนักงานใหม่เรียบร้อยแล้ว!',
                text: 'ข้อมูลพนักงาน: \n' + 
                      'รหัสพนักงาน: ' + document.getElementById('employee_id').value + '\n' +
                      'ชื่อพนักงาน: ' + document.getElementById('employee_name').value + '\n' +
                      'ตำแหน่ง: ' + document.getElementById('employee_position').value,
                icon: 'success',
                confirmButtonText: 'ตกลง',
                background: '#f8f9fa',
                color: '#333',
                iconColor: '#28a745',
                confirmButtonColor: '#007bff',
                customClass: {
                    title: 'swal-title',
                    content: 'swal-content',
                    confirmButton: 'swal-confirm-button'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit the form after the alert is closed
                }
            });
        }
    </script>
</body>
</html>
