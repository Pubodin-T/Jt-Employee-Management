<?php
include("db_connection.php");
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบว่ามี ID ของคำขอลาหรือไม่
if (isset($_GET['id'])) {
    $leave_request_id = $_GET['id'];

    // ดึงข้อมูลใบคำขอลาจากฐานข้อมูล
    $query = "SELECT * FROM leave_requests WHERE leave_requests_id = '$leave_request_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $leave_request = $result->fetch_assoc();
    } else {
        die("ไม่พบข้อมูลคำขอลานี้");
    }
}

// อัปเดตข้อมูลเมื่อกดบันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $leave_type_id = $_POST['leave_type_id'];
    $reason = $_POST['reason'];

    $update_query = "UPDATE leave_requests SET start_date = '$start_date', end_date = '$end_date', leave_type_id = '$leave_type_id', reason = '$reason' WHERE leave_requests_id = '$leave_request_id'";

    if ($conn->query($update_query) === TRUE) {
        echo "อัปเดตข้อมูลสำเร็จ!";
        header("Location: status.php");
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดต: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แก้ไขใบคำขอลา</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h1>แก้ไขใบคำขอลา</h1>
    <form method="post">
      <div class="form-group">
        <label>วันที่เริ่มต้น</label>
        <input type="date" name="start_date" class="form-control" value="<?php echo $leave_request['start_date']; ?>" required>
      </div>
      <div class="form-group">
        <label>วันที่สิ้นสุด</label>
        <input type="date" name="end_date" class="form-control" value="<?php echo $leave_request['end_date']; ?>" required>
      </div>
      <div class="form-group">
        <label>ประเภทการลา</label>
        <select name="leave_type_id" class="form-control" required>
          <!-- ดึงข้อมูลประเภทการลาจากฐานข้อมูล -->
          <?php
          $leave_type_query = "SELECT * FROM leave_types";
          $leave_type_result = $conn->query($leave_type_query);
          while ($leave_type = $leave_type_result->fetch_assoc()) {
              $selected = $leave_type['leave_type_id'] == $leave_request['leave_type_id'] ? 'selected' : '';
              echo "<option value='{$leave_type['leave_type_id']}' $selected>{$leave_type['leave_type_name']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label>เหตุผลการลา</label>
        <textarea name="reason" class="form-control" required><?php echo $leave_request['reason']; ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
    </form>
  </div>
</body>
</html>
