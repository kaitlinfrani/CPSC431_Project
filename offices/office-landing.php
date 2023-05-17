<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../../homepage/homepage.php');
    exit();
}

require_once 'db_connection.php';

// Prepare the SQL statement
$sql = "SELECT * FROM providers WHERE medical_office_id = " . $_SESSION['medical_office_id'];
$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
        <div class="header-buttons">
            <a
                href="../offices/add-provider/add-provider.php?medical_office_id=<?php echo $_SESSION['medical_office_id']; ?>">
                <button class="add-provider">Add Provider</button>
            </a>
            <form action="logout.php" method="POST">
                <input type="submit" value="Logout" />
            </form>
        </div>

        <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert success-message">' . $_SESSION['success_message'] . '</div>';
                
                // Unset the success message so it doesn't keep showing up on refresh
                unset($_SESSION['success_message']);
            }
            ?>
    </header>

    <!-- Filter -->
    <div class="sidebar">
        <div class="sidebar-content">
            <a href="../offices/appointment-action/pending.php">Pending</a>
            <a href="../offices/appointment-action/view_accept.php">Approved</a>
            <a href="../offices/appointment-action/view_reject.php">Rejected</a>
        </div>
    </div>

    <!-- Display the providers in a list -->
    <div class="main-content">
        <div class="filter-search-bar">
            <div class="search-container">
                <input type="text" id="searchBar" name="searchBar"
                    placeholder="Search and apply filters by pressing Enter...">
                <button class="clear-all-filters" onclick="clearAllFilters()">Clear All Filters</button>
            </div>
            <div class="applied-filters"></div>
        </div>
        <div class="providers-header">
            <h2>Providers</h2>
        </div>
        <div class="providers-container">
            <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            // Output data of each row
                            echo "<div class='provider single-provider'>";
                            echo "<ul>";

                            echo "<li class='provider-name'><i class='fas fa-user'></i> Name: " . $row["first_name"] . " " . $row["last_name"] . "</li>";
                            echo "<li class='provider-occupation'><i class='fas fa-briefcase'></i> Occupation: " . $row["occupation"] . "</li>";
                            echo "<li class='provider-zipcode'><i class='fas fa-map-marker-alt'></i> Zipcode: " . $row["zipcode"] . "</li>";
                            echo "<li class='provider-food_preference'><i class='fas fa-utensils'></i>Food Preference: " . $row["food_preference"]. "</li>";

                            $provider_id = $row["id"];
                            $availabilities_sql = "SELECT * FROM availabilities WHERE provider_id = $provider_id";
                            $availabilities_result = $conn->query($availabilities_sql);
            
                            echo "<li class='provider-availability'><i class='fas fa-clock'></i> Availability: ";
                            if ($availabilities_result->num_rows > 0) {
                                echo "<ul>";
                                while ($availability = $availabilities_result->fetch_assoc()) {
                                    $start_time = date("g:i A", strtotime($availability["start_time"]));
                                    $end_time = date("g:i A", strtotime($availability["end_time"]));
                                    echo "<li>" . $availability["day_of_week"] . ": " . $start_time . " - " . $end_time . "</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo " No availability.";
                            }
                            echo "</ul>";
                            echo "<a href='../offices/add-provider/edit-provider.php?id=" . $row['id'] . "'><button class='edit-btn'>Edit Provider</button></a>";
                            echo "</div>";
                        
                        }
                    } else {
                        echo "No providers found.";
                    }
                ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="alert.js"></script>
    <script src="filter.js"></script>
</body>

</html>