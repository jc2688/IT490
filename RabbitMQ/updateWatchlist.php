<?php
require_once(__DIR__ . '/path.inc');
require_once(__DIR__ . '/get_host_info.inc');
require_once(__DIR__ . '/rabbitMQLib.inc');

if ($_POST){
    $request = array();
    $request['type'] = 'update_watchlist';
    $request['username'] = $_POST['username']; // Assuming you have a way to get the username.
    $request['movie_title'] = $_POST['movie_title']; // Assuming you have a way to get the movie title.

    $client = new rabbitMQClient("testRabbitMQ.ini", "watchlist");
    $response = $client->send_request($request);

    echo $response;
} else {
    $error = array();
    $error["message"] = "error";
    echo json_encode($error);
}
?>
