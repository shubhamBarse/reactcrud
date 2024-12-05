<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Validate input
    if (isset($data->name) && isset($data->email) && isset($data->password) && isset($data->dob)) {
        $name = $data->name;
        $email = $data->email;
        $password = $data->password;
        $dob = $data->dob;

        // Database connection
        $servername = "localhost";
        $username = "root";
        $password_db = "Shubham@123";  // Your database password
        $dbname = "reactapp";  // Your database name

        $conn = new mysqli($servername, $username, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, dob) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $dob);

        if ($stmt->execute()) {
            echo json_encode(["message" => "User added successfully!"]);
            // header("Location: /home"); // Redirect to homepage after success
            // exit(); // Make sure no further code is executed after the redirect
        } else {
            echo json_encode(["message" => "Error adding user"]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["message" => "Invalid input"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>






