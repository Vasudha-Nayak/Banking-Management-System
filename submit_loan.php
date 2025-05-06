<?php
$host = 'localhost';
$db = 'Banking_Database';
$user = 'root';
$pass = 'mysql002';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$account_id = $_POST['account_id'];
$loan_type = $_POST['loan_type'];
$requested_amount = $_POST['requested_amount'];
$credit_score = $_POST['credit_score'];
$income = $_POST['income'];

// Step 1: Get customer_id from account_id
$query = "SELECT customer_id FROM Account WHERE account_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $account_id);
$stmt->execute();
$stmt->bind_result($customer_id);
$stmt->fetch();
$stmt->close();

if (!$customer_id) {
  $conn->close();
  die("No customer found for the given account ID.");
}

// Step 2: Insert into LoanApplication with customer_id
$sql = "INSERT INTO LoanApplication (loan_type, requested_amount, credit_score, income, approval_status, customer_id) 
        VALUES (?, ?, ?, ?, 'Pending', ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdddi", $loan_type, $requested_amount, $credit_score, $income, $customer_id);

if (!$stmt->execute()) {
  $stmt->close();
  $conn->close();
  die("Error inserting into LoanApplication: " . $stmt->error);
}

// Step 3: Get the application_id
$application_id = $stmt->insert_id;
$stmt->close();

// Step 4: Insert into AppliesFor with customer_id and application_status = 'Pending'
$sql2 = "INSERT INTO AppliesFor (customer_id, application_id, application_status) VALUES (?, ?, 'Pending')";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("ii", $customer_id, $application_id);

if (!$stmt2->execute()) {
  $stmt2->close();
  $conn->close();
  die("Error inserting into AppliesFor: " . $stmt2->error);
}

$stmt2->close();
$conn->close();

// ✅ Proper redirect to confirmation page
header("Location: loan_app_confirm.html");
exit();
?>
