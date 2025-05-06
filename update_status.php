<?php
$conn = new mysqli("localhost", "root", "mysql002", "banking_database");
if ($conn->connect_error) {
    die("Connection failed");
}

$customer_id = $_POST['customer_id'];
$application_id = $_POST['application_id'];
$status = $_POST['status'];
$interest_rate = isset($_POST['interest_rate']) ? floatval($_POST['interest_rate']) : null;

// Step 1: Update AppliesFor
$update_applies = $conn->prepare("UPDATE AppliesFor SET application_status=? WHERE customer_id=? AND application_id=?");
$update_applies->bind_param("sii", $status, $customer_id, $application_id);
$update_applies->execute();

// Stop here if rejected
if ($status === 'Rejected') {
    // Update LoanApplication status to 'Rejected'
    $update_loan_application = $conn->prepare("UPDATE LoanApplication SET approval_status='Rejected' WHERE application_id=?");
    $update_loan_application->bind_param("i", $application_id);
    $update_loan_application->execute();

    echo "Application Rejected";
    exit;
}

// Step 2: Get LoanApplication data
$res = $conn->query("SELECT loan_type, requested_amount FROM LoanApplication WHERE application_id = $application_id");
$loan = $res->fetch_assoc();

// Step 3: Get next loan_id
$res = $conn->query("SELECT MAX(loan_id) AS max_id FROM Loan");
$row = $res->fetch_assoc();
$new_loan_id = ($row['max_id'] ?? 0) + 1;

// Step 4: Insert into Loan
$insert_loan = $conn->prepare("INSERT INTO Loan (loan_id, customer_id, interest_rate, loan_type, loan_amount) VALUES (?, ?, ?, ?, ?)");
$insert_loan->bind_param("iidss", $new_loan_id, $customer_id, $interest_rate, $loan['loan_type'], $loan['requested_amount']);
$insert_loan->execute();

// Step 5: Insert into ApprovedInto
$insert_approved = $conn->prepare("INSERT INTO ApprovedInto (application_id, loan_id) VALUES (?, ?)");
$insert_approved->bind_param("ii", $application_id, $new_loan_id);
$insert_approved->execute();

// Step 6: Update LoanApplication status to "Approved"
$update_loan_application = $conn->prepare("UPDATE LoanApplication SET approval_status='Approved' WHERE application_id=?");
$update_loan_application->bind_param("i", $application_id);
$update_loan_application->execute();

echo "Application Approved! Loan ID: $new_loan_id at Interest Rate: $interest_rate%";
?>
