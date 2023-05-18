<?php

// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

if (isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE appointments_messages SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $status, $appointment_id);

    if ($stmt->execute()) {
        $message = "Appointment updated successfully.";
        $response = ['success' => true, 'message' => $message];
    } else {
        $message = "Error updating appointment.";
        $response = ['success' => false, 'message' => $message];
    }

    echo json_encode($response);
} else {
    $message = "Invalid request.";
    $response = ['success' => false, 'message' => $message];
    echo json_encode($response);
}
?>