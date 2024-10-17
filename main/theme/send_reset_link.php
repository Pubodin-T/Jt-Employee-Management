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
            echo "Message sent, please check your inbox.";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
} else {
    echo "No user found with that email.";
}