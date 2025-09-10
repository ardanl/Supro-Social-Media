<title><?= $SiteAdi; ?> - Gönderi Paylaş</title>
<?php
if(($KullaniciID)){
    if(isset($_POST["Paylas"])){ ?>
    <script>
        $("#PaylasBtn").prop("disabled", true);
    </script>
        <?php 
        $IcerikMetni = nl2br($_POST["IcerikMetni"]);
        $etiketlenenKullanicilar = [];
        
        preg_match_all('/@([\p{L}0-9_.]+)/u', $IcerikMetni, $matches);
        $usernames = array_unique($matches[1]);
        
        $allUsers = $db->Get("Kullanici", true); 
        
        $validUsernames = [];
        foreach ($allUsers as $user) {
            if (in_array($user['KullaniciAdi'], $usernames)) {
                $validUsernames[] = $user['KullaniciAdi'];
            }
        }
        
        $etiketlenenKullanicilar = [];
        
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
        
        $duzenli = '/(https:\/\/[^\s]+)/i';
        $IcerikMetni = preg_replace_callback($duzenli, function($eslesme) {
            $link = htmlspecialchars($eslesme[0]);
            return '<a href="' . $link . '" target="_blank">' . $link . '</a>';
        }, $IcerikMetni);
        if(isset($_POST["SelectKategoriID"])){
            $KategoriID = $_POST["SelectKategoriID"];
        }else{
            $KategoriID = 22;
        }
        $time = time();
            $IcerikResim = $_FILES["IcerikResim"]["name"];
            if ($IcerikResim != "") {
                $uzanti = pathinfo($IcerikResim, PATHINFO_EXTENSION);
                $adlandir = $KullaniciAdi . "_" . $time .uniqid() . "." . $uzanti;;
                move_uploaded_file($_FILES['IcerikResim']['tmp_name'],'images/' . $adlandir);
                
                resizeImage("images/$adlandir", "images/$adlandir", 700, 700);
                
                $Puan = $db->Get("Kullanici")->Where("KullaniciID", $KullaniciID, "Puan");
                $Kayit = $db->Insert("Icerikler",
                                    ["IcerikMetni" => "$IcerikMetni",
                                    "IcerikResim" => "$adlandir",
                                    "YazarID" => $KullaniciID,
                                    "YayimTarihi" => $time,
                                    "KategoriID" => $KategoriID
                                    ]);
            }else{
                $Kayit = $db->Insert("Icerikler",
                                    ["IcerikMetni" => "$IcerikMetni",
                                    "YazarID" => $KullaniciID,
                                    "YayimTarihi" => $time,
                                    "KategoriID" => $KategoriID
                                    ]);
            }
            
            if (strpos($IcerikMetni, '@everyone') !== false && $Yetki == 1 || strpos($IcerikMetni, '@everyone') !== false && $Yetki == 6 ) {
                // Tüm aktif kullanıcıları al
                $tumKullanicilar = $db->Get("Kullanici")->Where("Aktif", 1);
            
                foreach ($tumKullanicilar as $kullanici) {
                    // Kendine bildirim gönderme
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
                // Normal kullanıcı etiketleme bildirimleri
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
        
            $GonderiRandom = $Puan + rand(1, 50);
            $PuanGuncelle = $db->Update("Kullanici", 
            [
                "Puan" => $GonderiRandom
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
            $db->Insert("KullaniciLoglari",
            [
                "KullaniciID " => "$KullaniciID",
                "Islem" => "ID'si '$Kayit' olan içeriği yayınladı",
                "Tarih" => time()
            ]);
            // --- Supro AI cevabı ---
            if (in_array('Supro', $etiketlenenKullanicilar)) {
                $orjinalIcerik = trim(html_entity_decode(strip_tags($_POST["IcerikMetni"]), ENT_QUOTES, 'UTF-8'));

                // OpenAI API key (güvenli kullanım için .env veya direkt değişken)
                $openaiKey = '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';

                $payload = [
                    "model" => "gpt-4o-mini",
                    "messages" => [
                        [
                            "role" => "system",
                            "content" => "Sen @Supro hesabısın. Türkçe yaz. 1-2 cümle, net ve samimi. Asla tek başına emoji gönderme. En az 1 kelime yazı olmalı. Gerekirse 1 emoji yazının içinde olabilir."
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

                if ($result === false || $http >= 400) {
                    error_log("Supro AI error ($http): $curlErr | raw: ".substr((string)$result,0,500));
                    $suproCevap = "Yakalamana sevindim, detayları konuşalım. 🙂";
                } else {
                    $data = json_decode($result, true);
                    $suproCevap = trim($data['choices'][0]['message']['content'] ?? '');

                    // "Supro:" gibi önekleri temizle
                    $suproCevap = preg_replace('/^\s*@?supro[:\-\s]*/iu', '', $suproCevap);

                    // Sadece emoji ise fallback
                    if (preg_match('/^\p{So}+$/u', $suproCevap) || $suproCevap === '') {
                        $suproCevap = "Yakalamana sevindim, detayları konuşalım. 🙂";
                    }

                    // Çok uzunsa kırp
                    if (mb_strlen($suproCevap) > 240) {
                        $suproCevap = mb_substr($suproCevap, 0, 240) . "…";
                    }
                }

                // DB'ye ekle
                $suproID = 3; // Supro kullanıcı ID
                $KayitYorumOpenAI = $db->Insert("Yorumlar", [
                    "KullaniciID"  => $suproID,
                    "IcerikID"     => $Kayit,
                    "YorumIcerigi" => htmlspecialchars($suproCevap, ENT_QUOTES, 'UTF-8'),
                    "YorumTarihi"  => time()
                ]);
            }
            header("location: post/$time-$KullaniciID");
        
    }
?>
<form method="post" enctype="multipart/form-data" class="container" style="position: absolute;left: 0;right: 0;top: 0;bottom: 0;margin: auto;height: max-content;">
    <h5>@<?php echo $KullaniciAdi; ?> olarak gönderi paylaş</h5>
    <textarea name="IcerikMetni" class="form-control color" rows=4 id="IcerikMetni"></textarea>
      <ul class="dropdown-menu" id="dropdown" style="overflow-y: scroll;">
          <?php
          if($Yetki == 1 || $Yetki == 6){
            echo '<li><a class="dropdown-item" href="#">everyone</a></li>';
          }
          $Uyeler = $db->Get("Kullanici")->OrderBy("KullaniciAdi", "ASC")->Where("Aktif", "1");
          foreach($Uyeler as $Uye){ ?>
              <li><a class="dropdown-item" href="#"><?= $Uye["KullaniciAdi"]; ?></a></li>
          <?php }
          ?>
        
      </ul>
    <input name="IcerikResim" type="file" class="form-control mt-3 color form-control-sm" accept=".png, .jpg, .jpeg, .webp, .heif, .raw, .gif, .mp4, .hevc, .mov, .avi">
    <small class="text-muted">Videolar maksimum 20 saniye olmalıdır!</small>
    <select class="form-control mt-3 color form-control-sm" name="SelectKategoriID">
        <option value=22>Kategori Seçiniz</option>";
    <?php
        $Kategoriler = $db->Get("Kategoriler")->OrderBy("KategoriAdi", "ASC")->Where("Aktif", true);
        foreach($Kategoriler as $Kategori){
            echo "<option value='$Kategori[KategoriID]'>$Kategori[KategoriAdi]</option>";
        }
    ?>
    </select>
    <small class="text-muted">Lütfen Paylaş butonuna bastığınızda bekleyiniz!</small>
    <!--<a type="button" data-bs-toggle="modal" data-bs-target="#kategoriBilgiModal">
      Kategoriler hakkında daha fazla bilgi al
    </a>
    <div class="modal fade" id="kategoriBilgiModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">Tüm Kategoriler</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul>
                 <?php
                    $Kategoriler = $db->Get("Kategoriler")->OrderBy("KategoriAdi", "ASC")->Where("Aktif", true);
                    foreach($Kategoriler as $Kategori){ ?>
                        <li>
                            <?= $Kategori["KategoriAdi"]; ?>
                            <ul>
                                <li>
                                    <?= $Kategori["Aciklama"]; ?>
                                </li>
                            </ul>
                        </li>
                    <?php }
                ?>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Tamam</button>
          </div>
        </div>
      </div>
    </div>-->
    <div class="text-center">
        <button type="submit" name="Paylas" id="PaylasBtn" class="btn btn-sm btn-primary mt-2 col-8">Paylaş</button>
    </div>
</form>
<?php
}else{
    header("location: ./");
}
?>