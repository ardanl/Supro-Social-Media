<title><?= $SiteAdi; ?> - Mesajlarım</title>
<?php
$currentUserId = $KullaniciID;
// Mesajları çek
$sql = "SELECT m.*
FROM messages m
INNER JOIN (
    SELECT 
        CASE 
            WHEN sender_id = :id THEN receiver_id
            ELSE sender_id
        END AS user_id,
        MAX(time) AS last_msg_time
    FROM messages
    WHERE sender_id = :id OR receiver_id = :id
    GROUP BY user_id
) latest_msg
ON (
    (m.sender_id = :id AND m.receiver_id = latest_msg.user_id OR 
     m.receiver_id = :id AND m.sender_id = latest_msg.user_id)
    AND m.time = latest_msg.last_msg_time
)
ORDER BY m.time DESC
";


$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $currentUserId]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$uniqueConversations = [];
foreach ($conversations as $msg) {
    $otherUserId = ($msg['sender_id'] == $currentUserId) ? $msg['receiver_id'] : $msg['sender_id'];
    // Eğer bu kullanıcı daha önce eklenmemişse
    if (!isset($uniqueConversations[$otherUserId])) {
        $uniqueConversations[$otherUserId] = $msg;
    }
}
?>
<div class="container mt-4">
    <h4><i class="fi fi-ts-comment-alt-dots pe-2"></i>Mesajlar</h4>
    <div class="list-group">
        <?php foreach ($uniqueConversations as $otherUserId => $msg): ?>
            <?php
                $MesajGonderen = $db->Get("Kullanici")->Where("KullaniciID", $otherUserId);
                if($db->Row($MesajGonderen) > 0){
                    $MesajGonderen = $MesajGonderen[0];
            ?>
            <a href="messages?receiver_id=<?= $otherUserId ?>" class="list-group-item list-group-item-action position-relative">
                <?php if($msg['sender_id'] != $currentUserId && $msg["okundu"] == 0){ ?>
                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
                <?php } ?>
                <div class="d-flex align-items-center">
                    <img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(./images/<?= $MesajGonderen["ProfilResmi"]; ?>);background-position: center;background-size: cover;">
                    <strong>
                        @<?= $MesajGonderen["KullaniciAdi"]; 
                        if($MesajGonderen["Onaylandi"] == true && $MesajGonderen["Color"]){
                            echo '<i class="fi ps-1 tick fi-ss-badge-check ' . $MesajGonderen["Color"] . '"></i>';
                        }
                        ?>
                    </strong>
                </div>
                <small class="text-muted float-end" style="top: 0.3rem;position: absolute;right: 0.6rem;">
                    <?php 
                        $date1 = new DateTime();
                        $date1->setTimestamp($msg["time"]);
                        $date2 = new DateTime();
                        $diff = $date1->diff($date2);
                        $minutes = ($diff->h * 60) + $diff->i + ($diff->d * 24 * 60);
                        $hours = $diff->h + ($diff->d * 24);
                        $days = $diff->days;

                        if ($days > 0) echo "{$days} gün önce";
                        elseif ($hours > 0) echo "{$hours} saat önce";
                        elseif ($minutes > 0) echo "{$minutes} dakika önce";
                        else echo "şimdi";
                    ?>
                </small>
                <div>
                    <span class="text-muted">
                        <?= ($msg['sender_id'] == $currentUserId ? "Sen: " : "@{$MesajGonderen['KullaniciAdi']}: "); ?>
                        <?= htmlspecialchars($msg['message']); ?>
                    </span>
                </div>
            </a>
        <?php } endforeach; ?>
    </div>
    <small>Eğer hiç mesajın yoksa üzülme. Sende takipçilerinin profillerini ziyaret ederek onlarla mesajlaşabilirsin.</small>
</div>