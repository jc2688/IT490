#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request) {
    echo "Received request: " . json_encode($request) . PHP_EOL;

    // Prepare a simple response
    $response = [
        'status' => 'received',
        'content' => 'Message received successfully'
    ];

    return $response;
}

$server = new rabbitMQServer("testRabbitMQ.ini", "database");
echo "Test Server is running" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "Test Server is shutting down" . PHP_EOL;
?>
