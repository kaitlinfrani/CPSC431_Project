<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $occupation = $_POST['occupation'];
    $zipcode = $_POST['zipcode'];
    $food_preference = $_POST['food_preference'];
    $medical_office_id = $_SESSION['medical_office_id']; // Get the office ID from session

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
            $provider_id = $conn->insert_id;
            $_SESSION['success_message'] = "Provider added successfully!";
            // Insert availability information
            $days_of_week = $_POST['day_of_week'];
            $start_times = $_POST['start_time'];
            $end_times = $_POST['end_time'];

            $sql = "INSERT INTO availabilities (provider_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            for ($i = 0; $i < count($days_of_week); $i++) {
                $day_of_week = $days_of_week[$i];
                $start_time = $start_times[$i];
                $end_time = $end_times[$i];

                $stmt->bind_param("isss", $provider_id, $day_of_week, $start_time, $end_time);

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Provider availability added successfully!";
                } else {
                    $_SESSION['error_message'] = "There was an error adding the provider's availability";
                }
            }
        } else {
            $_SESSION['error_message'] = "There was an error adding the provider";
        }
    } else {
        $_SESSION['error_message'] = "Invalid medical office ID";
    }

    header('Location: ../office-landing.php');
    exit();
}
?>
