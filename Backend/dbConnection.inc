<?php
function dbConnect() {
	//attempts connection to the database
	try{
	$login = new mysqli("localhost","root","Qg5OKQ","SceneSync");
	}
	//if($login -> connect_error){
	catch(Exception $e){
	//if error is detected attempts to reconnect
		//notifying the users of shift
		echo "Error connecting to primary node: DB Server 1 \n";
		echo "Reattempting at secondary node: DB Server 2 \n";
		$login = new mysqli("10.244.1.5","fourth","four4four","SceneSync");
		//if failure again ends program on cluster failure
		if ($login -> connect_errno != 0) {
			echo "Error connecting to secondary node: DB Server 2 \n" . $this -> login -> connect_error . PHP_EOL;
			echo "DB Cluster Failure: Alert System Administrators \n" . $this -> login -> connect_error . PHP_EOL;
			exit(1);
		}
	}
	echo "correctly connected to database" . PHP_EOL;
	return $login;
}
?>
