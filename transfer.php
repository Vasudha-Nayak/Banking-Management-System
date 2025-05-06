<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$from_account = $_POST['from_account'];
$to_account = $_POST['to_account'];
$customer_id = $_POST['customer_id'];
$amount = $_POST['amount'];

// Step 1: Check if from_account exists
$check = $conn->prepare("SELECT balance FROM Account WHERE account_id = ?");
$check->bind_param("i", $from_account);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo "❌ Invalid sender account ID ($from_account).";
    exit;
}

$balance = $result->fetch_assoc()['balance'];

// Step 2: Check if balance is sufficient
if ($balance < $amount) {
    echo "❌ Insufficient funds!";
    exit;
}

// Step 3: Check if to_account exists
$checkTo = $conn->prepare("SELECT account_id FROM Account WHERE account_id = ?");
$checkTo->bind_param("i", $to_account);
$checkTo->execute();
$toResult = $checkTo->get_result();
if ($toResult->num_rows === 0) {
    echo "❌ Invalid receiver account ID ($to_account).";
    exit;
}

// Step 4: Perform transfer
$deduct = $conn->prepare("UPDATE Account SET balance = balance - ? WHERE account_id = ?");
$deduct->bind_param("di", $amount, $from_account);
$deduct->execute();

$add = $conn->prepare("UPDATE Account SET balance = balance + ? WHERE account_id = ?");
$add->bind_param("di", $amount, $to_account);
$add->execute();

// Step 5: Log transaction
$log = $conn->prepare("INSERT INTO Transactions (customer_id, account_id, transaction_type, transaction_amount) VALUES (?, ?, 'Transfer', ?)");
$log->bind_param("iid", $customer_id, $from_account, $amount);
$log->execute();

echo "✅ Transfer successful! ₹$amount transferred from Account $from_account to $to_account.";
?>
