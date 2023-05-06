<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once '../shared/db_connection.php';

// Fetch pending appointments
$pending_appointments_sql = "SELECT appointments_messages.*, providers.first_name, providers.last_name, providers.occupation, providers.zipcode, providers.food_preference
                             FROM appointments_messages
                             JOIN providers ON appointments_messages.provider_id = providers.id
                             WHERE appointments_messages.status = 'pending'";

$pending_appointments_result = $conn->query($pending_appointments_sql);
$pending_appointments = [];

while ($appointment = $pending_appointments_result->fetch_assoc()) {
    $pending_appointments[] = $appointment;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="scripts.js" defer></script>
    <title>Pending Appointments</title>
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
    </header>
    <div id="message-container" style="display: none;"></div>


    <div class="main-content">

        <div class="header-wrapper">
            <a class="go-back" href="../client-landing.php">Go back to profile</a>
            <div class="header-container">
                <h2>Pending Appointments</h2>
            </div>
        </div>


        <?php if (count($pending_appointments) > 0): ?>
        <ul>
            <?php foreach ($pending_appointments as $appointment): ?>
            <?php
            $appointment_id = $appointment['id'];
            $current_message = htmlspecialchars($appointment['message'], ENT_QUOTES);
            ?>
            <li>
                <h3>Appointment with <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></h3>
                <p>Occupation: <?php echo $appointment['occupation']; ?></p>
                <p>Zipcode: <?php echo $appointment['zipcode']; ?></p>
                <p>Food Preference: <?php echo $appointment['food_preference']; ?></p>
                <p>Appointment Date: <?php echo $appointment['appointment_date']; ?></p>
                <p>Start Time: <?php echo $appointment['start_time']; ?></p>
                <p>End Time: <?php echo $appointment['end_time']; ?></p>
                <p>Message: <?php echo $appointment['message']; ?></p>
                <div class="button-container">
                    <button class="edit-button"
                        onclick="openEditMessageModal('editMessageModal', '<?php echo $appointment_id; ?>', '<?php echo $current_message; ?>')">Edit
                        Message</button>
                    <button class="cancel-button"
                        onclick="openCancelAppointmentModal('cancelAppointmentModal', '<?php echo $appointment_id; ?>')">Cancel
                        Appointment</button>
                </div>
            </li>

            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>No pending appointments.</p>
        <?php endif; ?>
        <!-- Edit Message Modal -->
        <div id="editMessageModal" class="modal">
            <div class="modal-content">
                <span class="close">×</span>
                <h2>Edit Message</h2>
                <textarea></textarea>
                <div class="button-container">
                    <button class="save-changes">Save Changes</button>
                </div>
            </div>
        </div>
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
    </div>
</body>

</html>