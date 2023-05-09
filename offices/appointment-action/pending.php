<?php
// Start the session
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['name'])) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

// Include your database connection file
require_once 'db_connection.php';

// Fetch pending appointments

$pending_appointments_sql = "SELECT appointments_messages.*, providers.first_name, providers.last_name, providers.occupation, providers.zipcode, providers.food_preference
                             FROM appointments_messages
                             JOIN providers ON appointments_messages.provider_id = providers.id
                             WHERE appointments_messages.status = 'pending'
                             AND providers.medical_office_id = '".$_SESSION['medical_office_id']."'";

$pending_appointments_result = $conn->query($pending_appointments_sql);
$pending_appointments = [];

while ($appointment = $pending_appointments_result->fetch_assoc()) {
    $pending_appointments[] = $appointment;
}


// Fetch providers under the office ID
$providers_sql = "SELECT * FROM providers WHERE medical_office_id = ?";
$stmt = $conn->prepare($providers_sql);
$stmt->bind_param("i", $_SESSION['medical_office_id']);
$stmt->execute();
$providers_result = $stmt->get_result();
$providers = [];

while ($provider = $providers_result->fetch_assoc()) {
    $providers[] = $provider;
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style3.css">
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
            <a class="go-back" href="../office-landing.php">Go back to profile</a>
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
                    <form method="post" action="accept.php">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" name="submit" class="accept-button">Accept Appointment</button>
                    </form>
                    <form method="post" action="reject.php">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" name="submit" class="reject-button">Reject Appointment</button>
                    </form>
                </div>

            </li>
            <!--Shows all of the appointments-->
            <?php endforeach; ?>
            
        </ul>
        <?php else: ?>
        <p>No pending appointments.</p>
        <?php endif; ?>


    </div>
</body>
</html>