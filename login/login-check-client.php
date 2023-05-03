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

$stmt = $conn->prepare("SELECT * FROM clients WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPasswordFromDatabase = $row['password'];

    if (password_verify($password, $hashedPasswordFromDatabase)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_type'] = 'client';
        $_SESSION['user_id'] = $row['id'];
        header('Location: /project/clients/client-landing.php');
    } else {
        $_SESSION['error_message'] = "Invalid password";
        header('Location: login-client.php');
    }
} else {
    $_SESSION['error_message'] = "No account found with that email";
    header('Location: login-client.php');
}

$stmt->close();
$conn->close();
?>