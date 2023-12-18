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
        return getPopularRecommendations($apiKey);
    }

    // Get recommendation based on the most recently watched movie
    $recommendation = getRecommendationByMovieId($movieId, $mediaType, $apiKey);

    return $recommendation;
}

function getPopularRecommendations() {
    global $apiKey;
    $apiEndpoint = 'https://api.themoviedb.org/3/movie/popular';
    $params = [
        'api_key' => $apiKey,
        'language' => 'en-US',
        'page' => 1,
    ];
    $url = $apiEndpoint . '?' . http_build_query($params);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    if ($result && isset($result['results'])) {
        $popularMovies = array_slice($result['results'], 0, 10);
        $formattedMovies = [];
        foreach ($popularMovies as $movie) {
            $formattedMovies[] = [
                'title' => $movie['title'],
                'poster_path' => 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'],
                'MovieID' => $movie['id'],
            ];
        }
        return $formattedMovies;
    } else {
        return ['error' => 'Unable to fetch popular movies.'];
    }
}

function getRecommendationByMovieId($movieId, $mediaType, $apiKey) {
	global $apiKey;
	
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
