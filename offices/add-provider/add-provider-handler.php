<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $occupation = $_POST['occupation'];
    $zipcode = $_POST['zipcode'];
    $food_preference = $_POST['food_preference'];

    // Insert provider information
    $sql = "INSERT INTO providers (first_name, last_name, occupation, zipcode, food_preference) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $occupation, $zipcode, $food_preference);

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

        if (!$stmt->execute()) {
            $_SESSION['error_message'] = "There was an error adding the provider's availability";
        }
    } else {
        $_SESSION['error_message'] = "There was an error adding the provider";
    }

    header('Location: add-provider.php');
    exit();
}
?>