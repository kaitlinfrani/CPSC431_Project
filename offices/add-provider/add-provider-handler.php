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
        // Check for availability conflicts before adding the provider
        $days_of_week = $_POST['day_of_week'];
        $start_times = $_POST['start_time'];
        $end_times = $_POST['end_time'];
        $conflict = false;
        for ($i = 0; $i < count($days_of_week); $i++) {
            $day_of_week = $days_of_week[$i];
            $start_time = $start_times[$i];
            $end_time = $end_times[$i];

            // Check for availability conflicts with all providers in the same medical office
            $sql = "SELECT * FROM providers p 
            INNER JOIN availabilities a ON p.id = a.provider_id 
            WHERE p.medical_office_id = ? 
            AND a.day_of_week = ? 
            AND (
                (? BETWEEN a.start_time AND a.end_time) 
                OR (? BETWEEN a.start_time AND a.end_time) 
                OR (? <= a.start_time AND ? >= a.end_time)
            )";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssss", $medical_office_id, $day_of_week, $start_time, $end_time, $start_time, $end_time);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $conflict = true;
                $_SESSION['error_message'] = "There is an availability conflict with another provider.";
                unset($_SESSION['error_message']);
                //break; // exit the loop if there is a conflict
            }
        }

        if ($conflict) {
            // There is an availability conflict, do not add the provider
            $_SESSION['error_message'] = "There is an availability conflict with another provider.";
            header("Location: ../office-landing.php");
            exit();
        } else {
            // No availability conflicts, add the provider
            $sql = "INSERT INTO providers (first_name, last_name, occupation, zipcode, food_preference, medical_office_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $first_name, $last_name, $occupation, $zipcode, $food_preference, $medical_office_id);

            if ($stmt->execute()) {
                $provider_id = $conn->insert_id;
                // Insert availability information
                $conflict = false; // flag to indicate if there is an availability conflict
                for ($i = 0; $i < count($days_of_week); $i++) {
                    $day_of_week = $days_of_week[$i];
                    $start_time = $start_times[$i];
                    $end_time = $end_times[$i];

                    // Check for availability conflicts with all providers in the same medical office
                    $sql = "SELECT * FROM providers p 
                            INNER JOIN availabilities a ON p.id = a.provider_id 
                            WHERE p.medical_office_id = ? 
                            AND a.day_of_week = ? 
                            AND (
                                (? BETWEEN a.start_time AND a.end_time) 
                                OR (? BETWEEN a.start_time AND a.end_time) 
                                OR (? <= a.start_time AND ? >= a.end_time)
                            )";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isssss", $medical_office_id, $day_of_week, $start_time, $end_time, $start_time, $end_time);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $conflict = true;
                        // Delete the provider since there is an availability conflict
                        $sql = "DELETE FROM providers WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $provider_id);
                        $stmt->execute();
                        $_SESSION['error_message'] = "There is an availability conflict with another provider.";
                        break; // exit the loop if there is a conflict
                    }

                    // Insert new availability
                    $sql = "INSERT INTO availabilities (provider_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isss", $provider_id, $day_of_week, $start_time, $end_time);
                    $stmt->execute();
                }
                if (!$conflict) {
                    $_SESSION['success_message'] = "Provider added successfully!";
                    header('Location: ../office-landing.php');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "There was an error adding the provider";
            }
        }
    }
    header('Location: ../office-landing.php');
    exit();
}
?>