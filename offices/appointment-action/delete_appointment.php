<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    echo "error";
    exit();
}

require_once '../db_connection.php';

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    $sql = "DELETE FROM appointments_messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $appointment_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>