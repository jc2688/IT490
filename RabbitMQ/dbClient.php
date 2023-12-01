<?php
// Include necessary files for RabbitMQ connection
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Check if there is a POST request
if ($_POST) {
    // Initialize an array to store the request data and set the 'type' of request based on POST data
    $request = array();
    $request['type'] = $_POST["type"];
    
    // Switch case to handle different request types
    switch ($request['type']) {
        // Handle login request
        case "login":
            $request['username'] = $_POST['username'];
            $request['password'] = $_POST['password'];
            break;

        // Handle user registration request
        case "register":
            $request['firstname'] = $_POST['firstname'];
            $request['lastname'] = $_POST['lastname'];
            $request['username'] = $_POST['username'];
            $request['email'] = $_POST['email'];
            $request['address'] = $_POST['address'];
            $request['city'] = $_POST['city'];
            $request['country'] = $_POST['country'];
            $request['zipcode'] = $_POST['zipcode'];
            $request['password'] = $_POST['password'];
            break;

        // Handle requests to get user profile, watch list, or watched list
        case "getUserProfile":
        case "getWatchList":
        case "getWatchedList":
            $request['username'] = $_POST['username'];
            break;
        
        // Handle user profile updates and preferences validation
        case "updateUserProfile":
        case "updateProfile":
        case "updatePreferences":
        case "validateUserPreferences":
            $request['username'] = $_POST['username'];
            $request['favActor'] = $_POST['favActor'];
            $request['favGenre'] = $_POST['favGenre'];
            $request['favDirector'] = $_POST['favDirector'];
            $request['favMovie'] = $_POST['favMovie'];
            $request['biography'] = $_POST['biography'];
            break;

        // Handle adding movies to watch list or watched list
        case "addToWatchList":
        case "addToWatchedList":
            $request['username'] = $_POST['username'];
            $request['movieTitle'] = $_POST['movieTitle'];
            $request['posterURL'] = $_POST['posterURL'];
            $request['year'] = $_POST['year'];
            break;

        // Handle search for movie reviews
        case "searchMovieReviews":
            $request['movieTitle'] = $_POST['movieTitle'];
            break;

        // Handle insertion of a new movie review
        case "insertReview":
            $request['accountId'] = $_POST['accountId'];
            $request['movieTitle'] = $_POST['movieTitle'];
            $request['rating'] = $_POST['rating'];
            $request['review'] = $_POST['review'];
            break;

        // Handle deletion of a movie from the watch list
        case "deleteFromWatchList":
            $request['watchListID'] = $_POST['watchListID'];
            break;

        // Handle request for getting the leaderboard
        case "getLeaderboard":
            break;

        // Default case for invalid request types
        default:
            echo json_encode(["error" => "Invalid request type"]);
            exit;
    }

    // Create a new RabbitMQ client instance, send the request, and output the response
    $client = new rabbitMQClient("testRabbitMQ.ini", "database");
    $response = $client->send_request($request);
    echo $response;

} else {
    // Output an error message if no POST data is received
    echo json_encode(["error" => "No POST data received"]);
}
?>
