<?php
$host = "localhost";
$user = "root";
$password = "mysql002"; // Add password if needed
$database = "Banking_Database";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
