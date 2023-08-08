<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comments";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $response = array("success" => false, "message" => "Connection failed: " . $conn->connect_error);
    echo json_encode($response);
    exit;
}

$sql = "UPDATE fb SET approved = 1";

if ($conn->query($sql) === TRUE) {
    $response = array("success" => true);
} else {
    $response = array("success" => false, "message" => "Error: " . $conn->error);
}

$conn->close();
echo json_encode($response);
?>
