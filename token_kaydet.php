<?php
// Gelen JSON’i oku
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'] ?? '';

if ($token) {
    // Veritabanına kaydet (örnek: MySQL)
    $pdo = new PDO('mysql:host=mt-monterey.guzelhosting.com;dbname=svenyazi_SvenTalk;charset=utf8', 'svenyazi_arda', 'vGTXOZCPY54P');
    $stmt = $pdo->prepare("REPLACE INTO fcm_tokens (user_id, token) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $token]);
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Token yok']);
}
?>