<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/db_connection.php";

// Correct the table name from empolyee to employee
$sql = "SELECT * FROM employee
        WHERE reset_token = ?";

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("MySQL prepare error: " . $mysqli->error); // Check for prepare error
}

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found");
}

if (strtotime($user["reset_token_expiry"]) <= time()) {
    die("Token has expired");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400&display=swap" rel="stylesheet">
    <style>
         body {
            font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit */
            background-image: url('images/slider-main/j&t22.jpg'); /* เปลี่ยนเป็นเส้นทางรูปภาพของคุณ */
            background-size: cover; /* ทำให้ภาพพื้นหลังครอบคลุมทั้งพื้นที่ */
            background-position: center; /* จัดให้ภาพอยู่กลาง */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff; 
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
            width: 300px; 
            text-align: center; 
        }
        
        h2 {
            color: #333; 
            margin-bottom: 20px; 
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #555; 
            text-align: left; 
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }
        button, .back-button {
            background-color: #4CAF50; 
            color: white; 
            padding: 10px;
            border: none;
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px; 
            transition: background-color 0.3s; 
            width: 48%; /* ขยายความกว้างปุ่มให้เท่ากัน */
            display: inline-block; /* ทำให้ปุ่มอยู่ในบรรทัดเดียวกัน */
            margin: 5px 1%; /* เพิ่มระยะห่างระหว่างปุ่ม */
            text-align: center; /* จัดข้อความในปุ่มให้กลาง */
            font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit */
            text-decoration:none;
        }
        button:hover, .back-button:hover {
            background-color: #45a049; 
        }

    </style>
</head>
<body>
<div class="container">
    <h2>ตั้งรหัสผ่านใหม่</h2>

    <form method="post" action="process_reset_password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">รหัสผ่านใหม่</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">รหัสผ่านใหม่อีกครั้ง</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>ยืนยัน</button>
    </form>
    </div>
</body>
</html>
