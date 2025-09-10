<?php
header("Access-Control-Allow-Origin: *");

if ($_POST["ajax"] != "") {
    include "connection.php";
    $ajax = clean($_POST["ajax"]);
    if(isset($_COOKIE["KullaniciID"])){
        $KullaniciID = $_COOKIE["KullaniciID"];
        $UserID = $_COOKIE["KullaniciID"];
        
        $KullaniciAdi = $db->Get("Kullanici")->Where("KullaniciID", "$KullaniciID", "KullaniciAdi");
        
        $Puan = $db->Get("Kullanici")->Where("KullaniciID", $KullaniciID, "Puan");

        $BegeniRandom = $Puan + rand(1, 5);
        $BegenmeRandom = $Puan - 5;
        
        $YorumRandom = $Puan + rand(1, 10);
        $YorumSilRandom = $Puan - 10;
        
        $GonderiRandom = $Puan + rand(1, 50);
        $GonderiSilRandom = $Puan - 50;
        
        $TakipRandom = $Puan + rand(1, 20);
        $TakipBirakRandom = $Puan - 20;
    }
    
    $bugun = date("d.m.Y H:i");
    
    // Giriş
    if($ajax == 1){
        $KullaniciAdi = clean($_POST["KullaniciAdi"]);
        $Sifre = clean($_POST["Sifre"]);
        
        $Kullanici = $db->Get("Kullanici")->Where("KullaniciAdi", "$KullaniciAdi");
        if($db->Row($Kullanici) > 0){
            $Kullanici = $Kullanici[0];
            if($Kullanici["EpostaDogrulandi"] != ""){
                if($Kullanici["Silindi"] == 0){
                    if($Kullanici["Aktif"] == 1){
                        if($Kullanici["KilitlenmeSonu"] < time()){
                            if($Kullanici["BasarisizGirisDenemesi"] < 15){
                                if($Sifre == $Kullanici["Sifre"]){
                                    $db->Update("Kullanici", 
                                    ["SonGiris" => time(),
                                    "KilitlenmeSonu" => "",
                                    "BasarisizGirisDenemesi" => 0],
                                    ["KullaniciID" => $Kullanici["KullaniciID"]]);
                                    
                                    $db->Insert("KullaniciLoglari",
                                    ["KullaniciID" => $Kullanici["KullaniciID"],
                                    "Islem" => "Giriş",
                                    "Tarih" => time()]);
                                    $Zaman = time() + (60 * 60 * 24 * 365); // 1 Yıl
                                    setcookie("KullaniciID", $Kullanici["KullaniciID"], $Zaman, "/");
                                    echo true;
                                }else{
                                    $db->Update("Kullanici", 
                                    ["BasarisizGirisDenemesi" => $Kullanici["BasarisizGirisDenemesi"]+1],
                                    ["KullaniciID" => $Kullanici["KullaniciID"]]);
                                    
                                    $db->Insert("KullaniciLoglari",
                                    ["KullaniciID" => $Kullanici["KullaniciID"],
                                    "Islem" => "Hatalı şifre",
                                    "Tarih" => time()]);
                                    echo "Sifre";
                                }
                            }else{
                                $db->Update("Kullanici", 
                                ["KilitlenmeSonu" => time() + (60*15)],
                                ["KullaniciID" => $Kullanici["KullaniciID"]]);
                                echo date("d.m.Y H:i:s" , time() + (60*15));
                            }
                        }else{
                            echo date("d.m.Y H:i:s" ,$Kullanici["KilitlenmeSonu"]);
                        }
                    }else{
                        echo "Pasif";
                    }
                }else{
                    echo "Silindi";
                }
            }else{
                echo "Eposta";
            }
        }else{
            echo "KullaniciAdi";
        }
    }
    // Kayıt
    if($ajax == 2){
        $KullaniciAdi = clean($_POST["KullaniciAdi"]);
        $Sifre = clean($_POST["Sifre"]);
        $Email = clean($_POST["Email"]);
        $KayitKod = clean($_POST["KayitKod"]);
        $_SESSION["login"] = clean($_POST["Email"]);
        if($db->Row($db->Get("Kullanici")->Where("KullaniciAdi", $KullaniciAdi)) == 0){
            if($db->Row($db->Get("Kullanici")->Where("Email", $Email)) == 0){
                if($KayitKod != "" && $db->Row($db->Get("KayitKod")->Where("KayitKodNo", $KayitKod)) > 0){
                    $Kayit = $db->Insert("Kullanici",
                    [
                        "KullaniciAdi " => "$KullaniciAdi",
                        "Sifre" => "$Sifre",
                        "Email" => "$Email",
                        "KayitKod" => "$KayitKod",
                        "Puan" => "150",
                        "KayitTarihi" => time()
                    ]);
                    $DavetID = $db->Get("KayitKod")->Where("KayitKodNo", $KayitKod, "KayitKodKullaniciID");
                    $DavetPuan = $db->Get("Kullanici")->Where("KullaniciID", $DavetID, "Puan") + 125;
                    $PuanGuncelle = $db->Update("Kullanici", 
                    [
                        "Puan" => $DavetPuan
                    ],
                    [
                        "KullaniciID" => $DavetID
                    ]);
                }else{
                    $Kayit = $db->Insert("Kullanici",
                    [
                        "KullaniciAdi " => "$KullaniciAdi",
                        "Sifre" => "$Sifre",
                        "Email" => "$Email",
                        "KayitKod" => "$KayitKod",
                        "KayitTarihi" => time()
                    ]);
                }
                $_SESSION["loginID"] = $Kayit;
                $Bildirim = $db->Insert("Bildirimler",
                [
                    "KullaniciID" => "0",
                    "AliciID" => "$Kayit",
                    "BildirimKategoriID" => "3",
                    "BildirimIcerik" => "$bugun tarihinde hesap oluşturdunuz",
                    "BildirimTarihi" => time()
                ]);
                $messages = [
                    '👋 Merhaba! Supro’ya hoş geldin!',
                    'Ben Supy Asistan, burada ne zaman istersen yanında olan dostun sayılırım. Takıldığın bir şey olursa ya da sadece merak ettiklerini paylaşmak istersen bana yazabilirsin.',
                    'Burası birlikte eğlenip fikirlerimizi paylaştığımız bir topluluk. 💬✨ Tek ricam, kurallara uyarak hepimizin keyifli vakit geçirmesine yardımcı olman.',
                    'Aramıza katıldığın için çok mutluyuz, hadi keşfetmeye başlayalım! 🚀'
                ];

                $time = time();

                foreach ($messages as $message) {
                    $db->Insert("messages", [
                        "sender_id" => 3,
                        "receiver_id" => $Kayit,
                        "message" => $message,
                        "time" => $time,
                        "okundu" => 0
                    ]);
                    $time++;
                }
                if($Kayit){
                    echo true;
                }else{
                    echo false;
                }
            }else{
                echo "EMailVar";
            }
        }else{
            echo "KullaniciVar";
        }
    }
    // Beğen
    if($ajax == 3){
        $KullaniciID = clean($_POST["KullaniciID"]);
        $IcerikID = clean($_POST["IcerikID"]);
        $AliciID = clean($_POST["AliciID"]);
        $Kontrol = true;
        $Bul = $db->Get("Begeni")->Where("KullaniciID", $KullaniciID);
        if($db->Row($Bul) > 0){
            foreach($Bul as $value){
                if($value["IcerikID"] == $IcerikID){
                    $Kontrol = false;
                }
            }
        }
        if($Kontrol == false){
            $Sil = $db->Delete("Begeni", ["KullaniciID" => $KullaniciID, "IcerikID" => $IcerikID]);
            echo false;
            $Kayit = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$KullaniciID",
                "Islem" => "ID'si '$IcerikID' olan içeriğin beğenmesini geri çekti",
                "Tarih" => time()
            ]);
            if($AliciID != $KullaniciID){
                $PuanGuncelle = $db->Update("Kullanici", 
                [
                    "Puan" => $BegenmeRandom
                ],
                [
                    "KullaniciID" => $KullaniciID
                ]);
            }
            $Sil = $db->Delete("Bildirimler", ["KullaniciID" => $KullaniciID, "BildirimIcerikID" => $IcerikID, "BildirimKategoriID" => 2]);
        }else{
            if($AliciID != $KullaniciID){
                $PuanGuncelle = $db->Update("Kullanici", 
                [
                    "Puan" => $BegeniRandom
                ],
                [
                    "KullaniciID" => $KullaniciID
                ]);
            }
            $Kayit = $db->Insert("Begeni",
            [
                "KullaniciID " => "$KullaniciID",
                "IcerikID" => "$IcerikID",
                "Tarih" => time()
            ]);
            $Kayit = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$KullaniciID",
                "Islem" => "ID'si '$IcerikID' olan içeriği beğendi",
                "Tarih" => time()
            ]);
            if($KullaniciID != $AliciID){
                $Bildirim = $db->Insert("Bildirimler",
                [
                    "KullaniciID" => "$KullaniciID",
                    "AliciID" => "$AliciID",
                    "BildirimKategoriID" => "2",
                    "BildirimIcerikID" => "$IcerikID",
                    "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> gönderini beğendi",
                    "BildirimTarihi" => time()
                ]);
            }
            echo true;
        }
    }
    // Yorum
    if($ajax == 4){
        $KullaniciID = $_COOKIE["KullaniciID"];
        $IcerikID = clean($_POST["IcerikID"]);
        $AliciID = clean($_POST["AliciID"]);
        
        $Yorum = nl2br($_POST["Yorum"]);
        
        preg_match_all('/@([\p{L}0-9_.]+)/u', $Yorum, $matches);
        $usernames = array_unique($matches[1]);
        
        $allUsers = $db->Get("Kullanici", true); 
        
        $validUsernames = [];
        foreach ($allUsers as $user) {
            if (in_array($user['KullaniciAdi'], $usernames)) {
                $validUsernames[] = $user['KullaniciAdi'];
            }
        }
        
        $etiketlenenKullanicilar = [];
        
        $Yorum = preg_replace_callback(
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
            $Yorum
        );

        $Kayit = $db->Insert("Yorumlar",
        [
            "KullaniciID " => "$KullaniciID",
            "IcerikID" => "$IcerikID",
            "YorumIcerigi" => mb_convert_encoding($Yorum,  'UTF-8', 'auto'),
            "YorumTarihi" => time()
        ]);
        
        if (!empty($etiketlenenKullanicilar)) {
            foreach ($etiketlenenKullanicilar as $etiketliKullanici) {
                $etiketli = $db->Get("Kullanici")->Where("KullaniciAdi", $etiketliKullanici, "KullaniciID");
                
                if ($etiketli == $KullaniciID) continue;
        
                $db->Insert("Bildirimler", [
                    "KullaniciID" => $KullaniciID,
                    "AliciID" => $etiketli,
                    "BildirimKategoriID" => "6",
                    "BildirimIcerikID" => "$IcerikID,$Kayit",
                    "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> bir yorumda sizden bahsetti: \"$Yorum\"",
                    "BildirimIcerikCevapID" => "$IcerikID",
                    "BildirimTarihi" => time()
                ]);
            }
        }
        
        if($AliciID != $KullaniciID){
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $YorumRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
        }
        if($KullaniciID != $AliciID){
            $Bildirim = $db->Insert("Bildirimler",
            [
                "KullaniciID" => "$KullaniciID",
                "AliciID" => "$AliciID",
                "BildirimKategoriID" => "6",
                "BildirimIcerikID" => "$IcerikID,$Kayit",
                "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> gönderine \"$Yorum\" yorumunu yaptı",
                "BildirimIcerikCevapID" => "$IcerikID",
                "BildirimTarihi" => time()
            ]);
        }
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$IcerikID' olan içeriğe, ID'si '$Kayit' olan \"$Yorum\" yorumunu yaptı",
            "Tarih" => time()
        ]);
        $ProfilResmi = $db->Get("Kullanici")->Where("KullaniciID", "$_COOKIE[KullaniciID]", "ProfilResmi");

        // === 1) Supro etiketlendi mi? (CASE-INSENSITIVE) ===
        $suproMentioned = false;
        foreach ($etiketlenenKullanicilar as $u) {
            if (strcasecmp($u, 'Supro') === 0) { // 'Supro' == 'supro'
                $suproMentioned = true;
                break;
            }
        }

        if ($suproMentioned) {

            // (Opsiyonel) AJAX cevabını hemen döndür, AI işlemini arkada tamamla:
            // if (function_exists('fastcgi_finish_request')) { fastcgi_finish_request(); }

            $orjinalYorum = trim(html_entity_decode(strip_tags($_POST["Yorum"]), ENT_QUOTES, 'UTF-8'));

            // Güvenli: anahtarı .env'den al
            $openaiKey = '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'; // putenv/Apache env/PHP dotenv ile koy

            // === 2) OpenAI'ye İstek ===
            $payload = [
                "model" => "gpt-4o-mini",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "Sen @Supro hesabısın. Türkçe yaz. 1-2 cümle, net ve samimi.
        Asla tek başına emoji gönderme. En az 1 kelime yazı olmalı. Gerekirse 1 emoji yazının içinde olabilir."
                    ],
                    [
                        "role" => "user",
                        "content" => $orjinalYorum
                    ]
                ],
                "temperature" => 0.6,
                "max_tokens"  => 120
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

            // === 3) Hata yakalama & log ===
            if ($result === false || $http >= 400) {
                error_log("AI error ($http): $curlErr | raw: ".substr((string)$result,0,500));
                // Sessiz fallback
                $cevap = "Güzel soru, bence mantıklı görünüyor. 🙂";
            } else {
                $data  = json_decode($result, true);

                // Bazı sürümlerde alan yapısı farklı olabilir; güvenli alım:
                $cevap = trim($data['choices'][0]['message']['content'] ?? '');

                // === 4) Çıktı temizleme/guardrails ===

                // "Supro:" gibi önekleri temizle
                $cevap = preg_replace('/^\s*@?supro[:\-\s]*/iu', '', $cevap);

                // Sadece emoji mi?
                $onlyEmoji = preg_match('/^\p{So}+$/u', $cevap);
                if ($onlyEmoji || $cevap === '') {
                    $cevap = "Yakalamana sevindim, detayları konuşalım. 🙂";
                }

                // Çok uzunsa kısalt
                if (mb_strlen($cevap) > 240) {
                    $cevap = mb_substr($cevap, 0, 240) . "…";
                }
            }

            // === 5) DB'ye yaz (XSS'den kaçın) ===
            $suproID = 3; // @Supro kullanıcı ID'si (sabit)
            // Supro cevabını da yorumlar tablosuna ekle
            $db->Insert("YorumCevap", [
                "YorumCevapKullaniciID"   => $suproID,
                "YorumCevapYorumID"      => $Kayit,
                "YorumCevapMetin"  => mb_convert_encoding($cevap, 'UTF-8', 'auto'),
                "YorumCevapTarih"   => time()
            ]);
        }

        echo $Kayit . "," . $ProfilResmi;
    }
    // Takip Et
    if($ajax == 5){
        $TakipID = clean($_POST["TakipID"]);
        $Kayit = $db->Insert("Takipler", 
        [
            "TakipEdilenID" => $TakipID,
            "KullaniciID" => $KullaniciID,
            "Tarih" => time()
        ]);
        if($TakipID != $KullaniciID){
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $TakipRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
        }
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$TakipID' olan kişiyi takip etti",
            "Tarih" => time()
        ]);
        $Bildirim = $db->Insert("Bildirimler",
        [
            "KullaniciID" => "$KullaniciID",
            "AliciID" => "$TakipID",
            "BildirimKategoriID" => "4",
            "BildirimIcerikID" => "$KullaniciID",
            "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> sizi takip etmeye başladı",
            "BildirimTarihi" => time()
        ]);
    }
    // Takip Bırak
    if($ajax == 6){
        $TakipID = clean($_POST["TakipID"]);
        $Sil = $db->Delete("Takipler", ["KullaniciID" => $KullaniciID, "TakipEdilenID" => $TakipID]);
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$TakipID' olan kişiyi takibi bıraktı",
            "Tarih" => time()
        ]);
        if($TakipID != $KullaniciID){
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $TakipBirakRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
        }
        $Sil = $db->Delete("Bildirimler", ["KullaniciID" => $KullaniciID, "AliciID" => "$TakipID", "BildirimKategoriID" => 4]);
    }
    // Post Şikayet
    if($ajax == 7){
        $IcerikID = clean($_POST["IcerikID"]);
        $Konu = clean($_POST["Konu"]);
        $Metin = clean($_POST["Metin"]);
        
        $Kayit = $db->Insert("Sikayet", 
        [
            "SikayetYer" => 1, 
            "SikayetKim" => $IcerikID,
            "KullaniciID" => $KullaniciID,
            "KategoriID" => $Konu,
            "SikayetAciklama" => $Metin,
            "Tarih" => time()
        ]);
        
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$IcerikID' olan içeriği şikayet etti",
            "Tarih" => time()
        ]);
    }
    // Post Gizleme/Gösterme
    if($ajax == 8){
        $IcerikID = clean($_POST["IcerikID"]);
        $Durum = clean($_POST["Durum"]);

        $db->Update("Icerikler", 
        [
            "Durum" => $Durum
        ],
        [
            "IcerikID" => $IcerikID
        ]);

        if($Durum == true){
            $Durum = "içeriğin gizlemesini kaldırdı";
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $GonderiRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
            echo 0;
        }else{
            $Durum = "içeriği gizledi";
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $GonderiSilRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
            echo 1;
        }
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$IcerikID' olan $Durum",
            "Tarih" => time()
        ]);
    }
    // Post Silme & Gönderi Silme
    if($ajax == 9){
        $IcerikID = clean($_POST["IcerikID"]);

        $Resim = $db->Get("Icerikler")->Where("IcerikID", $IcerikID, "IcerikResim");
        $db->Delete("Icerikler", ["IcerikID" => $IcerikID]);
        $db->Delete("Yorumlar", ["IcerikID" => $IcerikID]);
        $db->Delete("Begeni", ["IcerikID" => $IcerikID]);
        $db->Delete("Bildirimler", ["BildirimIcerikID" => $IcerikID]);
        foreach($db->Get("Bildirimler")->Where("BildirimKategoriID", 6) as $Bildirim){
            $dizi = explode(",", $Bildirim["BildirimIcerikID"]);
            if($dizi[0] == $IcerikID){
                $db->Delete("Bildirimler", ["BildirimID" => $Bildirim["BildirimID"]]);
            }
        }
        $db->Delete("Bildirimler", ["BildirimIcerikID" => $IcerikID]);
        
        $Sil = $db->Delete("Bildirimler", ["KullaniciID" => $KullaniciID, "BildirimIcerikCevapID" => "$IcerikID"]);
        
        $PuanGuncelle = $db->Update("Kullanici", 
        [
            "Puan" => $GonderiSilRandom
        ],
        [
            "KullaniciID" => $KullaniciID
        ]);
        $path = __DIR__ . "/images/$Resim"; // ya da sabit tanımlı bir base path
        unlink($path);

    
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$IcerikID' olan gönderiyi sildi",
            "Tarih" => time()
        ]);
    }
    // Yorum Silme
    if($ajax == 10){
        $YorumID = clean($_POST["YorumID"]);
        $IcerikID = clean($_POST["IcerikID"]);
        echo $IcerikID;
        $AliciID = $db->Get("Yorumlar")->Where("YorumID", $YorumID, "KullaniciID");
        $db->Delete("Yorumlar", ["YorumID" => $YorumID]);
        
        if($AliciID != $KullaniciID){
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $YorumSilRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
        }
        $Sil = $db->Delete("Bildirimler", ["KullaniciID" => $KullaniciID, "BildirimIcerikCevapID" => "$IcerikID", "BildirimKategoriID" => 7]);
        $Sil = $db->Delete("Bildirimler", ["KullaniciID" => $KullaniciID, "BildirimIcerikID" => "$IcerikID,$YorumID", "BildirimKategoriID" => 6]);
        $Sil = $db->Delete("YorumCevap", ["YorumCevapKullaniciID" => $KullaniciID, "YorumCevapYorumID" => "$YorumID"]);
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$YorumID' olan yorumunu sildi",
            "Tarih" => time()
        ]);
        $BildirimID = $db->Get("Bildirimler")->Where("BildirimIcerikID", "Yorum,$IcerikID");
        if($db->Row($BildirimID)){
            foreach($BildirimID as $Bildirim){
                if($Bildirim["KullaniciID"] == "$KullaniciID"){
                    $db->Delete("Bildirimler", ["BildirimID" => $Bildirim["BildirimID"]]);
                    exit;
                }
            }
        }
    }
    // Yorum Şikayet
    if($ajax == 11){
        $IcerikID = clean($_POST["IcerikID"]);
        $Konu = clean($_POST["Konu"]);
        $Metin = clean($_POST["Metin"]);
        
        $Kayit = $db->Insert("Sikayet", 
        [
            "SikayetYer" => 2, 
            "SikayetKim" => $IcerikID,
            "KullaniciID" => $KullaniciID,
            "KategoriID" => $Konu,
            "SikayetAciklama" => $Metin,
            "Tarih" => time()
        ]);
        
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$IcerikID' olan yorumu şikayet etti",
            "Tarih" => time()
        ]);
    }
    // Kullanıcı Bilgisi Güncelleme
    if($ajax == 12){
        $Veri = clean($_POST["Veri"]);
        $Column = clean($_POST["Column"]);
        $UserID = clean($_POST["UserID"]);
        if($Column == "DogumTarihi"){
            $Veri = strtotime($Veri);
        }
        if($Column == "Instagram" || $Column == "Snapchat" || $Column == "Linkedin"){
            $Veri = strtolower($Veri);
        }
        if($Column == "Instagram"){
            $Veri = str_replace("@", "", $Veri);
        }
        if($Column == "Email"){
            if($db->Row($db->Get("Kullanici")->Where("Email", $Veri)) > 0){
                echo "Zaten kayıtlı!";
                exit;
            }
            if($db->Get("Kullanici")->Where("KullaniciID", $KullaniciID, "Email") != $Veri){
                $Guncelle = $db->Update("Kullanici",
                [
                    "EpostaDogrulandi" => "0"
                ],
                [
                    "KullaniciID" => $UserID
                ]);
                if($Guncelle){
                    echo md5($KullaniciID);
                }  
            }
        }
        $Guncelle = $db->Update("Kullanici",
        [
            "$Column" => "$Veri", 
            "GuncelleyenKullaniciID" => $KullaniciID,
            "GuncellenmeTarihi" => time()
        ],
        [
            "KullaniciID" => $UserID
        ]);
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$UserID' olan kişinin '$Column' değerini '$Veri' olarak güncelledi",
            "Tarih" => time()
        ]);
    }
    // Hesap Dondurma
    if($ajax == 13){
        $UserId = clean($_POST["userId"]);
        $kod = clean($_POST["kod"]);
        
        if($kod == $_SESSION["HesapDondurmaKodu"]){
            $Kayit = $db->Update("Kullanici",
            [
                "Aktif" => "0",
                "GuncelleyenKullaniciID" => $KullaniciID,
                "GuncellenmeTarihi" => time()
            ],
            [
                "KullaniciID" => $UserId
            ]);
            
            $KayitLog = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$KullaniciID",
                "Islem" => "ID'si '$UserId' olan kişinin hesabı donduruldu",
                "Tarih" => time()
            ]);
            if($Kayit){
                unset($_SESSION['HesapDondurmaKodu']);
                echo true;
            }
        }else{
            echo "Hatalı kod!";
        }
    }
    // Hesap Silme
    if($ajax == 14){
        $UserId = clean($_POST["userId"]);
        $kod = clean($_POST["kod"]);
        
        if($kod == $_SESSION["HesapSilmeKodu"]){
            $Kayit = $db->Update("Kullanici",
            [
                "Silindi" => "1",
                "GuncelleyenKullaniciID" => $KullaniciID,
                "GuncellenmeTarihi" => time()
            ],
            [
                "KullaniciID" => $UserId
            ]);
            
            $KayitLog = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$KullaniciID",
                "Islem" => "ID'si '$UserId' olan kişinin hesabı silindi",
                "Tarih" => time()
            ]);
            if($Kayit){
                unset($_SESSION['HesapSilmeKodu']);
                echo true;
            }
        }else{
            echo "Hatalı kod!";
        }
    }
    // Hesap Açma 1. Aşama
    if($ajax == 15){
        $Email = clean($_POST["email"]);
        if($db->Row($db->Get("Kullanici")->Where("Email", $Email)) > 0){
            $kod = $_SESSION["HesapAcmaKodu"] = kod();
            $_SESSION["HesapAcmaID"] = $db->Get("Kullanici")->Where("Email", $Email, "KullaniciID");
            mailGonder(
                    $Email,
                    "Hesap Açma Kodunuz: $kod",
                    "Hesap açmak için gereken kodunuz: <b><h2>$kod</h2></b>"
                );
        }
    }
    // Hesap Açma 2. Aşama (Son)
    if($ajax == 16){
        $UserId = clean($_POST["userId"]);
        $kod = clean($_POST["kod"]);
        
        if($kod == $_SESSION["HesapAcmaKodu"]){
            $Kayit = $db->Update("Kullanici",
            [
                "Aktif" => "1",
                "GuncelleyenKullaniciID" => $UserId,
                "GuncellenmeTarihi" => time()
            ],
            [
                "KullaniciID" => $UserId
            ]);
            
            $KayitLog = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$UserId",
                "Islem" => "ID'si '$UserId' olan kişi hesabı tekrar aktif hale getirdi",
                "Tarih" => time()
            ]);
            if($Kayit){
                unset($_SESSION['HesapAcmaKodu']);
                echo true;
            }
        }else{
            echo "Hatalı kod!";
        }
    }
    // Hesap Yükleme 1. Aşama
    if($ajax == 17){
        $Email = clean($_POST["email"]);
        if($db->Row($db->Get("Kullanici")->Where("Email", $Email)) > 0){
            $kod = $_SESSION["HesapYuklemeKodu"] = kod();
            $_SESSION["HesapYuklemeID"] = $db->Get("Kullanici")->Where("Email", $Email, "KullaniciID");
            mailGonder(
                    $Email,
                    "Hesap Geri Yükleme Kodunuz: $kod",
                    "Hesap geri yüklemeniz için gereken kodunuz: <b><h2>$kod</h2></b>"
                );
        }
    }
    // Hesap Yükleme 2. Aşama (Son)
    if($ajax == 18){
        $UserId = clean($_POST["userId"]);
        $kod = clean($_POST["kod"]);
        
        if($kod == $_SESSION["HesapYuklemeKodu"]){
            $Kayit = $db->Update("Kullanici",
            [
                "Silindi" => "0",
                "GuncelleyenKullaniciID" => $UserId,
                "GuncellenmeTarihi" => time()
            ],
            [
                "KullaniciID" => $UserId
            ]);
            
            $KayitLog = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$UserId",
                "Islem" => "ID'si '$UserId' olan kişi silinmiş hesabını geri yükledi",
                "Tarih" => time()
            ]);
            if($Kayit){
                unset($_SESSION['HesapYuklemeKodu']);
                echo true;
            }
        }else{
            echo "Hatalı kod!";
        }
    }
    // E-Mail Doğrulama
    if($ajax == 19){
        $UserId = clean($_POST["userId"]);
        $kod = clean($_POST["kod"]);
        
        if($kod == $_SESSION["EMailDogrulamaKodu"]){
            if(isset($_SESSION["login"])){
                $Kayit = $db->Update("Kullanici",
                [
                    "EpostaDogrulandi" => "1",
                    "GuncellenmeTarihi" => time()
                ],
                [
                    "KullaniciID" => $_SESSION["loginID"]
                ]);
                unset($_SESSION['loginID']);
                unset($_SESSION['login']);
            }else{
                $Kayit = $db->Update("Kullanici",
                [
                    "EpostaDogrulandi" => "1",
                    "GuncelleyenKullaniciID" => $KullaniciID,
                    "GuncellenmeTarihi" => time()
                ],
                [
                    "KullaniciID" => $UserId
                ]);
                $KayitLog = $db->Insert("KullaniciLoglari",
                [
                    "KullaniciID " => "$KullaniciID",
                    "Islem" => "ID'si '$UserId' olan kişinin mail adresi onaylandı",
                    "Tarih" => time()
                ]);
                $Bildirim = $db->Insert("Bildirimler",
                [
                    "KullaniciID" => "0",
                    "AliciID" => "$KullaniciID",
                    "BildirimKategoriID" => "3",
                    "BildirimIcerik" => "$bugun tarihinde mail adresiniz doğrulandı",
                    "BildirimTarihi" => time()
                ]);
            }
            if($Kayit){
                unset($_SESSION['EMailDogrulamaKodu']);
                echo true;
            }
        }else{
            echo "Hatalı kod!";
        }
    }
    // Bildirim Okundu
    if($ajax == 20){
        $Kayit = $db->Update("Bildirimler",
        [
            "Okundu" => "1"
        ],
        [
            "AliciID" => $KullaniciID
        ]);
    }
    // Ürün Satın Al / Alışveriş / Sticker & Banner
    if($ajax == 21){
        $UrunID = clean($_POST["urunId"]);
        $UrunTack = $db->Get("Urunler")->Where("UrunID", $UrunID, "UrunTack");
        $UrunYeri = $db->Get("Urunler")->Where("UrunID", $UrunID, "UrunYeri");
        $UrunLink = $db->Get("Urunler")->Where("UrunID", $UrunID, "UrunLink");
        $UrunAdet = $db->Get("Urunler")->Where("UrunID", $UrunID, "UrunAdet") - 1;
        $KullaniciTack = $db->Get("Kullanici")->Where("KullaniciID", $KullaniciID, "Puan");
        
        if($KullaniciTack >= $UrunTack){
            $Alisveris = $db->Insert("Alisveris",
            [
                "KullaniciID " => "$KullaniciID",
                "UrunID" => "$UrunID",
                "Tarih" => time()
            ]);
            $UrunAdetGuncelle = $db->Update("Urunler", 
            [
                "UrunAdet" => $UrunAdet
            ],
            [
                "UrunID" => $UrunID
            ]);
            $Kalan = $KullaniciTack - $UrunTack;
            if($UrunYeri == 1){
                $PuanGuncelle = $db->Update("Kullanici", 
                [
                    "Puan" => $Kalan,
                    "Sticker" => "$UrunLink"
                ],
                [
                    "KullaniciID" => $KullaniciID
                ]);
            }else{
                $PuanGuncelle = $db->Update("Kullanici", 
                [
                    "Puan" => $Kalan,
                    "Banner" => "$UrunLink"
                ],
                [
                    "KullaniciID" => $KullaniciID
                ]);
            }
            /*$Bildirim = $db->Insert("Bildirimler",
            [
                "KullaniciID" => "0",
                "AliciID" => "$KullaniciID",
                "BildirimKategoriID" => "3",
                "BildirimIcerik" => "$bugun tarihinde satın alımınız onaylanmıştır",
                "BildirimTarihi" => time()
            ]);*/
            $KayitLog = $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$KullaniciID",
                "Islem" => "ID'si '$UrunID' olan ürünü satın aldı",
                "Tarih" => time()
            ]);
            echo true;
        }
    }
    // Koleksiyon Değiştirme
    if($ajax == 22){
        $Veri = clean($_POST["veri"]);
        $Kayit = $db->Update("Kullanici",
        [
            "Sticker" => "$Veri"
        ],
        [
            "KullaniciID" => $KullaniciID
        ]);
        echo true;
    }
    // Bildirim Okundu İşaretle
    if($ajax == 23){
        $Kayit = $db->Update("Bildirimler",
        [
            "Okundu" => true
        ],
        [
            "AliciID" => $KullaniciID
        ]);
        echo true;
    }
    // Yorum Cevap Silme
    if($ajax == 24){
        $YorumID = clean($_POST["yorumId"]);
        $Sil = $db->Delete("YorumCevap", ["YorumCevapID" => $YorumID]);
        $Sil = $db->Delete("Bildirimler", ["YorumCevapID" => $YorumID]);
        $Sil = $db->Delete("Bildirimler", ["YorumCevaplaID" => $YorumID]);
        $Sil = $db->Delete("YorumCevapla", ["YorumCevaplaYorumID" => $YorumID]);
    }
    // Yorum Cevap Ekleme
    if($ajax == 25){
        $YorumID = clean($_POST["yorumId"]);
        $IcerikID = clean($_POST["icerikId"]);
        $Yorum = nl2br($_POST["yorum"]);
        
        preg_match_all('/@([\p{L}0-9_.]+)/u', $Yorum, $matches);
        $usernames = array_unique($matches[1]);
        
        $allUsers = $db->Get("Kullanici", true); 
        
        $validUsernames = [];
        foreach ($allUsers as $user) {
            if (in_array($user['KullaniciAdi'], $usernames)) {
                $validUsernames[] = $user['KullaniciAdi'];
            }
        }
        
        $etiketlenenKullanicilar = [];
        
        $Yorum = preg_replace_callback(
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
            $Yorum
        );

        $Kayit = $db->Insert("YorumCevap",
        [
            "YorumCevapKullaniciID " => "$KullaniciID",
            "YorumCevapYorumID" => "$YorumID",
            "YorumCevapTarih" => time(),
            "YorumCevapMetin" => mb_convert_encoding($Yorum,  'UTF-8', 'auto')
        ]);
        
        if (!empty($etiketlenenKullanicilar)) {
            foreach ($etiketlenenKullanicilar as $etiketliKullanici) {
                $etiketli = $db->Get("Kullanici")->Where("KullaniciAdi", $etiketliKullanici, "KullaniciID");
                
                if ($etiketli == $KullaniciID) continue;
        
                $db->Insert("Bildirimler", [
                    "KullaniciID" => $KullaniciID,
                    "AliciID" => $etiketli,
                    "BildirimKategoriID" => "7",
                    "BildirimIcerikID" => "$IcerikID,$Kayit",
                    "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> bir yorumun cevabında sizden bahsetti: \"$Yorum\"",
                    "BildirimTarihi" => time(),
                    "BildirimIcerikCevapID" => "$IcerikID",
                    "YorumCevapID" => $Kayit
                ]);
            }
        }
        
        echo $Kayit;
    }
    // Anket Cevaplama
    if($ajax == 26){
        $Veri = clean($_POST["veri"]);
        $Anket = clean($_POST["anket"]);
        $Kayit = $db->Insert("AnketCevaplari", [
            "AnketID" => "$Anket",
            "CevapMetni" => "$Veri",
            "CevapKullanici" => "$KullaniciID"
        ]);
        echo true;
    }
    // Koleksiyon Değiştirme
    if($ajax == 27){
        $Veri = clean($_POST["veri"]);
        $Kayit = $db->Update("Kullanici",
        [
            "Banner" => "$Veri"
        ],
        [
            "KullaniciID" => $KullaniciID
        ]);
        echo true;
    }
    // Kullanıcı Hesap Dondurma (Yetkili)
    if($ajax == 28){
        $kullaniciID = clean($_POST["kullaniciID"]);
        $veri = clean($_POST["veri"]);
        $Kayit = $db->Update("Kullanici",
        [
            "Aktif" => "$veri"
        ],
        [
            "KullaniciID" => $kullaniciID
        ]);
        echo true;
    }
    // Post Sabitle
    if($ajax == 29){
        $IcerikID = clean($_POST["IcerikID"]);
        $Durum = clean($_POST["Durum"]);

        $db->Update("Icerikler", 
        [
            "Sabit" => $Durum
        ],
        [
            "IcerikID" => $IcerikID
        ]);

        if($Durum == true){
            $Durum = "içeriği sabitledi";
            echo 0;
        }else{
            $Durum = "içeriğin sabitlemesini kaldırdı";
            echo 1;
        }
        $KayitLog = $db->Insert("KullaniciLoglari",
        [
            "KullaniciID " => "$KullaniciID",
            "Islem" => "ID'si '$IcerikID' olan $Durum",
            "Tarih" => time()
        ]);
    }
    // Yorum Cevap Cevaplama
    if($ajax == 30){
        $YorumID = clean($_POST["yorumId"]);
        $IcerikID = clean($_POST["icerikId"]);
        $Yorum = nl2br($_POST["yorum"]);
        
        preg_match_all('/@([\p{L}0-9_.]+)/u', $Yorum, $matches);
        $usernames = array_unique($matches[1]);
        
        $allUsers = $db->Get("Kullanici", true); 
        
        $validUsernames = [];
        foreach ($allUsers as $user) {
            if (in_array($user['KullaniciAdi'], $usernames)) {
                $validUsernames[] = $user['KullaniciAdi'];
            }
        }
        
        $etiketlenenKullanicilar = [];
        
        $Yorum = preg_replace_callback(
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
            $Yorum
        );

        $Kayit = $db->Insert("YorumCevapla",
        [
            "YorumCevaplaKullaniciID " => "$KullaniciID",
            "YorumCevaplaYorumID" => "$YorumID",
            "YorumCevaplaTarih" => time(),
            "YorumCevaplaYorum" => mb_convert_encoding($Yorum,  'UTF-8', 'auto')
        ]);
        
        if (!empty($etiketlenenKullanicilar)) {
            foreach ($etiketlenenKullanicilar as $etiketliKullanici) {
                $etiketli = $db->Get("Kullanici")->Where("KullaniciAdi", $etiketliKullanici, "KullaniciID");
                
                if ($etiketli == $KullaniciID) continue;
        
                $db->Insert("Bildirimler", [
                    "KullaniciID" => $KullaniciID,
                    "AliciID" => $etiketli,
                    "BildirimKategoriID" => "8",
                    "BildirimIcerikID" => "$IcerikID,$Kayit",
                    "BildirimIcerik" => "<a href='u/$KullaniciAdi'>$KullaniciAdi</a> bir yorumun cevabında sizden bahsetti: \"$Yorum\"",
                    "BildirimTarihi" => time(),
                    "BildirimIcerikCevapID" => "$IcerikID",
                    "YorumCevaplaID" => $Kayit,
                    "YorumCevapID" => $Kayit
                ]);
            }
        }
        echo $Kayit;
    }
    // Yorum Cevap Silme
    if($ajax == 31){
        $YorumID = clean($_POST["yorumId"]);
        $Sil = $db->Delete("YorumCevapla", ["YorumCevaplaID" => $YorumID]);
        $Sil = $db->Delete("Bildirimler", ["YorumCevaplaID" => $YorumID]);
    }
    // Kullanıcı Hesap Dondurma (Yetkili)
    if($ajax == 32){
        $yetkiID = clean($_POST["yetkiID"]);
        $kullaniciID = clean($_POST["kullaniciID"]);
        $Kayit = $db->Update("Kullanici",
        [
            "Yetki" => "$yetkiID",
            "GuncelleyenKullaniciID" => $KullaniciID,
            "GuncellenmeTarihi" => time()
        ],
        [
            "KullaniciID" => $kullaniciID
        ]);
    }
}else{
    header("location: ./");
}
?>