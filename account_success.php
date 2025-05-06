<?php
$customer_id = $_GET['customer_id'];
$username = $_GET['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Created Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
            background-color: #f0f8ff;
        }
        .confirmation-box {
            display: inline-block;
            padding: 30px;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            background-color: #e6ffe6;
        }
        .confirmation-box h2 {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h2>🎉 Account Created Successfully!</h2>
        <p><strong>Customer ID:</strong> <?php echo $customer_id; ?></p>
        <p><strong>Username:</strong> <?php echo $username; ?></p>
        <br>
        <a href="clogin.html">🔐 Go to Login</a>
    </div>
</body>
</html>
