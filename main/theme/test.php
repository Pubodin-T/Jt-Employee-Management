<?php
include("db_connection.php"); // Ensure this path is correct
session_start();

// Check if user is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Check if the connection is established
if (!isset($conn)) {
    die("Database connection failed.");
}

// Get employee's leave balance and accumulated leave days
$current_year = date('Y');
$employee_query = "SELECT sick_leave_remaining, personal_leave_remaining, vacation_leave_remaining, special_leave_remaining, vacation_days_accumulated 
                   FROM employee 
                   WHERE employee_id = '$employee_id'";
$employee_result = $conn->query($employee_query);

if ($employee_result === false || $employee_result->num_rows == 0) {
    die("Error fetching employee data");
}

$employee_row = $employee_result->fetch_assoc();
$sick_leave_remaining = $employee_row['sick_leave_remaining'] ?? 0;
$personal_leave_remaining = $employee_row['personal_leave_remaining'] ?? 0;
$vacation_leave_remaining = $employee_row['vacation_leave_remaining'] ?? 0;
$special_leave_remaining = $employee_row['special_leave_remaining'] ?? 0;
$vacation_days_accumulated = $employee_row['vacation_days_accumulated'] ?? 0;

// Get used vacation leave for the current year
$vacation_current_year_query = "
    SELECT SUM(DATEDIFF(end_date, start_date) + 1) as used_vacation_leave
    FROM leave_requests
    WHERE employee_id = '$employee_id' AND leave_type_id = 3 AND YEAR(start_date) = '$current_year'
";
$vacation_current_year_result = $conn->query($vacation_current_year_query);
$used_vacation_leave = 0;

if ($vacation_current_year_result && $vacation_current_year_result->num_rows > 0) {
    $vacation_current_year_row = $vacation_current_year_result->fetch_assoc();
    $used_vacation_leave = $vacation_current_year_row['used_vacation_leave'] ?? 0;
}

// Calculate remaining vacation leave for the current year
$vacation_leave_remaining -= $used_vacation_leave;

// Get accumulated vacation leave from previous years
$vacation_history_query = "
    SELECT YEAR(start_date) as leave_year, SUM(DATEDIFF(end_date, start_date) + 1) as total_vacation_days
    FROM leave_requests
    WHERE employee_id = '$employee_id' AND leave_type_id = 3 AND YEAR(start_date) < '$current_year'
    GROUP BY leave_year
";
$vacation_history_result = $conn->query($vacation_history_query);

// Store annual accumulated leave data
$vacation_history = [];
if ($vacation_history_result && $vacation_history_result->num_rows > 0) {
    while ($row = $vacation_history_result->fetch_assoc()) {
        $vacation_history[$row['leave_year']] = $row['total_vacation_days'];
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการลา</title>
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('images/slider-main/j&t555.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Kanit', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .card-header {
            background-color: #870000;
            color: white;
            font-size: 1.25rem;
            border-bottom: 1px solid #ff0707;
        }
        .table thead th {
            background-color: #870000;
            color: white;
        }
        .table tbody tr td {
            background-color: white;
            color: black;
        }
        .table, .table th, .table td {
            border: 1px solid #ddd;
        }
        h3 {
            color: white;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-dark p-0">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="navbar-collapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav mr-auto">
                            <li class="nav-item dropdown active">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">หน้าหลัก <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="active"><a href="index.html">หน้าหลัก</a></li>
                                    <li><a href="logout.php">ออกจากระบบ</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ข้อมูลพนักงาน <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="employee.php">ข้อมูลพนักงาน</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">แบบฟอร์มการลา <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="form.php">แบบฟอร์มการลา</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">ประวัติการลา <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="history.php">ประวัติการลา</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">เช็คสถานะการลา <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="status.php">เช็คสถานะการลา</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Leave balance table -->
        <h3>ยอดคงเหลือการลา</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ประเภทการลา</th>
                    <th>ยอดคงเหลือ</th>
                    <th>ยอดสะสม (รายปี)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ลาป่วย</td>
                    <td><?php echo htmlspecialchars($sick_leave_remaining); ?> วัน</td>
                    <td></td>
                </tr>
                <tr>
                    <td>ลากิจ</td>
                    <td><?php echo htmlspecialchars($personal_leave_remaining); ?> วัน</td>
                    <td></td>
                </tr>
                <tr>
                    <td>ลาพักร้อน</td>
                    <td><?php echo htmlspecialchars($vacation_leave_remaining); ?> วัน</td>
                    <td>
                        <?php
                        // Display accumulated vacation days
                        if (!empty($vacation_history) || $vacation_days_accumulated > 0) {
                            foreach ($vacation_history as $year => $days) {
                                echo "ปี $year: $days วัน<br>";
                            }
                            echo "วันลาที่สะสม: " . htmlspecialchars($vacation_days_accumulated) . " วัน";
                        } else {
                            echo "ไม่มีข้อมูลการสะสม";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>ลาพิเศษ</td>
                    <td><?php echo htmlspecialchars($special_leave_remaining); ?> วัน</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Leave request history form -->
        <div class="card mt-4">
            <div class="card-header">
                <strong>ประวัติการลา</strong>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="year">เลือกปี:</label>
                        <select class="form-control" name="year" id="year" required>
                            <option value="">เลือกปี</option>
                            <?php
                            for ($i = 2023; $i <= date('Y'); $i++) {
                                echo "<option value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">แสดงประวัติการลา</button>
                </form>

                <?php
                // Check if year is set in GET request
                if (isset($_GET['year'])) {
                    $selected_year = intval($_GET['year']);
                    $leave_history_query = "
                        SELECT * 
                        FROM leave_requests
                        WHERE employee_id = '$employee_id' AND YEAR(start_date) = $selected_year
                        ORDER BY start_date DESC
                    ";
                    $leave_history_result = $conn->query($leave_history_query);

                    if ($leave_history_result && $leave_history_result->num_rows > 0) {
                        echo "<table class='table table-bordered mt-3'>
                                <thead>
                                    <tr>
                                        <th>หมายเลขคำร้องลา</th>
                                        <th>ประเภทการลา</th>
                                        <th>วันที่เริ่มลา</th>
                                        <th>วันที่สิ้นสุดลา</th>
                                        <th>เหตุผล</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($leave_row = $leave_history_result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$leave_row['leave_requests_id']}</td>
                                    <td>{$leave_row['leave_type_id']}</td>
                                    <td>" . htmlspecialchars($leave_row['start_date']) . "</td>
                                    <td>" . htmlspecialchars($leave_row['end_date']) . "</td>
                                    <td>" . htmlspecialchars($leave_row['reason']) . "</td>
                                    <td>" . htmlspecialchars($leave_row['status']) . "</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<div class='alert alert-warning mt-3'>ไม่มีประวัติการลาในปีนี้</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
