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

function getRecentWatchedRecommendations($username) {

    // The accountID is retreived with the $username 

    $accountId = getAccountIdByUsername($username);

    // If no one is found it will say no username found 

    if (!$accountId) {

        return array('error' => 'Username not found.');

    }

    // Recommendations are found based on most recent watched 

    $mostRecentWatched = getMostRecentWatched($username);

    if (!$mostRecentWatched) {

        return getPopularRecommendations($apiKey);

    }

    // Ten or less recommended movie titles are returned 

    $recentTitle = $mostRecentWatched['MovieTitle'];

    $recentPosterPath = $mostRecentWatched['PosterURL'];

    $recommendations = searchMoviesByTitle($recentTitle);

    $recommendations += getPopularRecommendations(10 - count($recommendations));

    return $recommendations;

}



//isfar

//for recomendation function, if no recent watched, shows ppopular movies

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

        return $popularMovies;

    } else {

        return ['error' => 'Unable to fetch popular movies.'];

    }

}





//isfar

//for recomendation function, if no recent watched, shows ppopular movies

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



//isfar

// This function takes the parameter $title

function searchMoviesByTitle($title) {

    // The key used for the movie database 

    global $apiKey;

    // The api url is made with its parameters and what to search for 

    $url = "https://api.themoviedb.org/3/search/movie";

    $params = [

        'api_key' => $apiKey,

        'query' => urlencode($title),

    ];

    // Curl is set

    $url .= '?' . http_build_query($params);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);

    // A result is put into $data

    $data = json_decode($response, true);

    // The if checks if there are results in $data. 

    if (isset($data['results'])) {

        $movies = [];

        // The title, poster, and release year are returned 

        foreach ($data['results'] as $result) {

            $movies[] = [

                'title' => $result['title'],

                'poster_path' => $result['poster_path'],

                'release_year' => $result['release_date'],

                'MovieID' => $result['id'],

                'MediaType' => $mediaType,

            ];

        }

        return $movies;

    // Otherwise it will print nothing if no results were found

    } else {

        return [];

    }

}



//isfar

// This function takes the parameter $username. 

function getMostRecentWatched($username) {

    global $apiKey;

    // Gets the account ID associated with the username

    $accountId = getAccountIdByUsername($username);

    // If the username is not found an error message is displayed 

    if (!$accountId) {

        return array('error' => 'Username not found.');

    }

    // Connects to our database using our credentials. 

    $conn = dbConnect();

    // An error message is displayed if the connection gets displayed. 

    if ($conn->connect_error) {

        die("Connection failed: " . $conn->connect_error);

    }

    // Gets Movie, PosterURL, and year are taken from watched list associated with the particular account ID

    $sql = "SELECT Movie, PosterURL, Year

            FROM WatchedList

            WHERE AccountID = ?

            ORDER BY TimeCreated DESC

            LIMIT 1";

    // If something is found it will print out the relevant details otherwise most recent watched is set to null. 

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $accountId);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        $mostRecentWatched = array(

            'MovieTitle' => $row['Movie'],

            'PosterURL' => $row['PosterURL'],

            'Year' => $row['Year'],

        );

    } else {

        $mostRecentWatched = null;

    }

    // Connection is closed 

    $stmt->close();

    $conn->close();

    return $mostRecentWatched;

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

/*

$username = 'Billards27';

$result = getMoviesByMovieAndGenre($username);

print_r($result);*/

?>
