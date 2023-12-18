<?php

$apiKey = 'ab0f6b84bbcbbd4066f4fee3eaba248c';


function getPopularRecommendations() {
    global $apiKey; // Use the global API key variable
    
    // Make a request to TMDB API for popular recommendations
    $url = "https://api.themoviedb.org/3/trending/all/day?api_key={$apiKey}";

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and get the response
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if there are results
    if (isset($data['results']) && count($data['results']) > 0) {
        // Extract information from the first item
        $result = $data['results'][0];

        // Return relevant information
        return [
            'title' => $result['title'] ?? $result['name'],
            'media_type' => $result['media_type'],
            'poster_path' => $result['poster_path'] ?? '/default_poster.jpg', // Use a default poster path if not available
        ];
    } else {
        // If no results are found, return a default result
        return [
            'title' => 'No Recommendations Found',
            'media_type' => 'movie', // or 'tv'
            'poster_path' => '/default_poster.jpg',
        ];
    }
}

// Function to get recommendations based on a specific movie ID and media type from the TMDB API
function getRecommendationByMovieId($movieId, $mediaType) {
    global $apiKey; // Use the global API key variable
    
    // Make a request to TMDB API for movie recommendations
    $url = "https://api.themoviedb.org/3/{$mediaType}/{$movieId}/recommendations?api_key={$apiKey}&language=en-US&page=1";

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and get the response
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if there are recommendations
    if (isset($data['results']) && count($data['results']) > 0) {
        // Extract information from the first recommended item
        $recommendation = $data['results'][0];

        // Return relevant information
        return [
            'title' => $recommendation['title'] ?? $recommendation['name'],
            'media_type' => $mediaType,
            'poster_path' => $recommendation['poster_path'] ?? '/default_poster.jpg', // Use a default poster path if not available
        ];
    } else {
        // If no recommendations are found, return a default result
        return [
            'title' => 'No Recommendations Found',
            'media_type' => $mediaType,
            'poster_path' => '/default_poster.jpg',
        ];
    }
}

// Function to get recent watched recommendations
function getRecentWatchedRecommendations($username) {
    global $apiKey; // Use the global API key variable
    // Get the account ID
    $accountId = getAccountIDByUsername($username);

    // If account ID is not found or there is an issue, return popular recommendations
    if (!$accountId) {
        return getPopularRecommendations();
    }

    // Connect to the database
    $conn = dbConnect();

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
        return getPopularRecommendations();
    }

    // Get recommendation based on the most recently watched movie
    $recommendation = getRecommendationByMovieId($movieId, $mediaType);

    return $recommendation;
}
function getAccountIdByUsername($username) {
    // This will connect to our databse using our credentials. If there is an error, a message is displayed 
    $conn =  dbConnect();
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
$result = getRecentWatchedRecommendations($username);
print_r($result);


?>
