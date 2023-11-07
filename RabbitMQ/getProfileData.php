<?php
require_once(__DIR__ . '/server/path.inc');
require_once(__DIR__ . '/server/get_host_info.inc');
require_once(__DIR__ . '/server/rabbitMQLib.inc');

$request = array();
$request['type'] = "get_profile_data";

$client = new rabbitMQClient("testRabbitMQ.ini","database");

$response = $client->send_request($request);

if ($response && isset($response['data'])) {
    echo json_encode($response['data']);
} else {
    $error = array();
    $error["message"] = "error";
    echo json_encode($error);
}
?>