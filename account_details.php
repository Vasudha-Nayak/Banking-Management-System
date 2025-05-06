<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "user";
$password = "mysql002";
$database = "Banking_Database";

$conn = new mysqli("localhost", "root", "mysql002", "Banking_Database");


if ($conn->connect_error) {
  echo json_encode(["error" => "Database connection failed"]);
  exit();
}

$accountId = $_GET['account_id'] ?? '';

if (!$accountId) {
  echo json_encode(["error" => "No account ID provided"]);
  exit();
}

$sql = "SELECT c.customer_id, c.customer_name, c.customer_email, 
               a.account_id, a.account_type, a.balance
        FROM Customer c
        JOIN Account a ON c.customer_id = a.customer_id
        WHERE a.account_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $accountId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode($row);
} else {
  echo json_encode(["error" => "Account not found"]);
}

$stmt->close();
$conn->close();
?>
