<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Get the appointment ID from the request parameter (assuming it was sent via GET or POST)
$appointment_id = $_REQUEST['appointment_id'];

// Update the status of the appointment in the database
$conn = mysqli_connect('localhost', 'username', 'password', 'database_name');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE appointments SET status='accepted' WHERE id=$appointment_id";
if (mysqli_query($conn, $sql)) {
    echo "Appointment status updated successfully";
} else {
    echo "Error updating appointment status: " . mysqli_error($conn);
}

mysqli_close($conn);


// Handle accept appointment form submission
/*
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
}*/
?>