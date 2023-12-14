#!/usr/bin/php
<?php
// Include required files for RabbitMQ connection
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Function to process incoming requests from the client
function requestProcessor($request) {
    echo "Received request from client" . PHP_EOL;
    var_dump($request);

    $dbServerClient = new rabbitMQClient("testRabbitMQ.ini", "database");

    switch ($request['type']) {
        case "login":
        case "register":
        case "resetPassword":
        case "validatePreferences":
        case "getUserProfile":
        case "updateProfile":
        case "getWatchList":
        case "getWatchedList":
        case "addToWatchList":
        case "addToWatchedList":
        case "searchMovieReviews":
        case "getLeaderboard":
        case "insertReview":
        case "deleteFromWatchList":
        case "addToWatchedListAndRemoveFromWatchList":
            $response = $dbServerClient->send_request($request);
            break;

        default:
            echo "Unknown request type: " . $request['type'] . PHP_EOL;
            $response = ["error" => "Unknown request type"];
            break;
    }
    return $response;
}

$beServer = new rabbitMQServer("testRabbitMQ.ini", "backend");

echo "BE Server is running" . PHP_EOL;
$beServer->process_requests('requestProcessor');
echo "BE Server is shutting down" . PHP_EOL;
?>
