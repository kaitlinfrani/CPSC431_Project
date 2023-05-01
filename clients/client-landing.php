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
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
        <button class="edit-profile" onclick="location.href='../clients/edit-profile/edit-profile.php'">Edit
            Profile</button>
    </header>
    <div class="sidebar">
        <a href="..//clients/pending/pending.php">Pending</a>
        <a href="../clients/accepted/accepted.php">Accepted</a>
        <a href="../clients/clients/rejected.php">Rejected</a>
    </div>
    <!-- Add more content here -->
</body>

</html>



</html>