<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'companyx'; // Ensure this database exists
$user = 'root';
$pass = '';

// Create a new mysqli connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

return $mysqli; // Return the mysqli object if the connection is successful
?>

