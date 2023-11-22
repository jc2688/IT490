<?php
session_start();

// Assuming you've received the successful login response and username
if ($_POST['type'] === 'login' && $_POST['successFromRabbitMQ'] === 'true') {
    $username = $_POST['username']; // Replace this with your received username

    // Set the session variable for the logged-in user
    $_SESSION['loggedInUser'] = $username;

    // Return a success message or any relevant response
    $response = [
        'status' => 'success',
        'message' => 'Session set for logged-in user: ' . $username
    ];
    echo json_encode($response);
    exit;
} else {
    // Return an error or handle the case where login was not successful
    $response = [
        'status' => 'error',
        'message' => 'Failed to set session for the user'
    ];
    echo json_encode($response);
    exit;
}
?>