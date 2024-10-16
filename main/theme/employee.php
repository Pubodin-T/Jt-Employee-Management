<?php
// เรียกใช้ session_start() ก่อนเริ่มส่งข้อมูลไปยังเบราว์เซอร์
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "companyx";

// ฟังก์ชันคำนวณอายุจากวันเกิด
function calculateAge($dob) {
    if (!$dob || $dob == '0000-00-00') return 'ไม่ระบุ';
    $birthDate = new DateTime($dob);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

// กำหนดตัวแปรสำหรับข้อความแจ้งเตือน
$uploadMsg = "";

// การอัปโหลดรูปภาพ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $target_dir = "uploads/";
    // ตรวจสอบว่าโฟลเดอร์ uploads มีอยู่หรือไม่ ถ้าไม่มีก็สร้างใหม่
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $file_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . uniqid('IMG_', true) . '.' . strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ตรวจสอบว่าเป็นภาพหรือไม่
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadMsg = "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ.";
        $uploadOk = 0;
    }

    // ตรวจสอบขนาดไฟล์ (ไม่เกิน 2MB)
    if ($_FILES["photo"]["size"] > 2000000) {
        $uploadMsg = "ขนาดไฟล์เกินขนาดที่อนุญาต (2MB).";
        $uploadOk = 0;
    }

    // ตรวจสอบชนิดไฟล์
    $allowed = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed)) {
        $uploadMsg = "ขออภัย, เฉพาะไฟล์ JPG, JPEG, PNG, & GIF เท่านั้นที่อนุญาต.";
        $uploadOk = 0;
    }

    // ตรวจสอบว่ามีข้อผิดพลาดหรือไม่
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // อัปเดตข้อมูลพนักงานในฐานข้อมูล
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // ใช้ prepared statement เพื่อป้องกัน SQL Injection
            $stmt = $conn->prepare("UPDATE employee SET photo_path = ? WHERE employee_id = ?");
            $stmt->bind_param("ss", $target_file, $employee_id);

            if ($stmt->execute()) {
                $uploadMsg = "อัปโหลดรูปภาพสำเร็จแล้ว.";
            } else {
                $uploadMsg = "เกิดข้อผิดพลาดในการอัปเดตฐานข้อมูล.";
            }

            $stmt->close();
            $conn->close();
        } else {
            $uploadMsg = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
        }
    }
}

// เชื่อมต่อฐานข้อมูลเพื่อดึงข้อมูลพนักงาน
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
<!DOCTYPE html>
<html lang="th">
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>ข้อมูลพนักงาน</title>
    <!-- Mobile Specific Metas -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="ข้อมูลพนักงาน">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/logoj&t.png">
    <!-- CSS -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
    <!-- Animation -->
    <link rel="stylesheet" href="plugins/animate-css/animate.css">
    <!-- slick Carousel -->
    <link rel="stylesheet" href="plugins/slick/slick.css">
    <link rel="stylesheet" href="plugins/slick/slick-theme.css">
    <!-- Colorbox -->
    <link rel="stylesheet" href="plugins/colorbox/colorbox.css">
    <!-- Template styles-->
    <link rel="stylesheet" href="css/style.css">
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
            font-family: 'Kanit', sans-serif; /* กำหนดฟอนต์เป็น Kanit */
        }

        .employee-info {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px; /* เพิ่ม padding */
            border-radius: 10px;
            margin-bottom: 20px; /* ระยะห่างจากด้านล่าง */
            width: 100%; /* กว้างเต็มพื้นที่ */
            max-width: 600px; /* ขนาดสูงสุด */
            text-align: left; /* ข้อความจัดอยู่ทางซ้าย */
            margin: auto; /* จัดกึ่งกลาง */
            box-sizing: border-box; /* รวม padding และ border ในขนาดรวม */
        }

        .employee-info img {
            width: 200px; /* กำหนดความกว้างของรูปภาพ */
            height: 200px; /* กำหนดความสูงของรูปภาพ */
            object-fit: cover; /* ให้รูปภาพยืดหยุ่นได้เพื่อให้มีการครอบตัดและไม่ผิดสัดส่วน */
            border-radius: 10px; /* ทำให้มุมของรูปภาพโค้งมน */
            display: block; /* แสดงรูปภาพเป็นบล็อก */
            margin: 0 auto 20px; /* จัดกึ่งกลางรูปภาพและเพิ่มระยะห่างด้านล่าง */
        }

        .upload-photo {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-sizing: border-box;
        }

        .upload-photo h2 {
            margin-top: 0;
            text-align: center;
        }

        .upload-photo form {
            display: flex;
            flex-direction: column;
        }

        .upload-photo label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .upload-photo input[type="file"] {
            margin-bottom: 10px;
        }

        .upload-photo input[type="submit"] {
            align-self: center;
            padding: 10px 20px;
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-photo input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
        }

        .error {
            color: red;
        }
        
    </style>
</head>
<body>
    <div class="body-inner">
        <!-- Top Bar -->
        <div id="top-bar" class="top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-8">
                        <ul class="top-info text-center text-md-left">
                            <li><i class="fas fa-map-marker-alt"></i> <p class="info-text">Phon Krang, Nakhon Ratchasima 30000</p></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-4 top-social text-center text-md-right">
                        <ul class="list-unstyled">
                            <li>
                                <a title="Facebook" href="https://facebook.com/themefisher.com">
                                    <span class="social-icon"><i class="fab fa-facebook-f"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header -->
        <div class="bg-white">
            <div class="container">
                <div class="logo-area">
                    <div class="row align-items-center">
                        <div class="logo col-lg-3 text-center text-lg-left mb-3 mb-md-5 mb-lg-0">
                            <a class="d-block" href="index.html">
                                <img loading="lazy" src="images/logoj&t.png" alt="Constra">
                            </a>
                        </div>
                        <div class="col-lg-9 header-right">
                            <ul class="top-info-box">
                                <li>
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <p class="info-box-title">เบอร์โทร</p>
                                            <p class="info-box-subtitle">(+66) 02-009-5678</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <p class="info-box-title">Email</p>
                                            <p class="info-box-subtitle">dpo@jtexpress.co.th.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation -->
        <div class="site-navigation">
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
            </div>
        </div>
        <!-- Main Content -->
        <section id="main-container" class="main-container">
            <div class="container">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-xl-3 col-lg-4">
                        <div class="sidebar sidebar-left">
                            <div class="widget">
                                <h3 class="widget-title">Menu</h3>
                                <ul class="nav service-menu">
                                    <li><a href="history.php">ประวัติการลา</a></li>
                                    <li class="active"><a href="employee.php">ข้อมูลพนักงาน</a></li>
                                    <li><a href="form.php">แบบฟอร์มการลา</a></li>
                                    <li><a href="status.php">เช็คสถานะการลา</a></li>
                                </ul>
                            </div>
                            <div class="widget">
                                <div class="quote-item quote-border">
                                    <div class="quote-text-border">
                                        <!-- คุณสามารถเพิ่มข้อความหรือรูปภาพที่นี่ได้ -->
                                    </div>
                                    <div class="quote-item-footer">
                                        <div class="quote-item-info">
                                            <h3 class="quote-author"></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Main Content Area -->
                    <div class="col-xl-8 col-lg-8">
                        <div class="content-inner-page">
                            <div class="container">
                                <h1>ข้อมูลพนักงาน</h1>

                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // ตรวจสอบและกำหนดเส้นทางของรูปภาพ
                                        $photo_path = $row["photo_path"] && file_exists($row["photo_path"]) ? $row["photo_path"] : 'uploads/icon.jpg';
                                        
                                        echo "<div class='employee-info'>";
                                        echo "<img src='" . htmlspecialchars($photo_path) . "' alt='รูปพนักงาน'>";
                                        echo "<p><strong>รหัสพนักงาน:</strong> " . htmlspecialchars($row["employee_id"]) . "</p>";
                                        echo "<p><strong>ชื่อ-นามสกุล:</strong> " . htmlspecialchars($row["employee_name"]) . "</p>";
                                        echo "<p><strong>ตำแหน่ง:</strong> " . htmlspecialchars($row["employee_position"]) . "</p>";
                                        echo "<p><strong>วัน/เดือน/ปีเกิด:</strong> " . ($row["employee_dob"] && $row["employee_dob"] != '0000-00-00' ? htmlspecialchars($row["employee_dob"]) : 'ไม่ระบุ') . "</p>";
                                        echo "<p><strong>อายุ:</strong> " . calculateAge($row["employee_dob"]) . " ปี</p>";
                                        echo "<p><strong>เบอร์โทร:</strong> " . htmlspecialchars($row["employee_phone"]) . "</p>";
                                        echo "<p><strong>Email:</strong> " . htmlspecialchars($row["employee_email"]) . "</p>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p>ไม่พบข้อมูลพนักงาน</p>";
                                }

                                $stmt->close();
                                $conn->close();
                                ?>

                                <!-- ฟอร์มอัปโหลดรูปภาพ -->
                                <div class="upload-photo">
                                    <h2>อัปโหลดรูปภาพ</h2>
                                    <?php
                                    if (!empty($uploadMsg)) {
                                        echo "<div class='message'>" . htmlspecialchars($uploadMsg) . "</div>";
                                    }
                                    ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                        <label for="photo">เลือกรูปภาพ:</label>
                                        <input type="file" name="photo" id="photo" accept="image/*" required>
                                        <input type="submit" value="อัปโหลด" name="submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer -->
        <footer id="footer" class="footer bg-overlay">
            <!-- ... (footer content) ... -->
        </footer><!-- Footer end -->

        <!-- Javascript Files -->
        <!-- initialize jQuery Library -->
        <script src="plugins/jQuery/jquery.min.js"></script>
        <!-- Bootstrap jQuery -->
        <script src="plugins/bootstrap/bootstrap.min.js" defer></script>
        <!-- Slick Carousel -->
        <script src="plugins/slick/slick.min.js"></script>
        <script src="plugins/slick/slick-animation.min.js"></script>
        <!-- Color box -->
        <script src="plugins/colorbox/jquery.colorbox.js"></script>
        <!-- shuffle -->
        <script src="plugins/shuffle/shuffle.min.js" defer></script>
        <!-- Google Map API Key-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU" defer></script>
        <!-- Google Map Plugin-->
        <script src="plugins/google-map/map.js" defer></script>
        <!-- Template custom -->
        <script src="js/script.js"></script>
    </div><!-- Body inner end -->
</body>
</html>
