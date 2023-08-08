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

if (isset($_POST["action"]) && isset($_POST["id"])) {
    $action = $_POST["action"];
    $id = $_POST["id"];

    if ($action === "reject") {
        $sql = "UPDATE fb SET approved = 0 WHERE id = ?";
    }else{
        $sql = "UPDATE fb SET approved = 1 WHERE id=?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

$sql = "SELECT * FROM fb";
$result = $conn->query($sql);
$feedbacks = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Admin Panel</h1>
    <a href="logout.php">Logout</a>


    <div id="feedbacksContainer">
        <?php foreach ($feedbacks as $feedback) : ?>
            <div class="feedback">
                <h3><?= $feedback["name"] ?></h3>
                <p><?= $feedback["email"] ?></p>

                <form action="edit_feedback.php" method="post">
                    <input type="hidden" name="id" value="<?= $feedback["id"] ?>">
                    <textarea name="message"><?= $feedback["message"] ?></textarea>
                    <?php if ($feedback["edited_by_admin"]) : ?>
                      <p><em>Edited</em></p>
                     <?php endif; ?>
                    <button type="submit" name="action" value="edit">Edit</button>
                </form>
                


                <img src="data:image/jpeg;base64,${feedback.image}">
                
                <?php if ($feedback["approved"]) : ?>

                    <form action="admin_panel.php" method="post">
                        <input type="hidden" name="id" value="<?= $feedback["id"] ?>">
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>
                <?php else : ?>
                    <form action="admin_panel.php" method="post">
                        <input type="hidden" name="id" value="<?= $feedback["id"] ?>">
                        <button type="submit" name="action" value="approve">Approve</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>
</body>


</html>
