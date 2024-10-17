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
    echo "Password updated. You can now log in.";
} else {
    echo "No changes made or password is the same as before.";
}

?>