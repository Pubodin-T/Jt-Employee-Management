<?php

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/db_connection.php";

// Check for the reset token in the employee table
$sql = "SELECT * FROM employee WHERE reset_token = ?";

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("MySQL prepare error: " . $mysqli->error);
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

// Hash the new password
$password = $_POST["password"];

// Update the password in the account table using the account_id from the employee table
$sql = "UPDATE account SET password = ? WHERE account_id = ?";

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("MySQL prepare error: " . $mysqli->error);
}

$stmt->bind_param("ss", $password, $user["account_id"]); // Use account_id from the employee record
$stmt->execute();

// Check for successful update
if ($stmt->affected_rows > 0) {
    //echo "Password updated. You can now log in.";
    $message = "รหัสผ่านถูกตั้งใหม่แล้ว คุณสามารถใช้งานได้"; // Message for invalid email
} else {
    //echo "No changes made or password is the same as before.";
    $message = "รหัสไม่ถูกเปลี่ยนหรือรหัสผ่านใหม้ซ้ำกับรหัสเดิม"; // Message for invalid email
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
        <a href="login.html" class="back-button">ตกลง</a>
    </div>
</body>
</html>