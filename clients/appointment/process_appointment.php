<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: index.html');
    exit();
}

// Include your database connection file
require_once '../shared/db_connection.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $appointment_date = $_POST['appointment_date'];
    $start_time = $_POST['start_time'];
    // Calculate end_time by adding 30 minutes to start_time
    $start_time_object = DateTime::createFromFormat('H:i', $start_time);
    $start_time_object->add(new DateInterval('PT60M'));
    $end_time = $start_time_object->format('H:i');
    $user_id = $_POST['user_id'];
    $provider_id = $_POST['provider_id'];
    $message = $_POST['message'];

    // Check if the appointment time is within the provider's availability
    $sql = "SELECT * FROM availabilities WHERE provider_id = ? AND day_of_week = DAYNAME(?) AND TIME_TO_SEC(start_time) <= TIME_TO_SEC(?) AND TIME_TO_SEC(end_time) > TIME_TO_SEC(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $provider_id, $appointment_date, $start_time, $end_time);
    $stmt->execute();
    $availability_result = $stmt->get_result();

    if ($availability_result->num_rows > 0) {
        // Appointment time is within the provider's availability

        // Check if there is an accepted appointment with the same provider, date, and time
        $sql = "SELECT * FROM appointments_messages WHERE provider_id = ? AND appointment_date = ? AND start_time = ? AND status = 'accepted'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $provider_id, $appointment_date, $start_time);
        $stmt->execute();
        $appointment_result = $stmt->get_result();

        if ($appointment_result->num_rows > 0) {
            // There is already an accepted appointment with the same provider, date, and time
            $_SESSION['error_message'] = "The selected appointment time is already booked.";
            header('Location: schedule.php?provider_id=' . $provider_id);
            exit();
        }

        // Insert appointment and message into the database
        $sql = "INSERT INTO appointments_messages (client_id, provider_id, appointment_date, start_time, end_time, message, status) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisssss', $user_id, $provider_id, $appointment_date, $start_time, $end_time, $message, $status);

        // Set the status to "pending"
        $status = "pending";

        if ($stmt->execute()) {
            // Appointment and message successfully inserted
            $_SESSION['success_message'] = "Appointment scheduled and message sent successfully.";
            header('Location: ../client-landing.php');
            exit();
        } else {
            // Insertion failed
            $_SESSION['error_message'] = "An error occurred while scheduling the appointment and sending the message.";
            header('Location: schedule.php?provider_id=' . $provider_id);
            exit();
        }
    } else {
        // Appointment time is outside the provider's availability
        $_SESSION['error_message'] = "The selected appointment time is outside the provider's availability.";
        header('Location: schedule.php?provider_id=' . $provider_id);
        exit();
    }
}