<?php
session_start();

$admin_username = "admin";
$admin_password = "123";

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION["admin"] = true;
        header("Location: admin_panel.php");
    } else {
        echo "Неправильно логин или пароль";
    }
}
?>
