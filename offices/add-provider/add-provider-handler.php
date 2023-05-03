<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $occupation = $_POST['occupation'];
    $zipcode = $_POST['zipcode'];
    $food_preference = $_POST['food_preference'];
    $availability_date = $_POST['date'];
    $availability_time = $_POST['time'];

    $sql = "INSERT INTO providers (first_name, last_name, occupation, zipcode, food_preference, availability_date, availability_time) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $first_name, $last_name, $occupation, $zipcode, $food_preference, $availability_date, $availability_time);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Provider added successfully!";
    } else {
        $_SESSION['error_message'] = "There was an error adding the provider";
    }

    header('Location: add-provider.php');
    exit();
}
?>