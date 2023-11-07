<?php
require_once(__DIR__ . '/server/path.inc');
require_once(__DIR__ . '/server/get_host_info.inc');
require_once(__DIR__ . '/server/rabbitMQLib.inc');

if ($_POST){
  $request = array();
  $request['type'] = 'add_to_watchlist'; // Define the type of request
  $request['title'] = $_POST['title']; // Get the movie title from the POST request

  $client = new rabbitMQClient("testRabbitMQ.ini", "watchlist");
  $response = $client -> send_request($request);

  if ($response) {
    echo json_encode($response);
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
