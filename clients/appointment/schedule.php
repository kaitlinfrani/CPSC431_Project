<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: index.html');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Get provider ID from URL
$provider_id = $_GET['provider_id'];

// Prepare the SQL statement
$sql = "SELECT * FROM providers WHERE id = " . $provider_id;
$result = $conn->query($sql);
$provider = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Schedule Appointment</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
    </header>

    <div class="main-content">
        <h2>Schedule an Appointment</h2>

        <div class="appointment-box">
            <h3>Provider Information</h3>
            <p>Name: <?php echo $provider['first_name'] . ' ' . $provider['last_name']; ?></p>
            <p>Occupation: <?php echo $provider['occupation']; ?></p>
            <p>Zipcode: <?php echo $provider['zipcode']; ?></p>
            <p>Food Preference: <?php echo $provider['food_preference']; ?></p>
            <p>Availability: <?php echo $provider['availability']; ?></p>
        </div>

        <div class="message-form">
            <h3>Send Message</h3>
            <form action="process_form.php" method="post">
                <textarea name="message" placeholder="Type your message here..." required></textarea>
                <input type="hidden" name="provider_id" value="<?php echo $provider_id; ?>">
                <div class="button-container">
                    <button type="submit" class="send-message">Send Message</button>
                    <button type="button" class="go-back" onclick="history.back()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>