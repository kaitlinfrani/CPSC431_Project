<?php
require_once '../shared/db_connection.php';

if (isset($_POST['appointmentId']) && isset($_POST['message'])) {
    $appointmentId = $_POST['appointmentId'];
    $newMessage = $_POST['message'];

    $sql = "UPDATE appointments_messages SET message = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newMessage, $appointmentId);

    if ($stmt->execute()) {
        echo "Message updated successfully.";
    } else {
        echo "Error updating message: " . $conn->error;
    }
    $stmt->close();
    } else {
    echo "Invalid request.";
}
?>