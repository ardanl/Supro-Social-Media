<?php
require "connection.php"; // veritabanı bağlantı dosyan
header("Content-Type: application/json; charset=UTF-8");

// Kullanıcı login kontrolü
if (!$KullaniciID) {
    echo json_encode(["status" => "error", "message" => "Giriş yapmanız gerekiyor."]);
    exit;
}

if ($Yetki == 5) {
    echo json_encode(["status" => "error", "message" => "Yetkiniz gönderi paylaşmaya uygun değil."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $IcerikMetni = nl2br($_POST["IcerikMetni"]);
        $etiketlenenKullanicilar = [];

        // Kullanıcı etiketleme yakalama
        preg_match_all('/@([\p{L}0-9_.]+)/u', $IcerikMetni, $matches);
        $usernames = array_unique($matches[1]);

        $allUsers = $db->Get("Kullanici", true);
        $validUsernames = [];
        foreach ($allUsers as $user) {
            if (in_array($user['KullaniciAdi'], $usernames)) {
                $validUsernames[] = $user['KullaniciAdi'];
            }
        }

        // Metin içinde mention linklerini düzenleme
        $IcerikMetni = preg_replace_callback(
            '/@([\p{L}0-9_.]+)/u',
            function ($matches) use (&$etiketlenenKullanicilar, $validUsernames) {
                $username = htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
                
                if ($username === 'everyone') {
                    return '<a href="#">@everyone</a>';
                }
                if (in_array($username, $validUsernames)) {
                    if (!in_array($username, $etiketlenenKullanicilar)) {
                        $etiketlenenKullanicilar[] = $username;
                    }
                    $url = '/u/' . urlencode($username);
                    return '<a href="'. $url .'">@'. $username .'</a>';
                } else {
                    return '@' . $username;
                }
            },
            $IcerikMetni
        );

        // URL tespiti → link haline getirme
        $IcerikMetni = preg_replace_callback(
            '/(https:\/\/[^\s]+)/i',
            function($eslesme) {
                $link = htmlspecialchars($eslesme[0]);
                return '<a href="' . $link . '" target="_blank">' . $link . '</a>';
            }, 
            $IcerikMetni
        );

        $KategoriID = $_POST["SelectKategoriID"] ?? 22;
        $time = time();

        // --- Dosya yükleme ---
        $IcerikResim = $_FILES["IcerikResim"]["name"] ?? "";
        if ($IcerikResim != "") {
            $uzanti = pathinfo($IcerikResim, PATHINFO_EXTENSION);
            $adlandir = $KullaniciAdi . "_" . $time . uniqid() . "." . $uzanti;
            move_uploaded_file($_FILES['IcerikResim']['tmp_name'],'images/' . $adlandir);

            resizeImage("images/$adlandir", "images/$adlandir", 700, 700);

            $Kayit = $db->Insert("Icerikler", [
                "IcerikMetni" => "$IcerikMetni",
                "IcerikResim" => "$adlandir",
                "YazarID"     => $KullaniciID,
                "YayimTarihi" => $time,
                "KategoriID"  => $KategoriID
            ]);
        } else {
            $Kayit = $db->Insert("Icerikler", [
                "IcerikMetni" => "$IcerikMetni",
                "YazarID"     => $KullaniciID,
                "YayimTarihi" => $time,
                "KategoriID"  => $KategoriID
            ]);
        }

        // --- Bildirim işlemleri ---
        if (strpos($IcerikMetni, '@everyone') !== false && ($Yetki == 1 || $Yetki == 6)) {
            $tumKullanicilar = $db->Get("Kullanici")->Where("Aktif", 1);
            foreach ($tumKullanicilar as $kullanici) {
                if ($kullanici["KullaniciID"] == $KullaniciID) continue;
                $db->Insert("Bildirimler", [
                    "AliciID" => $kullanici["KullaniciID"],
                    "KullaniciID" => $KullaniciID, 
                    "BildirimKategoriID" => "1",
                    "BildirimIcerikID" => "$Kayit",
                    "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> bir gönderide tüm kullanıcılardan bahsetti",
                    "BildirimTarihi" => time()
                ]);
            }
        } else {
            foreach ($etiketlenenKullanicilar as $Username) {
                $UserID = $db->Get("Kullanici")->Where("KullaniciAdi", $Username, "KullaniciID");
                $db->Insert("Bildirimler", [
                    "AliciID" => $UserID,
                    "KullaniciID" => $KullaniciID, 
                    "BildirimKategoriID" => "1",
                    "BildirimIcerikID" => "$Kayit",
                    "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> bir gönderide sizden bahsetti",
                    "BildirimTarihi" => time()
                ]);
            }
        }

        // --- Kullanıcı puanı ---
        $Puan = $db->Get("Kullanici")->Where("KullaniciID", $KullaniciID, "Puan");
        $GonderiRandom = $Puan + rand(1, 50);
        $db->Update("Kullanici", ["Puan" => $GonderiRandom], ["KullaniciID" => $KullaniciID]);

        // --- Kullanıcı log kaydı ---
        $db->Insert("KullaniciLoglari", [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$Kayit' olan içeriği yayınladı",
            "Tarih" => time()
        ]);

        // --- Supro AI mention ---
        if (in_array('Supro', $etiketlenenKullanicilar)) {
            $orjinalIcerik = trim(html_entity_decode(strip_tags($_POST["IcerikMetni"]), ENT_QUOTES, 'UTF-8'));

            $openaiKey = "BURAYA_OPENAI_KEY"; // config’den al
            $payload = [
                "model" => "gpt-4o-mini",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "Sen @Supro hesabısın. Türkçe yaz. 1-2 cümle, net ve samimi."
                    ],
                    [
                        "role" => "user",
                        "content" => $orjinalIcerik
                    ]
                ],
                "temperature" => 0.6,
                "max_tokens" => 120
            ];

            $ch = curl_init("https://api.openai.com/v1/chat/completions");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer ".$openaiKey,
                    "Content-Type: application/json"
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
                CURLOPT_TIMEOUT => 15
            ]);

            $result = curl_exec($ch);
            $curlErr = curl_error($ch);
            $http    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($result !== false && $http < 400) {
                $data = json_decode($result, true);
                $suproCevap = trim($data['choices'][0]['message']['content'] ?? '');
                $suproID = 3; 
                $db->Insert("Yorumlar", [
                    "KullaniciID"  => $suproID,
                    "IcerikID"     => $Kayit,
                    "YorumIcerigi" => htmlspecialchars($suproCevap, ENT_QUOTES, 'UTF-8'),
                    "YorumTarihi"  => time()
                ]);
            }
        }

        echo json_encode([
            "status" => "ok",
            "message" => "Gönderiniz başarıyla paylaşıldı!",
            "redirect" => "post/$time-$KullaniciID"
        ]);
        set_exception_handler(function ($e) {
            echo json_encode([
                "status" => "error",
                "message" => "Hata: " . $e->getMessage()
            ]);
            exit;
        });

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            echo json_encode([
                "status" => "error",
                "message" => "PHP Hatası: $errstr ($errfile:$errline)"
            ]);
            exit;
        });
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Hata: ".$e->getMessage()]);
    }
}
?>