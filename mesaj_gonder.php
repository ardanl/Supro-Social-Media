<?php
include "connection.php";
$sender = $_POST['sender_id'];
$receiver = $_POST['receiver_id'];
$message = trim($_POST['message']);

if (!empty($message)) {
    $query = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, time) VALUES (?, ?, ?, ?)");
    $query->execute([$sender, $receiver, $message, time()]);
    echo "ok";
} else {
    echo "Mesaj boş olamaz.";
}
