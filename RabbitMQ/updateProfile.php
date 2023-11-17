<?php
require_once(__DIR__ . '/path.inc');
require_once(__DIR__ . '/get_host_info.inc');
require_once(__DIR__ . '/rabbitMQLib.inc');

if ($_POST) {
    $request = array();
    $request['type'] = "update_profile_data";
    $request['field'] = $_POST['field'];
    $request['value'] = $_POST['value'];

    $client = new rabbitMQClient("testRabbitMQ.ini", "database");

    $response = $client->send_request($request);

    if ($response && $response['returnCode'] === "1") {
        echo json_encode(['message' => 'success']);
    } else {
        echo json_encode(['message' => 'error']);
    }
} else {
    $error = array();
    $error["message"] = "error";
    echo json_encode($error);
}
?>