<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "companyx";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_GET['id'];

$sql = "SELECT * FROM employee WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

echo json_encode($employee);

$stmt->close();
$conn->close();
?>
