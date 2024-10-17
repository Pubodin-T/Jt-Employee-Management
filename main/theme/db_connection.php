<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'companyx'; // Ensure this database exists
$user = 'root';
$pass = '';

// Create a new mysqli connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

return $conn; // Return the mysqli object if the connection is successful
?>

