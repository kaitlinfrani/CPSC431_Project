<?php
session_start();
// to check array names: print_r($_SESSION);
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../homepage/homepage.php');
    exit();
    
}
require_once 'db_connection.php';


// Get the office ID of the currently logged-in user
$office_id = $_SESSION['office_id'];

// Prepare the SQL statement to retrieve providers for the current office
/*$sql = "SELECT medical_office_id FROM providers WHERE office_id = $office_id";
$result = $conn->query($sql);
*/
// Prepare the SQL statement
$sql = "SELECT * FROM providers";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="style2.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>
<body>
    <header>
        <!--Keep it as 'name' because when I printed out the $_SESSION array,
        it showed name not office_name bc it's set as name in signup.php-->
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
        <button class="homepage" onclick="location.href='../homepage/homepage.php'" style="float:left;margin-top:20px;margin-left:20px;">Log Out</button>
        <a href="../offices/add-provider/add-provider.php?medical_office_id=<?php echo $office_id; ?>"><button class="add-provider">Add Provider</button></a>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert success-message">' . $_SESSION['success_message'] . '</div>';

            // Unset the success message so it doesn't keep showing up on refresh
            unset($_SESSION['success_message']);
        }
        ?>
    </header>
    <main>
        <div class="providers-menu">
            <a href="../offices/appointment-action/pending.php"><button class="menu-btn">Pending</button></a>
            <a href="../offices/appointment-action/view_accept.php"><button class="menu-btn">Approved</button></a>
            <a href="../offices/appointment-action/view_reject.php"><button class="menu-btn">Rejected</button></a>
        </div>

        <!-- Occupation Filter -->

        <div class="providers-container">
            <?php
            
            if ($result->num_rows > 0) {
                // Output data of each row
                $count = 0;
                while($row = $result->fetch_assoc()) {
                    if ($count % 3 == 0) {
                        echo '<div class="row">';
                    }
                    echo "<div class='provider'>";
                    echo "<ul>";
                    echo "<li><i class='fas fa-user'></i>Name: " . $row["first_name"]. " " . $row["last_name"] . "</li>";
                    echo "<li><i class='fas fa-briefcase'></i>Occupation: " . $row["occupation"]. "</li>";
                    echo "<li><i class='fas fa-map-marker-alt'></i>Zipcode: " . $row["zipcode"]. "</li>";
                    echo "<li><i class='fas fa-utensils'></i>Food Preference: " . $row["food_preference"]. "</li>";
                    echo "<a href='../offices/add-provider/edit-provider.php?id=" . $row['id'] . "'><button class='edit-provider'>Edit Provider</button></a>";
                    echo "</ul>";
                    echo "</div>";
                    
                    // Close the row every 3 providers
                    if ($count % 3 == 2) {
                        echo '</div>';
                    }
                    $count++;
                }
                
                // Close the last row if it's not complete
                if ($count % 3 != 0) {
                    echo '</div>';
                }
            }
            else {
                echo "No providers found.";
            }
            ?>
        </div>

    </main>
    <script src="filter.js"></script>
</body>
</html>
