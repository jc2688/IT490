<?php

$apiKey = 'ab0f6b84bbcbbd4066f4fee3eaba248c';


// Function to get popular recommendations from the TMDB API
function getPopularRecommendations() {
    global $apiKey; // Use the global API key variable
    
    // Make a request to TMDB API for popular recommendations
    $url = "https://api.themoviedb.org/3/trending/all/day?api_key={$apiKey}";

    // Use file_get_contents to make the request
    $response = file_get_contents($url);

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

    // Use file_get_contents to make the request
    $response = file_get_contents($url);

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


$username = 'Billards27';
$result = getRecentWatchedRecommendations($username);
print_r($result);


?>
