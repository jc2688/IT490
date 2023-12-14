<?php
function dbConnect($type) {
	if ($type == "BackEndAdmin") {
		$login = new mysqli("localhost", "BackEndAdmin", "Qg5OKQ!?\$Q", "SceneSync");
		
		if ($login->connect_errno != 0) {
			echo "Error connecting to database at 10.244.1.2 " . $login->connect_error . PHP_EOL;
			$login = new mysqli("10.244.1.5", "BackEndAdmin", "Qg5OKQ!?\$Q", "SceneSync");
			if ($login->connect_errno != 0) {
				echo "Error connecting to database at 10.244.1.5 \n " . $login->connect_error . PHP_EOL;
				echo "Database Cluster Down \n " . $login->connect_error . PHP_EOL;
				exit(1);
			}
		}
		echo "Correctly connected to database" . PHP_EOL;
		return $login;
	}
	elseif ($type == "DeleteAccessOnly") {
		$login = new mysqli("localhost", "DeleteAccessOnly", "cl3v3rm@ndoesTh1ngs!", "SceneSync");
		
		if ($login->connect_errno != 0) {
			echo "Error connecting to database at 10.244.1.5 " . $login->connect_error . PHP_EOL;
			$login = new mysqli("10.244.1.5", "DeleteAccessOnly", "cl3v3rm@ndoesTh1ngs!", "SceneSync");
			if ($login->connect_errno != 0) {
				echo "Error connecting to database at 10.244.1.5 \n " . $login->connect_error . PHP_EOL;
				echo "Database Cluster Down \n " . $login->connect_error . PHP_EOL;
				exit(1);
			}
		}
		echo "Correctly connected to database" . PHP_EOL;
		return $login;
	}	
}

function getWatchListData($username) {
    $accountId = getAccountIdByUsername($username);
    if (!$accountId) {
        return array('error' => 'Username not found.');
    }
	$conn = new mysqli("localhost","BackEndAdmin", "Qg5OKQ!?\$Q", "SceneSync");
    $watchListData = array();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $sql = "SELECT ListID, MovieID, Movie, PosterURL, Year, MediaType FROM WatchList WHERE AccountID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $watchListData[] = array(
                'MovieID' => $row['MovieID'],
                'MovieTitle' => $row['Movie'],
                'PosterURL' => $row['PosterURL'],
                'Year' => $row['Year'],
            );
        }
        $stmt->close();
        $conn->close();
    }
    return $watchListData;
}
function getAccountIdByUsername($username) {
    $conn = $login = new mysqli("localhost", "BackEndAdmin", "Qg5OKQ!?\$Q", "SceneSync");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $username = $conn->real_escape_string($username);
     $sql = "SELECT AccountID FROM Accounts WHERE Username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $accountId = $row['AccountID'];
        $conn->close();
        return $accountId;
    } else {
        $conn->close();
        return false; // Username not found
    }
}
/*
$username = 'Billards27';
$password = 'Testing123!';

$login = validateLogin($username, $password);

var_dump($login);
*/

$type = "BackEndAdmin";
$username = 'Billards27'; // Replace with an actual username
//$password = 'Testing123!'; // Replace with an actual password

$result = getWatchListData($username);

// Display the result
var_dump($result);


?>
