<?php
require_once(__DIR__ . '/server/path.inc');
require_once(__DIR__ . '/server/get_host_info.inc');
require_once(__DIR__ . '/server/rabbitMQLib.inc');

if ($_GET){
  $request = array();
  $request['type'] = 'get_movie_info'; // Define the type of request
  $request['title'] = $_GET['title']; // Get the movie title from the URL parameter

  $client = new rabbitMQClient("testRabbitMQ.ini", "movie");
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
