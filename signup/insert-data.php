<?php
  // Connect to the database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "project";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get the form data
  $name = "";
  $officeName = "";
  $occupation = "";
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm-password'];
  $userType = $_POST['user-type'];

  // Check if the confirm password matches the password
  if ($password !== $confirmPassword) {
    die("Error: Passwords do not match");
  }

  // Insert the data into the appropriate table based on user type
  if ($userType === 'provider') {
    $name = $_POST['Name'];
    $officeName = $_POST['office-name'];
    $sql = "INSERT INTO providers (name, office_name, email, password) VALUES ('$name', '$officeName', '$email', '$password')";
  } else if ($userType === 'client') {
    $name = $_POST['Name'];
    $occupation = $_POST['occupation'];
    if($occupation === "Other") {
      $occupation = $_POST['custom-occupation'];
    }
    $sql = "INSERT INTO clients (name, email, password, occupation) VALUES ('$name', '$email', '$password', '$occupation')";
  } else {
    die("Error: Invalid user type");
  }

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close the database connection
  $conn->close();
?>
