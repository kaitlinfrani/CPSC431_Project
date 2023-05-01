<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $occupation = $_POST['occupation'];
    $user_id = $_SESSION['user_id'];
    $other = isset($_POST['other']) ? $_POST['other'] : null;

    if ($password != '' && $password != $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match";
        header('Location: edit-profile.php');
        exit();
    }

    // If occupation is 'Other', use the 'other' value
    if ($occupation === 'Other') {
        $occupation = $other;
    }

    $sql = "UPDATE clients SET name=?, email=?, occupation=?";

    $params = [$name, $email, $occupation];

    if ($password != '') {
        $sql .= ", password=?";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $params[] = $hashedPassword;
    }

    $sql .= " WHERE id=?";

    $params[] = $user_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error_message'] = "There was an error updating your profile";
    }

    header('Location: edit-profile.php');
    exit();
}
?>