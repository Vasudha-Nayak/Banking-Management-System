<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$account_id = $_POST['account_id'];
$customer_id = $_POST['customer_id'];
$amount = $_POST['amount'];

// Step 1: Check if account exists
$sql = "SELECT balance FROM Account WHERE account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "❌ Invalid account ID ($account_id).";
    exit;
}

$balance = $result->fetch_assoc()['balance'];

// Step 2: Check sufficient funds
if ($balance < $amount) {
    echo "❌ Insufficient funds!";
    exit;
}

// Step 3: Deduct amount
$withdraw = $conn->prepare("UPDATE Account SET balance = balance - ? WHERE account_id = ?");
$withdraw->bind_param("di", $amount, $account_id);
$withdraw->execute();

// Step 4: Log transaction
$log = $conn->prepare("INSERT INTO Transactions (customer_id, account_id, transaction_type, transaction_amount) VALUES (?, ?, 'Withdrawal', ?)");
$log->bind_param("iid", $customer_id, $account_id, $amount);
$log->execute();

echo "✅ Withdrawal successful! ₹$amount withdrawn from Account $account_id.";
?>
