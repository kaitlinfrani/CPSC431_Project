<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login_style.css" />
    <script src="login.js"></script>
</head>

<body>
    <div class="login-container">
        <h1>Provider Login</h1>
        <form method="POST" action="login-check-provider.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <input type="submit" name="submit" value="Log In" />
        </form>
        <?php
          session_start();
          if(isset($_SESSION['error_message'])) {
            echo '<div class="error-message">'.$_SESSION['error_message'].'</div>';
            unset($_SESSION['error_message']); // clear the message for next requests
          }
        ?>
        <div class="button-container">
            <button onclick="location.href='/project/signup/signup.php'" type="button">Sign Up</button>
            <button onclick="location.href='/project/homepage/homepage.php'" type="button">Homepage</button>
        </div>
    </div>
</body>

</html>