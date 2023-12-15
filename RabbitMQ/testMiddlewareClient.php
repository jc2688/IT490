#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

try {
    $client = new rabbitMQClient("testRabbitMQ.ini", "database");

    // Prepare a request
    $request = ['type' => 'test', 'content' => 'Hello from Test Client'];
    
    // Send request and receive response
    $response = $client->send_request($request);

    // Output only the JSON encoded response
    echo json_encode($response);
    
} catch (Exception $e) {
    // In case of an error, output a JSON encoded error message
    echo json_encode(['error' => $e->getMessage()]);
}

?>
