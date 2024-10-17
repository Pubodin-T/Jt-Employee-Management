<?php
include("db_connection.php"); // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_month = $_POST['month'];  // รับค่าจากฟอร์มที่ผู้ใช้เลือก
    $selected_year = $_POST['year'];    // รับค่าปี

    // ตรวจสอบการเลือกเดือนและปี
    if (empty($selected_month) || empty($selected_year)) {
        echo "<p class='alert alert-danger'>กรุณาเลือกเดือนและปี</p>";
    } else {
        // Query เพื่อดึงข้อมูลประเภทการลาและจำนวนการลาในแต่ละประเภท
        $query = "
            SELECT 
                lt.leave_type_name,
                COUNT(lr.leave_requests_id) as total_leaves
            FROM 
                leave_requests lr
            JOIN 
                leave_types lt ON lr.leave_type_id = lt.leave_type_id
            WHERE 
                MONTH(lr.start_date) = '$selected_month' 
                AND YEAR(lr.start_date) = '$selected_year'
            GROUP BY 
                lt.leave_type_name";

        $result = $conn->query($query);

        if ($result === false) {
            echo "<p class='alert alert-danger'>เกิดข้อผิดพลาดในการดึงข้อมูล: " . $conn->error . "</p>";
        } elseif ($result->num_rows > 0) {
            // สร้างอาร์เรย์สำหรับเก็บข้อมูลกราฟ
            $leave_types = [];
            $total_leaves = [];

            while ($row = $result->fetch_assoc()) {
                $leave_types[] = $row['leave_type_name']; // ประเภทการลา
                $total_leaves[] = $row['total_leaves'];   // จำนวนการลา
            }
            
            // แปลงข้อมูลให้เป็น JSON เพื่อส่งไปใช้ใน JavaScript
            $leave_types_json = json_encode($leave_types);
            $total_leaves_json = json_encode($total_leaves);
        } else {
            echo "<p class='alert alert-info mt-4'>ไม่มีข้อมูลการลาสำหรับเดือนและปีที่เลือก</p>";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายงานการลา</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;300;400;500;600&display=swap">
  <style>
    
    .container {
      margin-top: 50px;
    }
    .form-group label {
      font-weight: 500;
    }
    .table {
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .table th, .table td {
      text-align: center;
      padding: 15px;
      border-bottom: 1px solid #dee2e6;
    }
    .table th {
      background-color: #dc3545;
      color: white;
    }
    .table-container {
      margin-top: 30px;
    }
    .table-container h3 {
      margin-bottom: 20px;
    }
    #myChart {
      max-width: 600px;
      margin: 50px auto;
    }
    .btn-primary {
      background-color: #dc3545;
      border-color: #fd4b5c;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }
    .alert {
      margin: 20px 0;
      border-radius: 5px;
    }
    body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            color: #ffffff;
            background: url('images/slider-main/j&t555.jpg') no-repeat center center/cover;
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
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #dc3545;
            color: #fff;
        }
        .sidebar a.active {
            background-color: #dc3545;
            color: #fff;
        }
        /* Main Content Styles */
        .content {
            margin-left: 250px; /* Leave space for the sidebar */
            padding: 40px;
            
            width: calc(100% - 250px); /* Adjust the width to account for the sidebar */
            color: #fff; /* สีของข้อความใน Main Content */
        }
        .content h1 {
            color: #fff;
        }
        .content p {
            color: #ddd; /* สีของข้อความอื่นๆ */
        }
        .certificate-table {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            color: #000;
        }
        .certificate-table th, .certificate-table td {
            padding: 10px;
            text-align: left;
        }
        .certificate-table th {
            background-color: #ff0000;
            color: #fff;
        }
        
    .notification {
        margin-top: 20px;
        padding: 15px;
    }
    .notification a {
    display: block; /* ให้ลิงก์เป็นบล็อกเพื่อให้คลิกได้ทั้งกล่อง */
    padding: 15px; /* เพิ่ม padding รอบๆ ข้อความ */
    border-radius: 5px; /* มุมมน */
    transition: background-color 0.3s, box-shadow 0.3s; /* เอฟเฟกต์การเปลี่ยนแปลง */
}

.notification a:hover {
    background-color: #ff0000; /* เปลี่ยนพื้นหลังเมื่อวางเมาส์ */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อวางเมาส์ */
}
    .alert {
    display: inline-block; /* ให้กล่องมีขนาดตามเนื้อหาข้างใน */
    max-width: 400px; /* กำหนดความกว้างสูงสุดของกล่อง */
    padding: 15px; /* เพิ่ม padding รอบๆ ข้อความในกล่อง */
    margin-bottom: 20px;
    border-radius: 10px; /* ปรับรัศมีมุมให้โค้งมน */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2), /* เงาที่มุมด้านล่าง */
                0 6px 6px rgba(0, 0, 0, 0.1); /* เงาที่เบลอ */
    transition: transform 0.2s; /* เอฟเฟกต์ในการขยายขนาด */
    word-wrap: break-word; /* ให้ข้อความแสดงในบรรทัดใหม่เมื่อมีความยาวเกินกล่อง */
    cursor: pointer; /* เปลี่ยนเคอร์เซอร์เมื่อเลื่อนเมาส์ไปบนกล่องแจ้งเตือน */
}

.alert:hover {
    transform: scale(1.05); /* ขยายขนาดกล่องเมื่อชี้เมาส์ไปที่มัน */
    background-color: #ff0000;
}
    
    .alert-warning {
        background-color: #272727;
        color: #856404;
        border-color: #2b2b2b;
    }
    .alert-success {
        background-color: #000000;
        color: #fff;
    }
    .alert i {
    margin-right: 10px; /* เพิ่มระยะห่างระหว่างไอคอนและข้อความ */
    font-size: 20px; /* ขนาดของไอคอน */
    vertical-align: middle; /* จัดให้ไอคอนอยู่กึ่งกลางกับข้อความ */
}
.alert i {
    margin-right: 10px; /* ระยะห่างระหว่างไอคอนและข้อความ */
    font-size: 20px; /* ขนาดของไอคอน */
    vertical-align: middle; /* จัดไอคอนให้กึ่งกลางข้อความ */
    color: #ffc107; /* สีของไอคอนรูประฆัง */
}
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d"></script>
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
  <div class="container">
    <h1 class="text-center">รายงานการลาของพนักงาน</h1>
    <form method="POST" action="">
      <div class="form-group">
        <label for="month">เลือกเดือน:</label>
        <select id="month" name="month" class="form-control">
          <option value="01">มกราคม</option>
          <option value="02">กุมภาพันธ์</option>
          <option value="03">มีนาคม</option>
          <option value="04">เมษายน</option>
          <option value="05">พฤษภาคม</option>
          <option value="06">มิถุนายน</option>
          <option value="07">กรกฎาคม</option>
          <option value="08">สิงหาคม</option>
          <option value="09">กันยายน</option>
          <option value="10">ตุลาคม</option>
          <option value="11">พฤศจิกายน</option>
          <option value="12">ธันวาคม</option>
        </select>
      </div>
      <div class="form-group">
        <label for="year">เลือกปี:</label>
        <select id="year" name="year" class="form-control">
          <option value="2024">2024</option>
          <option value="2023">2023</option>
          <!-- เพิ่มปีอื่น ๆ ตามที่ต้องการ -->
        </select>
      </div>
      <button type="submit" class="btn btn-primary">ดูรายงาน</button>
    </form>

    <!-- แสดงกราฟ -->
    <?php if (isset($leave_types_json) && isset($total_leaves_json)): ?>
      <div id="myChartContainer">
        <canvas id="myChart"></canvas>
      </div>

      <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie', // กำหนดประเภทกราฟเป็นวงกลม
            data: {
                labels: <?php echo $leave_types_json; ?>, // ประเภทการลา
                datasets: [{
                    label: 'จำนวนการลาในแต่ละประเภท',
                    data: <?php echo $total_leaves_json; ?>, // จำนวนการลา
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' ครั้ง';
                            }
                        }
                    },
                    // เพิ่มการตั้งค่า 3D
                    '3d': {
                        enabled: true, // เปิดใช้งาน 3D
                        alpha: 0.8, // มุมมองที่มุมมองกล้อง
                        beta: 0.5, // มุมมองที่แนวนอน
                        depth: 0.8 // ระดับความลึก
                    }
                }
            }
        });
      </script>

      <!-- ตารางแสดงข้อมูลการลา -->
      <div class="table-container">
        <h3 class="text-center">ข้อมูลการลาในเดือน <?php echo $selected_month . '/' . $selected_year; ?></h3>
        <table class="table table-bordered mt-3">
          <thead>
            <tr>
              <th>ประเภทการลา</th>
              <th>จำนวนการลา</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // สร้างตารางแสดงข้อมูล
            $result->data_seek(0); // รีเซ็ตผลลัพธ์
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['leave_type_name'] . "</td>";
                echo "<td>" . $row['total_leaves'] . "</td>";
                echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
