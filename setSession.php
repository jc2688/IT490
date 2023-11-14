<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = $_POST['type'];

    if ($type === 'login') {
        // Assuming a successful login, set session variables
        $username = $_POST['username'];
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        echo json_encode(['returnCode' => '1', 'message' => 'Login successful']);
        exit();
    } elseif ($type === 'logout') {
        // Logout logic
        session_destroy();
        echo json_encode(['returnCode' => '1', 'message' => 'Logout successful']);
        exit();
    }
}

// If none of the conditions were met, return an error code or handle the request accordingly
echo json_encode(['returnCode' => '0', 'message' => 'Invalid request']);
exit();
?>