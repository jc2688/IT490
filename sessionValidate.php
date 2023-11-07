<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = $_POST['type'];

    if ($type === 'validate_session') {
        // Check if the session is valid (e.g., compare session ID with a stored value)
        // You might also want to perform additional checks like validating the user's credentials

        $returnCode = ($_SESSION['logged_in'] === true) ? '1' : '0';

        echo json_encode(['returnCode' => $returnCode, 'username' => $_SESSION['username']]);
        exit();
    } elseif ($type === 'logout') {
        // Perform logout logic, for example:
        session_destroy();
        echo '1'; // Indicate successful logout
        exit();
    }
}

// If none of the conditions were met, return an error code or handle the request accordingly
echo json_encode(['returnCode' => '-1']);
exit();
?>
