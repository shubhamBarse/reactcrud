<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

header("Access-Control-Allow-Origin: *");  // Allow all domains
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$db_conn = new mysqli("localhost", "root", "Shubham@123", "reactapp");

if ($db_conn->connect_error) {
    die("ERROR: Could Not Connect: " . $db_conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        $path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        if (isset($path[3]) && is_numeric($path[3])) {
            $idd = $db_conn->real_escape_string($path[2]);
            $id = 13;
            $stmt = $db_conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $userrow = $result->fetch_assoc();
                echo json_encode([
                    "id" => $userrow['id'],
                    "username" => $userrow['name'],
                    "email" => $userrow['email'],
                    "password" => $userrow['password']
                ]);
            } else {
                echo json_encode(["result" => "User not found"]);
            }
            $stmt->close();
        } else {
            $result = $db_conn->query("SELECT * FROM users");
            $json_array = [];

            while ($row = $result->fetch_assoc()) {
                $json_array[] = [
                    "id" => $row['id'],
                    "username" => $row['name'],
                    "email" => $row['email'],
                    "status" => $row['dob']
                ];
            }

            echo json_encode($json_array ?: ["result" => "No data found"]);
        }
        break;

    case "POST":
        $userpostdata = json_decode(file_get_contents("php://input"), true);

        if (isset($userpostdata['username'], $userpostdata['email'], $userpostdata['status'])) {
            $stmt = $db_conn->prepare("INSERT INTO tbl_user (username, useremail, status) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $userpostdata['username'], $userpostdata['email'], $userpostdata['status']);

            if ($stmt->execute()) {
                echo json_encode(["success" => "User added successfully"]);
            } else {
                echo json_encode(["error" => "Failed to add user"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["error" => "Invalid input"]);
        }
        break;
        case "PUT":
            $userUpdate = json_decode(file_get_contents("php://input"), true);
        
            // Ensure required fields are provided
            // if (isset($userUpdate['username'], $userUpdate['email'], $userUpdate['dob'], $userUpdate['id'])) {
                // Prepare the SQL statement with the correct column names
                $stmt = $db_conn->prepare("UPDATE users SET name = ?, email = ?, dob = ?, password = ? WHERE id = ?");
                $stmt->bind_param(
                    "ssssi", // Match the types: string, string, string, string, and integer
                    $userUpdate['username'], 
                    $userUpdate['email'], 
                    $userUpdate['dob'], 
                    $userUpdate['password'], // Bind the password parameter
                    $userUpdate['id'] // Bind the id parameter
                );
        
                if ($stmt->execute()) {
                    echo json_encode(["success" => "User record updated successfully"]);
                } else {
                    echo json_encode(["error" => "Failed to update user"]);
                }
                $stmt->close();
            // } 
            // else {
            //     echo json_encode(["error" => "Invalid input"]);
            // }
            break;
        
    default:
        echo json_encode(["error" => "Invalid request method"]);
}

$db_conn->close();
?>

