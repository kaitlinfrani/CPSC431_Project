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

// Fetch availabilities for the current provider
$availabilities_sql = "SELECT * FROM availabilities WHERE provider_id = $provider_id";
$availabilities_result = $conn->query($availabilities_sql);
$availabilities = [];


if ($availabilities_result->num_rows > 0) {
    while ($availability = $availabilities_result->fetch_assoc()) {
        $availabilities[] = $availability;
    }
}

$accepted_appointments_sql = "SELECT * FROM appointments_messages WHERE provider_id = $provider_id AND status = 'accepted'";
$accepted_appointments_result = $conn->query($accepted_appointments_sql);
$accepted_appointments = [];

while ($appointment = $accepted_appointments_result->fetch_assoc()) {
    $accepted_appointments[] = $appointment;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Schedule Appointment</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert error-message">' . $_SESSION['error_message'] . '</div>';

            // Unset the success message so it doesn't keep showing up on refresh
            unset($_SESSION['error_message']);
        }
        ?>
    </header>

    <div class="main-content">
        <h2>Schedule an Appointment</h2>

        <div class="appointment-box">
            <h3>Provider Information</h3>
            <p>Name: <?php echo $provider['first_name'] . ' ' . $provider['last_name']; ?></p>
            <p>Occupation: <?php echo $provider['occupation']; ?></p>
            <p>Zipcode: <?php echo $provider['zipcode']; ?></p>
            <p>Food Preference: <?php echo $provider['food_preference']; ?></p>
            <p>Availability:
            <ul>
                <?php foreach ($availabilities as $availability): ?>
                <li><?php echo $availability['day_of_week'] . ": " . $availability['start_time'] . " - " . $availability['end_time']; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            </p>
        </div>

        <div class="appointment-form">
            <h3>Schedule an Appointment</h3>
            <form action="process_appointment.php" method="post">
                <label for="appointment_date">Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" required>

                <label for="start_time">Start Time:</label>
                <p>Appointments are scheduled every hour.</p>
                <select name="start_time" id="start_time" required></select>

                <label for="message">Message:</label>
                <textarea name="message" id="message" placeholder="Type your message here..." required></textarea>

                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" name="provider_id" value="<?php echo $provider_id; ?>">

                <button type="submit">Schedule Appointment</button>
                <button type="button" id="cancel_button">Cancel</button>
            </form>
        </div>

    </div>
    <script>
    window.providerAvailability = <?php echo json_encode($availabilities); ?>;
    </script>
    <script>
    // Get the provider's accepted appointments as an array of objects
    acceptedAppointments = <?php echo json_encode($accepted_appointments); ?>;
    </script>
    <script src="schedule.js"></script>
    <script src="alert.js"></script>

</body>

</html>