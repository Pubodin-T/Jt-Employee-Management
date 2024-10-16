<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ข้อมูลพนักงาน</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>ข้อมูลพนักงาน</h1>

    <?php
    session_start();
    if (!isset($_SESSION["employee_id"])) {
      header("Location: login.php");
      exit();
    }

    $employee_id = $_SESSION["employee_id"];
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "company";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // ใช้ prepared statements เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT * FROM employee WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $photo_path = $row["photo_path"] ? 'uploads' . $row["photo_path"] : 'uploads/icon.jpg'; // รูปภาพเริ่มต้นหากไม่มีรูปภาพ
        echo "<div class='employee-info'>";
        echo "<img src='$photo_path' alt='รูปพนักงาน' style='width:100px;height:100px;'>";
        echo "<p><strong>รหัสพนักงาน:</strong> " . htmlspecialchars($row["employee_id"]) . "</p>";
        echo "<p><strong>ชื่อ-นามสกุล:</strong> " . htmlspecialchars($row["employee_name"]) . "</p>";
        echo "<p><strong>ตำแหน่ง:</strong> " . htmlspecialchars($row["employee_position"]) . "</p>";
        echo "<p><strong>อายุ:</strong> " . ($row["employee_age"] ? htmlspecialchars($row["employee_age"]) : 'ไม่ระบุ') . "</p>";
        echo "<p><strong>วัน/เดือน/ปีเกิด:</strong> " . ($row["employee_dob"] ? htmlspecialchars($row["employee_dob"]) : 'ไม่ระบุ') . "</p>";
        echo "<p><strong>เบอร์โทร:</strong> " . ($row["employee_phone"] ? htmlspecialchars($row["employee_phone"]) : 'ไม่ระบุ') . "</p>";
        echo "<p><strong>Email:</strong> " . ($row["employee_email"] ? htmlspecialchars($row["employee_email"]) : 'ไม่ระบุ') . "</p>";
        echo "</div><hr>";
      }
    } else {
      echo "ไม่มีข้อมูลพนักงาน.";
    }

    $stmt->close();
    $conn->close();
    ?>

    <!-- ลิงก์สำหรับอัปโหลดรูปภาพ -->
    <div class="upload-photo">
      <p><a href="upload_photo.html">อัปโหลดรูปภาพ</a></p>
    </div>
  </div>
</body>
</html>
