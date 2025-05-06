<?php
$conn = new mysqli("localhost", "root", "mysql002", "banking_database");
if ($conn->connect_error) {
    die("DB connection failed");
}
$sql = "SELECT * FROM LoanApplication WHERE approval_status != 'Approved'";
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
$conn->close();
?>
