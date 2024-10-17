<?php
$email = $_POST["email"];
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Attempt to include the database connection
$mysqli = require __DIR__ . "/db_connection.php";

// Check if $mysqli is a valid MySQLi object
if (!$mysqli instanceof mysqli) {
    die("Database connection error: Expected mysqli instance, got " . gettype($mysqli));
}

// Prepare the SQL statement
$sql = "UPDATE employee
        SET reset_token = ?,
            reset_token_expiry = ?
        WHERE employee_email = ?";

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("MySQL prepare error: " . $mysqli->error);
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);
if (!$stmt->execute()) {
    die("MySQL execute error: " . $stmt->error);
}

// Check if a row was affected (meaning the update was successful)
if ($mysqli->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    Click <a href="http://localhost/Jt-Employee-Management/main/theme/reset_password.php?token=$token">here</a> 
    to reset your password.
    END;

    try {
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $message = "ส่งข้อความแล้ว กรุณาเช็คอีเมลของคุณ."; // Message for valid email
            $message_type = 'success'; // Used for styling purposes, if needed
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
} else {
    $message = "ไม่พบบัญชีของคุณ กรุณาลองอีกครั้ง"; // Message for invalid email
    $message_type = 'error'; // Used for styling purposes, if needed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        input[type="email"] {
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
        <h2><?= $message ?></h2>
        <a href="forgot_password.php" class="back-button">ตกลง</a>
    </div>
</body>
</html>