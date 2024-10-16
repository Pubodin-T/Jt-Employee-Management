<?php
include("db_connection.php");
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

$employee_id = $_SESSION['employee_id'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $reset_leave = $_POST['reset_leave'];
    if($reset_leave == 1){
        $search = "SELECT * FROM employee";
        $result = $conn->query($search);
        while ($row = $result->fetch_assoc()) {
            $set_value = $row['vacation_leave_remaining'];
            $set_id = $row['employee_id'];
            $sql = "UPDATE employee set 
                    vacation_days_accumulated = $set_value,
                    sick_leave_remaining = 30,
                    personal_leave_remaining = 3,
                    vacation_leave_remaining = 6+$set_value,
                    special_leave_remaining = 5
                    WHERE employee_id = $set_id";
                    $conn->query($sql);
                }
        // foreach($result as $x){
        //     $set_value = $x['vacation_leave_remaining'];
        //     $set_id = $x['employee_id'];
        //     $sql = "UPDATE employee set vacation_days_accumulated = $set_value WHERE employee_id = $set_id";
        //     $conn->query($sql);
            
            
        // }
        // for($i = 0; $i < count($result); $i++){
        //     $set_value = $result['vacation_leave_remaining'];
        //     $set_id = $result['employee_id'];
        //     $sql = "UPDATE employee set vacation_days_accumulated = $set_value WHERE employee_id = $set_id";
        //     $conn->query($sql);
        // }
        
    }
    if($reset_leave == 2){
        $search = "SELECT * FROM employee";
        $result = $conn->query($search);
        while ($row = $result->fetch_assoc()) {
            $set_value = $row['vacation_leave_remaining'];
            $set_id = $row['employee_id'];
            $sql = "UPDATE employee set 
                    vacation_days_accumulated = 0,
                    sick_leave_remaining = 30,
                    personal_leave_remaining = 3,
                    vacation_leave_remaining = 6,
                    special_leave_remaining = 5
                    WHERE employee_id = $set_id";
                    $conn->query($sql);
                }
    }
}
// ปิดการเชื่อมต่อฐานข้อมูล (ย้ายไปที่ด้านล่างสุดของไฟล์)
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ประวัติการลา</title>
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
        body {
            background-image: url('images/slider-main/j&t555.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit ที่คุณเพิ่ม */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* เต็มความสูงของหน้าจอ */
            margin: 0;
            background-color: #f0f0f0; /* สีพื้นหลัง */
            color: #ffffff;
        }
        .container {
            text-align: center; /* จัดข้อความให้กึ่งกลาง */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        select, button {
            margin-top: 10px; /* ช่องว่างระหว่าง select กับปุ่ม */
            padding: 10px;
            font-size: 16px; /* ขนาดฟอนต์ */
        }
    
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #1e1e1e; /* สีพื้นหลังของ Sidebar */
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed; /* Fix it to the left side */
            color: #f8f9fa; /* สีของข้อความใน Sidebar */
        }
        .sidebar h2 {
            color: #f8f9fa; /* สีของหัวข้อ Sidebar */
        }
        .sidebar a {
            display: block;
            padding: 15px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #f8f9fa; /* สีของลิงก์ */
            background-color: #495057; /* สีพื้นหลังของลิงก์ */
            border-radius: 5px;
           
        }
        .sidebar a:hover {
            background-color: #dc3545;
            color: #fff;
        }
        .sidebar a.active {
            background-color: #dc3545;
            color: #fff;
        }
</style>
  </style>
  
</head>

<body>
    
<form method="POST" enctype="multipart/form-data">
<div class="container">
    <h1>รีเซ็ตวันลาพักร้อนพนักงาน</h1>    
    <select name="reset_leave">
        <option value="">-- กรุณาเลือก --</option>
        <option value="1">เก็บสะสมวันลาประจำปี</option>
        <option value="2">ไม่เก็บสะสมวันลาประจำปี</option>
    </select>
    <br>
    <button type="submit">ส่ง</button>    
</div>
</form>
  <!--/ Container end -->

  <!-- Javascript Files -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
