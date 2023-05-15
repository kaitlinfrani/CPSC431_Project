<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once '../shared/db_connection.php';

// Fetch rejected and cancelled appointments
$rejected_appointments_sql = "SELECT appointments_messages.*, providers.first_name, providers.last_name, providers.occupation, providers.zipcode, providers.food_preference
                              FROM appointments_messages
                              JOIN providers ON appointments_messages.provider_id = providers.id
                              WHERE appointments_messages.status IN ('rejected', 'cancelled')";

$rejected_appointments_result = $conn->query($rejected_appointments_sql);
$rejected_appointments = [];

while ($appointment = $rejected_appointments_result->fetch_assoc()) {
    $rejected_appointments[] = $appointment;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="scripts.js" defer></script>
    <title>Rejected Appointments</title>
</head>

<body>
    <div id="deleteModal" class="delete-modal">
        <div class="modal-content">
            <h2>Delete Appointment</h2>
            <p>Are you sure you want to delete this appointment?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="confirm-btn">Delete</button>
                <button id="cancelDelete" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
    </header>

    <div class="main-content">

        <div class="header-wrapper">
            <a class="go-back" href="../client-landing.php">Go back to profile</a>
            <div class="header-container">
                <h2>Rejected Appointments</h2>
            </div>
        </div>


        <?php if (count($rejected_appointments) > 0): ?>
        <ul>
            <?php foreach ($rejected_appointments as $appointment): ?>
            <li>
                <h3>Appointment with <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></h3>
                <p>Occupation: <?php echo $appointment['occupation']; ?></p>
                <p>Zipcode: <?php echo $appointment['zipcode']; ?></p>
                <p>Food Preference: <?php echo $appointment['food_preference']; ?></p>
                <p>Appointment Date: <?php echo $appointment['appointment_date']; ?></p>
                <p>Start Time: <?php echo $appointment['start_time']; ?></p>
                <p>End Time: <?php echo $appointment['end_time']; ?></p>
                <p>Message: <?php echo $appointment['message']; ?></p>
                <p>Status: <?php echo ucfirst($appointment['status']); ?></p>
                <button class="delete-btn" data-appointment-id="<?php echo $appointment['id']; ?>">Delete</button>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>No rejected appointments.</p>
        <?php endif; ?>
        <script src="delete.js"></script>
    </div>
</body>

</html>