#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request) {
    echo "Received request: " . json_encode($request) . PHP_EOL;
    
    // Execute the client script and capture the output
    $output = shell_exec('php testMiddlewareClient.php');
    echo "Raw output from client script: " . $output . PHP_EOL;

    // Parse only the JSON part of the output
    $response = json_decode(trim($output));
    
    return $response; // Return the JSON decoded response
}

try {
    $server = new rabbitMQServer("testRabbitMQ.ini", "backend");
    echo "Server is running" . PHP_EOL;
    $server->process_requests('requestProcessor');
} catch (Exception $e) {
    echo "Error in server: " . $e->getMessage() . PHP_EOL;
}

echo "Server is shutting down" . PHP_EOL;
?>
