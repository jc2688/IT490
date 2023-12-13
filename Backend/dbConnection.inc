<?php
function dbConnect($type) {
	if($type = "BackendAdmin") {
		$login = new mysqli("10.244.1.2","BackEndAdmin","Qg5OKQ!?\$Q","SceneSync");
		
		if ($login -> connect_errno != 0) {
			echo "Error connecting to database at 10.244.1.2 " . $this -> login -> connect_error . PHP_EOL;
			$login = new mysqli("10.244.1.2","BackEndAdmin","Qg5OKQ!?\$Q","SceneSync");
			if ($login -> connect_errno != 0) {
				echo "Error connecting to database at 10.244.1.5 \n " . $this -> login -> connect_error . PHP_EOL;
				echo "Database Cluster Down \n " . $this -> login -> connect_error . PHP_EOL;
				exit(1)
			}
		}
		echo "correctly connected to database" . PHP_EOL;
		return $login;
	}
	elseif ($type = "DeleteAccesOnly") {
		$login = new mysqli("10.244.1.2","BackEndAdmin","Qg5OKQ!?\$Q","SceneSync");
		
		if ($login -> connect_errno != 0) {
			echo "Error connecting to database at 10.244.1.2 " . $this -> login -> connect_error . PHP_EOL;
			$login = new mysqli("10.244.1.2","BackEndAdmin","Qg5OKQ!?\$Q","SceneSync");
			if ($login -> connect_errno != 0) {
				echo "Error connecting to database at 10.244.1.5 \n " . $this -> login -> connect_error . PHP_EOL;
				echo "Database Cluster Down \n " . $this -> login -> connect_error . PHP_EOL;
				exit(1);
			}
		}
		echo "correctly connected to database" . PHP_EOL;
		return $login;
	
}
?>
