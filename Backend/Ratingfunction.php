<?php

function getLeaderboard() {
    // Database connection details
    $servername = "your_db_server";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "your_db_name";

    
    $conn = new mysqli($servername, $username, $password, $dbname);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to retrieve leaderboard data
    $query = "
        SELECT Profiles.RateScore, Profiles.AccountID, Accounts.Username
        FROM Profiles
        CROSS JOIN Accounts
        WHERE Accounts.AccountID = Profiles.AccountID
        ORDER BY RateScore DESC
        LIMIT 0, 10;
    ";

    // Execute the query
    $result = $conn->query($query);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Fetch the data
    $leaderboard = array();
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }

    // Close the database connection
    $conn->close();

    return $leaderboard;
}


$leaderboardData = getLeaderboard();

// Display the leaderboard data
echo "<pre>";
print_r($leaderboardData);
echo "</pre>";
?>
