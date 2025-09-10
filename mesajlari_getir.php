<?php
include "connection.php";

$sender = $_GET['sender_id'];
$receiver = $_GET['receiver_id'];

$query = $conn->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY created_at ASC
");
$query->execute([$sender, $receiver, $receiver, $sender]);
echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));

