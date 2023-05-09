<?php
session_start();
require_once 'db_connection.php';
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

$office_id = $_GET['medical_office_id'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Provider</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <header>
        <h1>Add Provider</h1>
        <form method="post" action="../office-landing.php">
            <button type="submit" class="btn-back">Go Back</button>
        </form>
    </header>
    <main>
        <form method="post" action="add-provider-handler.php">
            <div class="input-group">
                <label>First Name:</label>
                <input type="text" name="first_name" required />
            </div>
            <div class="input-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" required />
            </div>
            <div class="input-group">
                <label>Occupation:</label>
                <input type="text" name="occupation" required />
            </div>
            <div class="input-group">
                <label>Zipcode:</label>
                <input type="text" name="zipcode" required />
            </div>
            <div class="input-group">
                <label>Food Preference:</label>
                <input type="text" name="food_preference" required />
            </div>
            
            <div class="input-group">
                <label>Medical Office ID:</label>
                <input type="text" name="medical_office_id" value="<?php echo $office_id; ?>">

            </div>

            <div class="input-group">
                <label>Availability:</label>
                <div class="availability-inputs">
                    <div class="availability-input">
                        <label>Day of Week:</label>
                        <select name="day_of_week" required>
                            <option value="">Select a day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="availability-input">
                        <label>Start Time:</label>
                        <input type="time" name="start_time" required />
                    </div>
                    <div class="availability-input">
                        <label>End Time:</label>
                        <input type="time" name="end_time" required />
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn">Add Provider</button>
        </form>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert success-message">' . $_SESSION['success_message'] . '</div>';

            // Unset the success message so it doesn't keep showing up on refresh
            unset($_SESSION['success_message']);
        }
        ?>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert error-message">' . $_SESSION['error_message'] . '</div>';

            // Unset the error message so it doesn't keep showing up on refresh
            unset($_SESSION['error_message']);
        }
        ?>
    </main>
</body>
</html>