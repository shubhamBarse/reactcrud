<?php
// Enable error reporting (optional for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set CORS headers (to allow frontend requests)
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow all origins, or replace '*' with 'http://localhost:3000' if needed
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow the necessary methods

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // End the request here, as OPTIONS requests are just for CORS preflight
}

// Database credentials
$servername = "localhost";
$username = "root";
$password_db = "Shubham@123";  // Your database password
$dbname = "reactapp";  // Your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query to fetch users
$sql = "SELECT id, name, email, dob FROM users"; // Select specific columns to avoid unnecessary data
$stmt = $conn->prepare($sql); // Prepare the SQL statement
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set

// Create an array to hold the user data
$users = [];

// Fetch the data from the result set
while ($row = $result->fetch_assoc()) {
    $users[] = array(
        "id" => $row['id'],
        "username" => $row['name'], // Assuming 'name' field is the username
        "email" => $row['email'],
        "dob" => $row['dob']
    );
}

// Return the result as JSON
echo json_encode($users);
return $users;

// Close the database connection
$stmt->close();
$conn->close();
?>

















