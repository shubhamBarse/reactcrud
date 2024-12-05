<?php
// Enable error reporting (optional for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set CORS headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Replace '*' with your frontend domain in production
header("Access-Control-Allow-Methods: DELETE, OPTIONS"); // Include OPTIONS for preflight
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database credentials
$servername = "localhost";
$username = "root";
$password_db = "Shubham@123";
$dbname = "reactapp";

// Connect to the database
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Parse DELETE request payload (if needed)
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['id'] ?? null;

if ($userId) {
    // Prepare statement to delete user by ID
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    
    if ($stmt === false) {
        // If prepare() failed, output the error
        echo json_encode(["success" => false, "message" => "SQL prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $userId);  // Bind the ID as an integer

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete user."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid user ID."]);
}

// Close connection
$conn->close();
?>


