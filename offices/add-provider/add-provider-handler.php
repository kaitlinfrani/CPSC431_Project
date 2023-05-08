<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $occupation = $_POST['occupation'];
    $zipcode = $_POST['zipcode'];
    $food_preference = $_POST['food_preference'];
    $medical_office_id = $_POST['medical_office_id'];

    // Check if the medical_office_id value exists in the offices table
    $sql = "SELECT id FROM offices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $medical_office_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Insert provider information
        $sql = "INSERT INTO providers (first_name, last_name, occupation, zipcode, food_preference, medical_office_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $first_name, $last_name, $occupation, $zipcode, $food_preference, $medical_office_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Provider added successfully!";
            $provider_id = $conn->insert_id;

            // Insert availability information
            $day_of_week = $_POST['day_of_week'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];

            $sql = "INSERT INTO availabilities (provider_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $provider_id, $day_of_week, $start_time, $end_time);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Provider availability added successfully!";
            } else {
                $_SESSION['error_message'] = "There was an error adding the provider's availability";
            }
        } else {
            $_SESSION['error_message'] = "There was an error adding the provider";
        }
    } else {
        $_SESSION['error_message'] = "Invalid medical office ID";
    }

    header('Location: add-provider.php');
    exit();
}
?>