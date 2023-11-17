<?php

$ratings = [
    'user1' => 100,
    'user2' => 85,
    'user3' => 120,
    
];

// Function to calculate the rating score based on interactions
function calculateRating($user, $interactionPoints) {
$mysqli = dbConnect();
    return isset($ratings[$user]) ? $ratings[$user] + $interactionPoints : $interactionPoints;
}

// Function to display the leaderboard
function displayLeaderboard($ratings) {
    // Sort the ratings in descending order
    arsort($ratings);

    // Display the leaderboard
    echo "Leaderboard:\n";
    foreach ($ratings as $user => $score) {
        echo "$user: $score points\n";
    }
}

$user1Interactions = 20;
$user2Interactions = 15;
$user3Interactions = 25;

$user1Rating = calculateRating('user1', $user1Interactions);
$user2Rating = calculateRating('user2', $user2Interactions);
$user3Rating = calculateRating('user3', $user3Interactions);

// Update the ratings array
$ratings['user1'] = $user1Rating;
$ratings['user2'] = $user2Rating;
$ratings['user3'] = $user3Rating;

// Display the leaderboard
displayLeaderboard($ratings);
?>
