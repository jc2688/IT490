<?php

function getLeaderboard() {
  
    $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
  
    $query = "
        SELECT Profiles.RateScore, Profiles.AccountID, Accounts.Username
        FROM Profiles
        CROSS JOIN Accounts
        WHERE Accounts.AccountID = Profiles.AccountID
        ORDER BY RateScore DESC
        LIMIT 0, 10;
    ";
  
    $result = $conn->query($query);
  
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
  
    $leaderboard = array();
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
  
    $conn->close();
    
    return $leaderboard;
}
$leaderboardData = getLeaderboard();
echo "<pre>";
print_r($leaderboardData);
echo "</pre>";
?>
