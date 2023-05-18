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
    $availability_day = $_POST['day_of_week'];
    $availability_start = $_POST['start_time'];
    $availability_end = $_POST['end_time'];
    $active_inactive = isset($_POST['inactive']) ? 0 : 1;

    // Check if the update button was clicked
    if (isset($_POST['update_provider'])) {
        // Update the provider information in the database
        $sql = "UPDATE providers SET first_name = ?, last_name = ?, occupation = ?, zipcode = ?, food_preference = ?, active_inactive = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $occupation, $zipcode, $food_preference, $active_inactive, $provider_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Provider information updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update provider information.";
        }
    }
    
    // Check if the delete button was clicked
    if (isset($_POST['delete_provider'])) {
        // Delete the provider's availabilities
        $deleteAvailabilitiesSql = "DELETE FROM availabilities WHERE provider_id = ?";
        $deleteAvailabilitiesStmt = $conn->prepare($deleteAvailabilitiesSql);
        $deleteAvailabilitiesStmt->bind_param("i", $provider_id);

        // Execute the deletion of availabilities
        if ($deleteAvailabilitiesStmt->execute()) {
            // Delete the provider from the database
            $deleteProviderSql = "DELETE FROM providers WHERE id = ?";
            $deleteProviderStmt = $conn->prepare($deleteProviderSql);
            $deleteProviderStmt->bind_param("i", $provider_id);

            // Execute the deletion of the provider
            if ($deleteProviderStmt->execute()) {
                $_SESSION['success_message'] = "Provider deleted successfully.";
                // Redirect to a suitable page after deletion
                header("Location: ../office-landing.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to delete provider.";
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete availabilities.";
        }
    }

    // Update the provider's availabilities in the database
    if (isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['day_of_week'])) {
        $start_times = $_POST['start_time'];
        $end_times = $_POST['end_time'];
        $days_of_week = $_POST['day_of_week'];

        // Delete existing availabilities for the provider
        $delete_sql = "DELETE FROM availabilities WHERE provider_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $provider_id);
        $delete_stmt->execute();

        // Insert updated availabilities into the database
        $insert_sql = "INSERT INTO availabilities (provider_id, start_time, end_time, day_of_week) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("isss", $provider_id, $start_time, $end_time, $day_of_week);

        // Update each availability
        for ($i = 0; $i < count($start_times); $i++) {
            $start_time = $start_times[$i];
            $end_time = $end_times[$i];
            $day_of_week = $days_of_week[$i];

            if (!$insert_stmt->execute()) {
                $_SESSION['error_message'] = "Failed to update availabilities.";
                break;
            }
            else {
                $_SESSION['success_message'] = "Provider information updated successfully.";
            }
        }
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
            <label for="inactive">Inactive:</label>
            <input type="checkbox" id="inactive" name="inactive" <?php echo ($row['active_inactive'] == 0) ? 'checked' : ''; ?>>
            <?php $active_inactive = isset($_POST['inactive']) ? 0 : 1; ?>
            <!-- Edit availability script -->
            <h2>Edit Availability</h2>
            <?php
            // Prepare the SQL statement to retrieve the provider's availabilities
            $availability_sql = "SELECT * FROM availabilities WHERE provider_id = ?";
            $availability_stmt = $conn->prepare($availability_sql);
            $availability_stmt->bind_param("i", $provider_id);
            $availability_stmt->execute();
            $availability_result = $availability_stmt->get_result();

            // Check if the provider has any availability set
            if ($availability_result->num_rows === 0) {
                echo "<p>No availability set for this provider.</p>";
            } else {
                // Fetch and display all availabilities
                while ($availability_row = $availability_result->fetch_assoc()) {
                    ?>
                    <label for="start_time">Start Time:</label>
                    <input type="time" name="start_time[]" value="<?php echo htmlentities($availability_row['start_time']); ?>">
                    <label for="end_time">End Time:</label>
                    <input type="time" name="end_time[]" value="<?php echo htmlentities($availability_row['end_time']); ?>">
                    <label for="day_of_week">Day of Week:</label>
                    <select name="day_of_week[]">
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($days as $day) {
                            $selected = ($day == $availability_row['day_of_week']) ? "selected" : "";
                            echo "<option value='$day' $selected>$day</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
            }
            ?>
            <!-- End of edit availabiltiy-->

            <!-- Update provider -->
            <input type="submit" name="update_provider" value="Update Provider">

            <!-- Delete provider -->
            <input type="submit" name="delete_provider" value="Delete Provider">
        </form>
    </main>
</body>
<!-- Go back -->
<button class="back-to-profile" onclick="location.href='/project/offices/office-landing.php'">Back to Profile</button>
</html>