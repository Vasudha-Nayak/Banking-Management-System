<?php
session_start();

// DB config
$servername = "localhost";
$username = "root";
$password = "mysql002";
$database = "Banking_Database";

// Connect
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $input_username = $_POST['username'];
        $input_password = $_POST['password'];

        $sql = "SELECT * FROM admin_auth WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $input_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if ($input_password === $row['password']) {
                $_SESSION['admin_id'] = $row['admin_id'];
                $_SESSION['username'] = $row['username'];
                echo "<script>alert('Login Successful !!'); window.location.href='admin_page.html';</script>";
                exit();
            } else {
                echo "<script>alert('Incorrect Password'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Username Not Found'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please Enter Username and Password.'); window.history.back();</script>";
    }
}
$conn->close();
?>
