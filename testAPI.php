<?php
$apiKey = 'ab0f6b84bbcbbd4066f4fee3eaba248c';

function getMoviesByMovieAndGenre($username) {
    // Global variable for TMDB API key
    global $apiKey;
    // Connect to the database
    $conn = dbConnect();
    // Return an empty array if there is a connection error
    if ($conn->connect_error) {
        return [];
    }
    // Get the account id with the username
    $accountId = getAccountIDByUsername($username);
    // Fetch favorite genre and movie with the account id
    $sql = "SELECT FavoriteGenres FROM Profiles WHERE AccountID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $stmt->bind_result($favoriteGenre);
    $stmt->fetch();
    $stmt->close();
    // Return an empty array if the user has no favorite genre
    if (!$favoriteGenre) {
        $conn->close();
        return [];
    }
    // Make API request to TMDB for movies by genre
    $tmdbApiUrl = 'https://api.themoviedb.org/3/';
    $tmdbDiscoverEndpoint = 'discover/movie';
    $apiEndpoint = $tmdbApiUrl . $tmdbDiscoverEndpoint . '?api_key=' . $apiKey . '&with_genres=' . urlencode($favoriteGenre);
    // Use file_get_contents to make the request
    $apiResponse = file_get_contents($apiEndpoint);
    // Return an empty array if the API request has an error
    if ($apiResponse === false) {
        $conn->close();
        return [];
    }
    // Decode the JSON response
    $moviesData = json_decode($apiResponse, true);
    // Map the results to the desired format
    $recommendedMovies = array_map(function ($movie) {
        // Determine media type (movie)
        $mediaType = 'movie';
        return [
            'title' => $movie['title'],
            'overview' => $movie['overview'],
            'release_date' => $movie['release_date'],
            'poster_path' => isset($movie['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : 'Not available',
            'MovieID' => $movie['id'],
            'MediaType' => $mediaType,
        ];
    }, $moviesData['results']);
    // Return the first 10 recommended movies
    $result = array_slice($recommendedMovies, 0, 10);
    // Close the database connection
    $conn->close();
    return $result;
}
function getAccountIdByUsername($username) {
    // This will connect to our databse using our credentials. If there is an error, a message is displayed 
    $conn = dbConnect();
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
function dbConnect() {
	//attempts connection to the database
	try{
	$login = new mysqli("localhost","root","Qg5OKQ","SceneSync");
	}
	//if($login -> connect_error){
	catch(Exception $e){
	//if error is detected attempts to reconnect
		//notifying the users of shift
		echo "Error connecting to primary node: DB Server 1 \n";
		echo "Reattempting at secondary node: DB Server 2 \n";
		$login = new mysqli("10.244.1.5","fourth","four4four","SceneSync");
		//if failure again ends program on cluster failure
		if ($login -> connect_errno != 0) {
			echo "Error connecting to secondary node: DB Server 2 \n" . $this -> login -> connect_error . PHP_EOL;
			echo "DB Cluster Failure: Alert System Administrators \n" . $this -> login -> connect_error . PHP_EOL;
			exit(1);
		}
	}
	echo "correctly connected to database" . PHP_EOL;
	return $login;
}
$username = 'Billards27';
$result = $getMoviesByMovieAndGenre($username);
print_r($result);

?>
