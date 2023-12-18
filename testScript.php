#!/usr/bin/php
<?php

$apiKey = 'ab0f6b84bbcbbd4066f4fee3eaba248c';







function getAccountIdByUsername($username) {

    $conn = dbConnect();

    if ($conn->connect_error) {

        die("Connection failed: " . $conn->connect_error);

    }
    

    $username = $conn->real_escape_string($username);

     $sql = "SELECT AccountID FROM Accounts WHERE Username = '$username'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $accountId = $row['AccountID'];

        $conn->close();

        return $accountId;

    } else {

        $conn->close();

        return false; // Username not found

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







function getFavoriteMovieTitleByAccountID($accountID) {

    global $conn;



    $stmt = $conn->prepare("SELECT FavoriteMovie FROM Profiles WHERE AccountID = ?");

    $stmt->bind_param("i", $accountID);

    

    $stmt->execute();

    

    $stmt->bind_result($favoriteMovieTitle);

    

    // Fetch the result

    $stmt->fetch();

    

    $stmt->close();

    

    return $favoriteMovieTitle;

}



function getMovieIdByTitle($title) {

    global $apiKey;



    $title = urlencode($title);

    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=$title";



    // Make the API request

    $response = file_get_contents($url);

    $data = json_decode($response, true);



    // Check if there are results

    if (!empty($data['results'])) {

        // Assume you only want the first result

        $movieId = $data['results'][0]['id'];

        return $movieId;

    } else {

        return false; // Movie not found

    }

}



function getRecommendedMovies($movieId) {

    global $apiKey;



    $url = "https://api.themoviedb.org/3/movie/$movieId/recommendations?api_key=$apiKey";



    // Make the API request

    $response = file_get_contents($url);

    $data = json_decode($response, true);



    // Check if there are results

    if (!empty($data['results'])) {

        // You can customize the data retrieval as per your needs

        return $data['results'];

    } else {

        return false; // No recommendations found

    }

}



// Get the database connection

$conn = dbConnect();



// Assume you have a username

$username = "Billards27";



// Get the account ID by username

$accountID = getAccountIDByUsername($username);

echo $accountID;


if ($accountID) {

    // Get the user's favorite movie title based on the account ID

    $favoriteMovieTitle = getFavoriteMovieTitleByAccountID($accountID);


echo $favoriteMovieTitle;
    if ($favoriteMovieTitle) {

        // Get the movie ID

        $movieId = getMovieIdByTitle("Toy Story");
echo $movieId;


        if ($movieId) {

            // Get recommended movies based on the movie ID

            $recommendedMovies = getRecommendedMovies(10191);



            if ($recommendedMovies) {

                // Do something with the recommended movies data

                print_r($recommendedMovies);

            } else {

                echo "No recommendations found.";

            }

        } else {

            echo "Favorite movie not found.";

        }

    } else {

        echo "User's favorite movie not found.";

    }

} else {

    echo "Account ID not found for the given username.";

}



// Close the database connection

$conn->close();





$username = "Billards27";

$recommendation = getAccountIdByUsername($username);

print_r($recommendation);



?>
