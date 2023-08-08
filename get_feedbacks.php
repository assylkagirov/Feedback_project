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

$sql = "SELECT * FROM fb WHERE approved=1 ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result) {
    $feedbacks = array();

    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }

    $response = array("success" => true, "feedbacks" => $feedbacks);
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "Error: " . $conn->error);
    echo json_encode($response);
}


$conn->close();
?>
