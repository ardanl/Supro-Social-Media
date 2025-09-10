<?php
if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $sender = 'User';  // Burada kullanıcı bilgisi dinamik olabilir

    // Mesajı veritabanına kaydet
    $stmt = $conn->prepare("INSERT INTO messages (sender, message, time) VALUES (?, ?, ?)");
    $stmt->bind_param("ss", $sender, $message, time());
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
