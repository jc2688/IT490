<?php

$apiKey = 'ab0f6b84bbcbbd4066f4fee3eaba248c';



// Assuming you have already defined dbConnect() and getAccountIDByUsername() functions

function getRecentWatchedRecommendations($username) {
	global $apiKey;
    // Get the account ID
    $accountId = getAccountIDByUsername($username);

    // If account ID is not found or there is an issue, return popular recommendations
    if (!$accountId) {
        return getPopularRecommendations($apiKey);
    }

    // Connect to the database
    $conn =     $conn = new mysqli('10.244.1.2', 'BackEndAdmin', 'Qg5OKQ!?$Q', 'SceneSync');

    // Select the most recent watched movie
    $query = "SELECT movie_id, media_type FROM watched_list WHERE account_id = ? ORDER BY timestamp DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $stmt->bind_result($movieId, $mediaType);

    // Fetch the result
    $stmt->fetch();

    // Close the database connection
    $stmt->close();
    $conn->close();

    // If there is no recent watched movie, return popular recommendations
    if (!$movieId) {
        return getPopularRecommendations($apiKey);
    }

    // Get recommendation based on the most recently watched movie
    $recommendation = getRecommendationByMovieId($movieId, $mediaType, $apiKey);

    return $recommendation;
}

function getPopularRecommendations($apiKey) {
    // You can implement the logic to get popular recommendations from the API here
    // Use the $apiKey to make requests to TMDB API
    // Return an array with the movie or TV show title, media type, and poster path
    // Example:
    return [
        'title' => 'Popular Movie or TV Show',
        'media_type' => 'movie', // or 'tv'
        'poster_path' => '/example_poster_path.jpg',
    ];
}

function getRecommendationByMovieId($movieId, $mediaType, $apiKey) {
    // You can implement the logic to get recommendations based on a specific movie from the API here
    // Use the $apiKey, $movieId, and $mediaType to make requests to TMDB API
    // Return an array with the movie or TV show title, media type, and poster path
    // Example:
    return [
        'title' => 'Recommended Movie or TV Show',
        'media_type' => 'movie', // or 'tv'
        'poster_path' => '/recommended_poster_path.jpg',
    ];
}

// Example usage:
$username = 'example_user';
$apiKey = 'your_tmdb_api_key';
$result = getRecentWatchedRecommendations($username, $apiKey);

// Output the result
print_r($result);

function getAccountIdByUsername($username) {
    // This will connect to our databse using our credentials. If there is an error, a message is displayed 
    $conn =  $conn = new mysqli('10.244.1.2', 'BackEndAdmin', 'Qg5OKQ!?$Q', 'SceneSync');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $username = $conn->real_escape_string($username);
    // Account ID is associated with the username 
    $sql = "SELECT AccountID FROM Accounts WHERE Username = '$username'";
    $result = $conn->query($sql);
    // If the user name was found it gets the account ID 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $accountId = $row['AccountID'];
        $conn->close();
        return $accountId;
    // If the username was not found the database connection is closed and returns false
    } else {
        $conn->close();
        return false;
    }
}





$username = 'Billards27';
//$password = 'sdf';
//$email = 'sdf';
$result = getRecentWatchedRecommendations($username);
print_r($result);


?>
