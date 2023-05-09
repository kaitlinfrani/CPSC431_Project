<?php
session_start();
// to check array names: print_r($_SESSION);
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../homepage/homepage.php');
    exit();
}
require_once 'db_connection.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider_id = $_POST['provider_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $occupation = $_POST['occupation'];
    $zipcode = $_POST['zipcode'];
    $food_preference = $_POST['food_preference'];

    // Update the provider information in the database
    $sql = "UPDATE providers SET first_name = ?, last_name = ?, occupation = ?, zipcode = ?, food_preference = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $first_name, $last_name, $occupation, $zipcode, $food_preference, $provider_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Provider information updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update provider information.";
    }

    // Redirect back to the providers page
    header("Location: ../office-landing.php");
    exit();
}

// Check if the provider ID has been set
if (!isset($_GET['id'])) {
    header("Location: providers.php");
    exit();
}

$provider_id = $_GET['id'];

// Prepare the SQL statement to retrieve the provider information
$sql = "SELECT * FROM providers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a provider with the given ID exists
if ($result->num_rows !== 1) {
    header("Location: providers.php");
    exit();
}

// Fetch the provider information
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Provider</title>
    <link rel="stylesheet" href="edit.css"/>
</head>
<body>
    <header>
        <h1>Edit Provider</h1>
    </header>
    <main>
        <form method="post" action="">
            <input type="hidden" name="provider_id" value="<?php echo $row['id']; ?>">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>">
            <label for="occupation">Occupation:</label>
            <input type="text" name="occupation" value="<?php echo $row['occupation']; ?>">
            <label for="zipcode">Zipcode:</label>
            <input type="text" name="zipcode" value="<?php echo $row['zipcode']; ?>">
            <label for="food_preference">Food Preference:</label>
            <input type="text" name="food_preference" value="<?php echo $row['food_preference']; ?>">
            <input type="submit" value="Update Provider">
        </form>
    </main>
</body>
</html>
