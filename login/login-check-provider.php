<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM providers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPasswordFromDatabase = $row['password'];

    if (password_verify($password, $hashedPasswordFromDatabase)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_type'] = 'provider';
        header('Location: /project/landing/provider-landing.php');
    } else {
        $_SESSION['error_message'] = "Invalid password";
        header('Location: login-provider.php');
    }
} else {
    $_SESSION['error_message'] = "No account found with that email";
    header('Location: login-provider.php');
}

$stmt->close();
$conn->close();
?>