<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: hhttp://local.reactcrud.com/"); // Replace with your React app's URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$servername = "localhost";
$username = "root";
$password = "Shubham@123";
$dbname = "reactapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT id, name AS username, email, dob FROM users WHERE id=$id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "fdfd";
    echo json_encode($result->fetch_assoc());
    return $result;
} else {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
}

$conn->close();
?>




