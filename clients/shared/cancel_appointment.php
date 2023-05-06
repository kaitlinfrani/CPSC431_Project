<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: index.html');
    exit();
}

if (isset($_POST['appointment_id'])) {
    require_once 'db_connection.php';

    $appointment_id = $_POST['appointment_id'];

    // Change the SQL query to update the status instead of deleting the row
    $cancel_appointment_sql = "UPDATE appointments_messages SET status = 'cancelled' WHERE id = ?";

    if ($stmt = $conn->prepare($cancel_appointment_sql)) {
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Appointment cancelled successfully";
        } else {
            echo "Error cancelling appointment";
        }
    } else {
        echo "Error preparing statement";
    }
} else {
    echo "Invalid request";
}
?>