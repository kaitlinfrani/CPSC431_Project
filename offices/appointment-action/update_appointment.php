<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Handle accept appointment form submission
if (isset($_POST['accept_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE appointments_messages SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $status, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Appointment accepted successfully.';
    } else {
        $_SESSION['error'] = 'Error accepting appointment: ' . $conn->error;
    }

    header('Location: pending.php');
    exit();
}

// Handle reject appointment form submission
if (isset($_POST['reject_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE appointments_messages SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $status, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Appointment rejected successfully.';
    } else {
        $_SESSION['error'] = 'Error rejecting appointment: ' . $conn->error;
    }

    header('Location: pending.php');
    exit();
}