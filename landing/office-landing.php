<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
    <!-- Add more content here -->
</body>

</html>