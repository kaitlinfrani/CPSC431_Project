<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Fetch pending appointments
$pending_appointments_sql = "SELECT appointments_messages.*, providers.first_name, providers.last_name, providers.occupation, providers.zipcode, providers.food_preference
                             FROM appointments_messages
                             JOIN providers ON appointments_messages.provider_id = providers.id
                             WHERE appointments_messages.status = 'pending'";

$pending_appointments_result = $conn->query($pending_appointments_sql);
$pending_appointments = [];

while ($appointment = $pending_appointments_result->fetch_assoc()) {
    $pending_appointments[] = $appointment;
}

// Handle accept appointment form submission
if (isset($_POST['accept_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $update_appointment_sql = "UPDATE appointments_messages SET status = 'accepted' WHERE id = $appointment_id";
    if ($conn->query($update_appointment_sql) === TRUE) {
        $_SESSION['message'] = 'Appointment accepted successfully.';
        header('Location: pending_appointments.php');
        exit();
    } else {
        $_SESSION['error'] = 'Error accepting appointment: ' . $conn->error;
        header('Location: pending_appointments.php');
        exit();
    }
}

// Handle reject appointment form submission
if (isset($_POST['reject_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $update_appointment_sql = "UPDATE appointments_messages SET status = 'rejected' WHERE id = $appointment_id";
    if ($conn->query($update_appointment_sql) === TRUE) {
        $_SESSION['message'] = 'Appointment rejected successfully.';
        header('Location: pending_appointments.php');
        exit();
    } else {
        $_SESSION['error'] = 'Error rejecting appointment: ' . $conn->error;
        header('Location: pending_appointments.php');
        exit();
    }
}
?>
