<!DOCTYPE html>
<html>
<?php 
    include 'create_database_and_tables.php';  // Include the script to create the database and tables
    include 'session_handler.php'; 
?>

<head>
    <title>User Signup Page</title>
    <link rel="stylesheet" type="text/css" href="signup.css" />
    <script src="signup.js"></script>
</head>

<body onload="initializeForm()">
    <h1>User Signup</h1>
    <div class="container">
        <!-- Success Message -->
        <?php if ($signup_result === 'success'): ?>
        <div class="form-alert-container">
            <div class="alert alert-success">
                <strong>Success!</strong> You have successfully signed up.
            </div>
        </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($signup_result === 'error'): ?>
        <div class="form-alert-container">
            <div class="alert alert-danger">
                <strong>Error!</strong> <?php echo $signup_error_message; ?>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="insert-data.php">
            <div>
                <p>Are you a medical office or a client?</p>
                <div class="radio-option">
                    <label><input type="radio" name="user-type" value="office" required
                            onclick="showProviderFields()" />Medical Office</label>
                    <label><input type="radio" name="user-type" value="client" required
                            onclick="showClientFields()" />Client</label>
                </div>


            </div>

            <label for="Name" id="name-label">Name:</label>
            <input type="text" id="name-input" name="name" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required />


            <div id="client-fields">
                <label for="occupation">Occupation:</label>
                <select id="occupation" name="occupation" required onchange="showCustomOccupationInput()">
                    <option value="">Select an occupation</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Pharmaceutical">Pharmaceutical Company</option>
                    <option value="Medical">Medical Company</option>
                    <option value="Other">Other</option>
                </select>
                <input type="text" id="custom-occupation" name="custom-occupation" style="display: none"
                    placeholder="What's your occupation?" />
            </div>

            <input type="submit" name="submit" value="Sign Up" />
            <div class="login-buttons">
                <button onclick="location.href='/project/login/login-office.php'" type="button">Login
                    Provider</button>
                <button onclick="location.href='/project/login/login-client.php'" type="button">Login Client</button>
            </div>

            <button onclick="location.href='/project/homepage/homepage.php'" type="button">Homepage</button>
        </form>
    </div>
</body>

</html>