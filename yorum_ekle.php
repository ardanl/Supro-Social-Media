<?php
session_start();
// Yorum ekleme işlemini yap
// ...

// Bildirim için token’ı çek
$pdo = new PDO('mysql:host=mt-monterey.guzelhosting.com;dbname=svenyazi_SvenTalk;charset=utf8', 'svenyazi_arda', 'vGTXOZCPY54P');
$stmt = $pdo->prepare("SELECT token FROM fcm_tokens WHERE user_id = ?");
$stmt->execute([$gonderiSahibiId]);
$tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

// FCM server key
$serverKey = 'FIREBASE_SERVER_KEY';

foreach ($tokens as $token) {
    $payload = [
        'to' => $token,
        'notification' => [
            'title' => 'Gönderinize yorum yapıldı!',
            'body'  => $_SESSION['username']." adlı kullanıcı yorum yaptı.",
            'icon'  => '/img/icon.png'
        ]
    ];
    $ch = curl_init('https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: key='.$serverKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
