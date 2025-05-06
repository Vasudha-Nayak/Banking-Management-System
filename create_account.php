<?php
$servername = "localhost";
$username = "root";
$password = "mysql002";
$dbname = "banking_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from form
$custName = $_POST['name'];
$custEmail = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$accountType = $_POST['account_type'];
$balance = (float)$_POST['initial_deposit'];
$user = $_POST['username'];
$pass = $_POST['password'];

// Insert into Customer table
$sqlCustomer = "INSERT INTO Customer (customer_name, customer_email, phone, address)
                VALUES ('$custName', '$custEmail', '$phone', '$address')";

if ($conn->query($sqlCustomer) === TRUE) {
    $customer_id = $conn->insert_id;

    // Insert into Account table
    $sqlAccount = "INSERT INTO Account (customer_id, account_type, balance)
                   VALUES ('$customer_id', '$accountType', '$balance')";
    $conn->query($sqlAccount);

    // Insert into customer_auth table
    $sqlAuth = "INSERT INTO customer_auth (customer_id, username, password)
                VALUES ('$customer_id', '$user', '$pass')";
    $conn->query($sqlAuth);

    // Redirect to confirmation page with details
    header("Location: account_success.php?customer_id=$customer_id&username=$user");
    exit();
} else {
    echo "Error: " . $sqlCustomer . "<br>" . $conn->error;
}

$conn->close();
?>
