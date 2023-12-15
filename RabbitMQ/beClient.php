#!/usr/bin/php
<?php
// Include necessary files for RabbitMQ server
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

if ($argc < 2) {
    echo "Error: No argument provided." . PHP_EOL;
    exit(1);
}

$requestJson = $argv[1];
$request = json_decode($requestJson, true);

$dbClient = new rabbitMQClient("testRabbitMQ.ini", "database");

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
    case "deleteFromWatchedList":
    case "addToWatchedListAndRemoveFromWatchList":
        $response = $dbClient->send_request($request);
        break;

    default:
        echo "Unknown request type: " . $request['type'] . PHP_EOL;
        $response = ["error" => "Unknown request type"];
        break;
}

echo json_encode($response);
?>
