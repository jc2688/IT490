<?php
// Database connection
require_once('dbConnect.php');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate user data
    $requiredFields = ['FirstName', 'LastName', 'Username', 'Email', 'Address', 'City', 'Country', 'ZipCode', 'PasswordHash'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing or empty field: $field"]);
            exit;
        }
    }

    $firstName = $data['FirstName'];
    $lastName = $data['LastName'];
    $username = $data['Username'];
    $email = $data['Email'];
    $address = $data['Address'];
    $city = $data['City'];
    $country = $data['Country'];
    $zipCode = $data['ZipCode'];
    $passwordHash = password_hash($data['PasswordHash'], PASSWORD_BCRYPT); // Hash the password

    // Check if the username or email is already in database
    $query = "SELECT id FROM users WHERE Username = ? OR Email = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            http_response_code(400);
            echo json_encode(["message" => "Username or email already in use"]);
            exit;
        }

        $stmt->close();
    }

    // Insert the data into database
    $insertQuery = "INSERT INTO Accoutns (FirstName, LastName, Username, Email, Address, City, Country, ZipCode, PasswordHash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($insertStmt = $mysqli->prepare($insertQuery)) {
        $insertStmt->bind_param("sssssssss", $firstName, $lastName, $username, $email, $address, $city, $country, $zipCode, $passwordHash);
        if ($insertStmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to register user"]);
        }
        $insertStmt->close();
    }
}
$mysqli->close();
