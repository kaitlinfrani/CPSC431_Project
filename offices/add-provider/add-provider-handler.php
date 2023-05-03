<?php
session_start();
//require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $occupation = $_POST['occupation'];
    $zipcode = $_POST['zipcode'];
    $food_preference = $_POST['food_preference'];
    $availability = $_POST['date'] . ' ' . $_POST['time'];

    $sql = "INSERT INTO providers (name, occupation, zipcode, food_preference, availability) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $occupation, $zipcode, $food_preference, $availability);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Provider added successfully!";
    } else {
        $_SESSION['error_message'] = "There was an error adding the provider";
    }

    header('Location: add-provider.php');
    exit();
}
?>