<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: admin_login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comments";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["action"]) && $_POST["action"] === "edit" && isset($_POST["id"]) && isset($_POST["message"])) {
    $id = $_POST["id"];
    $newMessage = $_POST["message"];

    $sql = "UPDATE fb SET message = ?, edited_by_admin = 1  WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newMessage, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_panel.php"); 
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
