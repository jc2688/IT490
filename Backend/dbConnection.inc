<?php
function dbConnect($type) {
	if($type = "BackEndAdmin") {
		$login = new mysqli("10.244.1.2","BackEndAdmin","Qg5OKQ!?$Q","SceneSync");
		
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
	elseif ($type = "DeleteAccessOnly") {
		$login = new mysqli("10.244.1.5","DeleteAccessOnly","cl3v3rm@ndoesTh1ngs!","SceneSync");
		
		if ($login -> connect_errno != 0) {
			echo "Error connecting to database at 10.244.1.5 " . $this -> login -> connect_error . PHP_EOL;
			$login = new mysqli("10.244.1.5","DeleteAccessOnly","cl3v3rm@ndoesTh1ngs!","SceneSync");
			if ($login -> connect_errno != 0) {
				echo "Error connecting to database at 10.244.1.5 \n " . $this -> login -> connect_error . PHP_EOL;
				echo "Database Cluster Down \n " . $this -> login -> connect_error . PHP_EOL;
				exit(1);
			}
		}
		echo "correctly connected to database" . PHP_EOL;
		return $login;
	}	
}
?>
