<?php
require_once(__DIR__ . '/server/path.inc');
require_once(__DIR__ . '/server/get_host_info.inc');
require_once(__DIR__ . '/server/rabbitMQLib.inc');

// Assuming you have a way to get the username, replace 'USERNAME_HERE' with the actual username.
$username = 'USERNAME_HERE';

$request = array();
$request['type'] = 'populate_watchlist';
$request['username'] = $username;

$client = new rabbitMQClient("testRabbitMQ.ini", "watchlist");
$response = $client->send_request($request);

echo $response;
?>
