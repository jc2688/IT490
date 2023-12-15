#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Sample message to send
$message = [
    'type' => 'test',
    'content' => 'Hello from Test Client'
];

// Create a new RabbitMQ client instance for the test middleware
$client = new rabbitMQClient("testRabbitMQ.ini", "backend");

// Send the message and print the response
$response = $client->send_request($message);
echo "Response from Middleware: " . json_encode($response) . "\n";
?>
