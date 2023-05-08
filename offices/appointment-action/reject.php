<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Update the appointment status to accepted
    $update_appointment_sql = "UPDATE appointments_messages SET status = 'rejected' WHERE id = '$appointment_id'";
    if ($conn->query($update_appointment_sql) === TRUE) {
        echo "Appointment rejected successfully";
    } else {
        echo "Error updating appointment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rejected Appointments</title>
    <link rel="stylesheet" type="text/css" href="view_accept.css"/>
</head>
<form method="post" action="../office-landing.php">
            <button type="submit" class="btn-back">Go Back</button>
            </div>
        </form>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
      }
      
      /* Style for the form */
      form {
        max-width: 500px;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      }
      
      /* Style for the heading */
      h1 {
        text-align: center;
        margin-top: 0;
        margin-bottom: 20px;
      }
      
      /* Style for the button */
      button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-bottom: 20px;
        border-radius: 5px;
        cursor: pointer;
      }
      
      /* Style for the button:hover */
      button:hover {
        background-color: #3e8e41;
      }
      
      /* Style for the form group */
      .form-group {
        margin-bottom: 20px;
      }
      
      /* Style for the label */
      label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
      }
      
      /* Style for the input */
      input {
        display: block;
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
      }
      
      /* Style for the error message */
      .error {
        color: red;
        margin-bottom: 20px;
      }
      
      /* Style for the success message */
      .success {
        color: green;
        margin-bottom: 20px;
      }
      
      /* Style for the back button */
      .btn-back {
        background-color: #f44336;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-bottom: 20px;
        border-radius: 5px;
        cursor: pointer;
      }
      
      /* Style for the back button:hover */
      .btn-back:hover {
        background-color: #c9302c;
      }
</style>
</html>