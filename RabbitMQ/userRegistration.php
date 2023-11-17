<?php
require_once(__DIR__ . '/path.inc');
require_once(__DIR__ . '/get_host_info.inc');
require_once(__DIR__ . '/rabbitMQLib.inc');

if ($_POST){
  $request = array();
  $password = hash("sha256", $_POST["password"]);
  $request['type'] = $_POST["type"];
  $request['firstname'] = $_POST["firstname"];
  $request['lastname'] = $_POST["lastname"];
  $request['username'] = $_POST["username"];
  $request['email'] = $_POST["email"];
  $request['address'] = $_POST["address"];
  $request['city'] = $_POST["city"];
  $request['country'] = $_POST["country"];
  $request['zipcode'] = $_POST["zipcode"];
  $request['password'] = $password;

  $client = new rabbitMQClient("testRabbitMQ.ini","database");
  
  $response = $client -> send_request($request);
  echo $response;
} else {
	$error = array();
	$error["message"] = "error";
	echo json_encode($r);
}
?>