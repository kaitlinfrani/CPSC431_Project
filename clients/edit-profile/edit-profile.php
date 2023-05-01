<!-- edit-profile.php -->
<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.html');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM clients WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="edit-profile.js"></script>
</head>

<body>
    <h1>Edit Profile</h1>
    <div class="container">
        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> <?php echo $_SESSION['error_message']; ?>
        </div>
        <?php
        unset($_SESSION['error_message']);
        endif;
        ?>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php
        unset($_SESSION['success_message']);
        endif;
        ?>

        <form method="POST" action="edit-profile-handler.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" />

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" />

            <label for="occupation">Occupation:</label>
            <select id="occupation" name="occupation" required onchange="showOtherField()">
                <option value="Doctor" <?php echo ($user['occupation'] == 'Doctor') ? 'selected' : ''; ?>>Doctor
                </option>
                <option value="Pharmaceutical"
                    <?php echo ($user['occupation'] == 'Pharmaceutical') ? 'selected' : ''; ?>>Pharmaceutical Company
                </option>
                <option value="Medical" <?php echo ($user['occupation'] == 'Medical') ? 'selected' : ''; ?>>Medical
                    Company</option>
                <option value="Other"
                    <?php echo (!in_array($user['occupation'], ['Doctor', 'Pharmaceutical', 'Medical'])) ? 'selected' : ''; ?>>
                    Other</option>
            </select>

            <div id="otherOccupation"
                style="display: <?php echo (!in_array($user['occupation'], ['Doctor', 'Pharmaceutical', 'Medical'])) ? 'block' : 'none'; ?>;">
                <label for="other">Please specify:</label>
                <input type="text" id="other" name="other"
                    value="<?php echo (!in_array($user['occupation'], ['Doctor', 'Pharmaceutical', 'Medical'])) ? $user['occupation'] : ''; ?>" />
            </div>


            <input type="submit" name="submit" value="Update Profile" />
        </form>
        <button class="back-to-profile" onclick="location.href='/project/clients/client-landing.php'">Back to
            Profile</button>

    </div>
</body>

</html>