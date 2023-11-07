<?php
require_once(__DIR__ . '/server/path.inc');
require_once(__DIR__ . '/server/get_host_info.inc');
require_once(__DIR__ . '/server/rabbitMQLib.inc');

if ($_GET && isset($_GET['movieTitle'])) {
    $request = array();
    $request['type'] = 'get_reviews';
    $request['movieTitle'] = $_GET['movieTitle'];

    $client = new rabbitMQClient("testRabbitMQ.ini", "reviews");
    $response = $client->send_request($request);

    if ($response && isset($response['data'])) {
        echo json_encode($response['data']);
    } else {
        $error = array();
        $error["message"] = "error";
        echo json_encode($error);
    }
} else {
    $error = array();
    $error["message"] = "error";
    echo json_encode($error);
}
?>