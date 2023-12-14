#!/usr/bin/php
<?php
// Include necessary files for RabbitMQ server
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request) {
    echo "Received request from client" . PHP_EOL;
    var_dump($request);

    $output = shell_exec('php beClient.php ' . escapeshellarg(json_encode($request)));
    
    $response = json_decode($output, true);
    
    return $response;
}

$beServer = new rabbitMQServer("testRabbitMQ.ini", "backend");
echo "BE Server is running" . PHP_EOL;
$beServer->process_requests('requestProcessor');
echo "BE Server is shutting down" . PHP_EOL;
?>
