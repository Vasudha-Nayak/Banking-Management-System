<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

$account_id = $_POST['account_id'];
$customer_id = $_POST['customer_id'];
$amount = $_POST['amount'];
$mode = $_POST['mode'];

$sql = "INSERT INTO Deposit (customer_id, account_id, deposit_amount, mode_of_deposit) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iids", $customer_id, $account_id, $amount, $mode);

if ($stmt->execute()) {
    $update = $conn->prepare("UPDATE Account SET balance = balance + ? WHERE account_id = ?");
    $update->bind_param("di", $amount, $account_id);
    $update->execute();

    $log = $conn->prepare("INSERT INTO Transactions (customer_id, account_id, transaction_type, transaction_amount) VALUES (?, ?, 'Deposit', ?)");
    $log->bind_param("iid", $customer_id, $account_id, $amount);
    $log->execute();

    echo "Deposit successful !!";
} else {
    echo "Error: " . $conn->error;
}
?>
