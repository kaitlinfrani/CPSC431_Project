<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: index.html');
    exit();
}

if (isset($_POST['appointment_id']) && isset($_POST['new_message'])) {
    require_once 'db_connection.php';

    $appointment_id = $_POST['appointment_id'];
    $new_message = $_POST['new_message'];

    $update_message_sql = "UPDATE appointments_messages SET message = ? WHERE id = ?";

    if ($stmt = $conn->prepare($update_message_sql)) {
        $stmt->bind_param("si", $new_message, $appointment_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Message updated successfully";
        } else {
            echo "Error updating message";
        }
    } else {
        echo "Error preparing statement";
    }
} else {
    echo "Invalid request";
}
?>