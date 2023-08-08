<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comments";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST["name"];
$email = $_POST["email"];
$message = $_POST["message"];


$image = null;
if ($_FILES["image"]["tmp_name"]) {
    $image = file_get_contents($_FILES["image"]["tmp_name"]); 
}


$uploadedFile = $_FILES['image'];
$fileSize = $uploadedFile['size'];
$maxFileSize = 1024 * 1024; 
if ($fileSize > $maxFileSize) {
    echo json_encode(array("success" => false, "message" => "File must be no more than 1MB"));    
    exit;
}


$stmt = $conn->prepare("INSERT INTO fb (name, email, message, image, approved) VALUES (?, ?, ?, ?, 1)");
$stmt->bind_param("sssb", $name, $email, $message, $image);

if ($stmt->execute()) {
    $response = array("success" => true); 
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "Error: " . $stmt->error);
    echo json_encode($response);
}

$stmt->close();
$conn->close();
?>
