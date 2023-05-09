<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Fetch accepted appointments
$accepted_appointments_sql = "SELECT appointments_messages.*, providers.first_name, providers.last_name, providers.occupation, providers.zipcode, providers.food_preference
                             FROM appointments_messages
                             JOIN providers ON appointments_messages.provider_id = providers.id
                             WHERE appointments_messages.status = 'accepted'
                             AND providers.medical_office_id = '".$_SESSION['medical_office_id']."'";

$accepted_appointments_result = $conn->query($accepted_appointments_sql);
$accepted_appointments = [];

while ($appointment = $accepted_appointments_result->fetch_assoc()) {
    $accepted_appointments[] = $appointment;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view_accept.css">
    <script src="scripts.js" defer></script>
    <title>Accepted Appointments</title>
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
    </header>
    <div id="message-container" style="display: none;"></div>

    <div class="main-content">
        <div class="header-wrapper">
            <a class="go-back" href="../office-landing.php">Go back to profile</a>
            <div class="header-container">
                <h2>Accepted Appointments</h2>
            </div>
        </div>

        <?php if (count($accepted_appointments) > 0): ?>
        <ul>
            <?php foreach ($accepted_appointments as $appointment): ?>
            <?php
            $appointment_id = $appointment['id'];
            $start_time = date("g:i A", strtotime($appointment["start_time"]));
            $end_time = date("g:i A", strtotime($appointment["end_time"]));
            ?>
            <li>
                <h3>Appointment with <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></h3>
                <p>Occupation: <?php echo $appointment['occupation']; ?></p>
                <p>Zipcode: <?php echo $appointment['zipcode']; ?></p>
                <p>Food Preference: <?php echo $appointment['food_preference']; ?></p>
                <p>Appointment Date: <?php echo $appointment['appointment_date']; ?></p>
                <p>Start Time: <?php echo $start_time; ?></p>
                <p>End Time: <?php echo $end_time; ?></p>
                <p>Message: <?php echo $appointment['message']; ?></p>
                <button class="cancel-button"
                    onclick="openCancelAppointmentModal('cancelAppointmentModal', '<?php echo $appointment_id; ?>')">Cancel
                    Appointment</button>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>No accepted appointments.</p>
        <?php endif; ?>

        <!-- Cancel Appointment Modal -->
        <div id="cancelAppointmentModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Cancel Appointment</h2>
                <p>Are you sure you want to cancel this appointment?</p>
                <div class="button-container">
                    <button class="confirm-cancel">Yes, Cancel Appointment</button>
                </div>
            </div>
        </div>
</body>

</html>