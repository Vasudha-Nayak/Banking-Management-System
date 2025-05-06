<?php
$host = 'localhost';
$user = 'root';
$password = 'mysql002';
$dbname = 'Banking_Database';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT c.customer_id, c.customer_name, c.customer_email, a.account_id, a.account_type, a.balance
        FROM Customer c
        JOIN Account a ON c.customer_id = a.customer_id";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
