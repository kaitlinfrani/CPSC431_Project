<?php
session_start();

// Include your database connection file
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get message and provider_id from POST data
    $message = $_POST['message'];
    $provider_id = $_POST['provider_id'];

    // Get user_id from SESSION
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL statement
    $sql = "INSERT INTO messages (provider_id, user_id, message, status) VALUES (?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $status = 'pending';
    $stmt->bind_param('iiss', $provider_id, $user_id, $message, $status);

    // Execute query
    if ($stmt->execute()) {

        $_SESSION['success_message'] = 'Your message was successfully sent.';

        // Redirect back to the schedule page, or wherever you want the user to go
        header('Location: ../client-landing.php');
    } else {
        echo "Error: " . $stmt->error;
    }
    
}