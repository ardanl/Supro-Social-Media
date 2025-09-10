<?php
if (isset($k[1])) {
    $U = $db->Get("Kullanici")->Where("KullaniciAdi", clean($k[1]));
    if ($db->Row($U) > 0) {
        $U = $U[0];
        if ($U["Aktif"] == 1 && $U["Silindi"] == 0 || $Yetki < 3 ) { ?>
            <title><?= $SiteAdi . " ●  @" . $k[1]; ?></title>
            <div class="d-flex align-items-center justify-content-center dark my-3 container">
                <div class="card p-4 shadow color m-1 col-12 pb-4" style="padding-top: 5.5rem!important;">
                    <div style="position: absolute; left: 0; top: 0; height: 8rem; background-image: url(../images/banner/<?= $U["Banner"]; ?>); background-size: cover; background-position: center;" class="card-img-top"></div>
                    <?php if($KullaniciID == $U["KullaniciID"]){ ?>
                    <button type="button" class="btn btn-sm p-0 position-absolute" style="top: 0.2rem; left: 0.4rem;" data-bs-toggle="modal" data-bs-target="#banners">
                        <i class="p-0 fi fi-sr-settings text-white fs-4"></i>
                    </button>
                    <a href="<?= $Link; ?>exit" style="top: 0.2rem; right: 0.4rem;" class="position-absolute"><i class="fi text-white fs-4 fi-sr-user-logout"></i></a>
                    <div class="modal fade" id="banners" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                          <div class="modal-header py-1">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Banner Koleksiyonum</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                                <ul class="p-0 row row-cols-2 justify-content-center g-1 mb-0">
                                    <?php
                                        $AlisverisSql = $db->Get("Alisveris")->OrderBy("Tarih", "DESC")->Where("KullaniciID", $KullaniciID);
                                        if($db->Row($AlisverisSql) > 0){ 
                                        foreach($AlisverisSql as $Alisveris){
                                            $Urun = $db->Get("Urunler")->Where("UrunID", $Alisveris["UrunID"]);
                                            $Urun = $Urun[0];
                                            if($Urun["UrunYeri"] == 2){
                                        ?>
                                        <div class="col h-100">
                                            <input type="radio" class="btn-check" name="options-outlinedd" id="urun<?= $Urun["UrunID"]; ?>" autocomplete="off" <?php if($U["Banner"] != "" && $U["Banner"] == $Urun["UrunLink"]){ echo "checked"; } ?>>
                                            <label data-bs-dismiss="modal" class="w-100 h-100 tek-satir btn-sm btn btn-outline-light my-1 koleksiyon-banner" data-veri="<?= $Urun["UrunLink"]; ?>" for="urun<?= $Urun["UrunID"]; ?>">
                                                <?= $Urun["UrunAdi"]; ?>
                                            </label>
                                        </div>
                                       <?php } } }else{
                                            echo '<li class="list-group-item text-center">Koleksiyon boş...</li>';
                                        }
                                        ?>
                                        <div class="col h-100">
                                            <input type="radio" class="btn-check" name="options-outlinedd" id="urun0" autocomplete="off" <?php if($U["Banner"] != "" && $U["Banner"] == "banner-classic.jpeg"){ echo "checked"; } ?>>
                                            <label data-bs-dismiss="modal" class="w-100 h-100 tek-satir btn-sm btn btn-outline-light my-1 koleksiyon-banner" data-veri="banner-classic.jpeg" for="urun0">
                                                Klasik
                                            </label>
                                        </div>
                                </ul>
                          </div>
                          <div class="modal-footer">
                            <a href="../alisveris" class="btn btn-sm text-light col-4 g-kakao"><i class="fi fi-ts-store-alt"></i>Mağaza</a>
                            <button type="button" class="btn btn-sm btn-primary col-4" data-bs-dismiss="modal">Tamam</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    <!--<p class="text-warning position-absolute" style="left: 15px;top:15px;">
                        <?=  '<i class="fi pe-2 fi-rr-' . $db->Get("Yetkiler")->Where("YetkiID", $U["Yetki"], "Icon") . '"></i>' . $db->Get("Yetkiler")->Where("YetkiID", $U["Yetki"], "YetkiAdi"); ?>
                    </p>-->
                        <?php
                        if ($U["Yetki"] != 6) { 
                        if($KullaniciID == $U["KullaniciID"]){ ?>
                            <div style="left: 15px;top:8.2rem;" class="fw-bold d-flex align-items-center position-absolute">
                                <a href="../alisveris" class="<?php if($U["Color"] && $U["Onaylandi"] == true){ echo "tick " . $U["Color"]; }else{ echo "text-light"; } ?>">
                                    <img src="../images/scoin.png" width="25">
                                    <!--<i class="fi fi-sr-cryptocurrency fs-4 p-0"></i>-->
                                    <span style="padding-left: 3px;" class="align-middle"><?= $U["Puan"];  ?></span>
                                </a>
                            </div>
                        <?php } } 
                        if($Yetki == 1 && $KullaniciID != $U["KullaniciID"]){ ?>
                            <p style="left: 0px;top:8.2rem;" class="position-absolute">
                            <?= date("d.m.Y", $U["SonGorunme"]); ?>
                            <br>
                            <?= date("H:i", $U["SonGorunme"]); ?>
                            </p>
                        <?php } ?>
                    <p style="right: 15px;top:8.2rem;" class="position-absolute">
                        <?php
                        if ($KullaniciID) {
                            if($U["Yetki"] == 6){
                                $Color = $U["Color"]; ?>
                                <!--<span class="<?php if($U["Onaylandi"]){ echo "tick " . $U["Color"]; }else{ echo "text-light"; } ?>">● Çevrimiçi</span>-->
                            <?php }else{
                                if (empty($U["SonGorunme"])) {
                                    $SonGorunme = 0;
                                } else {
                                    $SonGorunme = $U["SonGorunme"];
                                }
                                $SonGorunmeDate = date("d.m.Y H:i:s", $SonGorunme);

                                $date1 = new DateTime($SonGorunmeDate);
                                $date2 = new DateTime();

                                $diff = $date1->diff($date2);

                                $minutes = ($diff->h * 60) + ($diff->i) + ($diff->d * 24 * 60);
                                $hours = $diff->h + ($diff->d * 24);
                                $days = $diff->days;
                                if ($U["SonGorunme"] + (60 * 2) >= time()) { ?>
                                    <!--<span class="<?php if($U["Onaylandi"]){ echo "tick " . $U["Color"]; }else{ echo "text-light"; } ?>">● Çevrimiçi</span>-->
                                <?php } else { ?>
                                    <span class="<?php if($U["Onaylandi"]){ echo "tick " . $U["Color"]; }else{ echo "text-light"; } ?>">
                                    <?php if ($SonGorunme == 0 && $Yetki < 3) {
                                        echo "Çevrimdışı";
                                    } else {
    
                                        if($Yetki < 3 && $Yetki < $U["Yetki"]){
                                            if ($days > 0) {
                                                echo "● {$days} gün önce";
                                            } elseif ($hours > 0) {
                                                echo "● {$hours} saat önce";
                                            } elseif ($minutes > 0) {
                                                echo "● {$minutes} dk önce";
                                            }
                                        }
                                    }
                                    echo "</span>";
                                }
                            }
                        }
                        ?>
                    </p>
                    <?php if (($KullaniciID) && $U["KullaniciID"] != $KullaniciID && $Yetki != 6 && $Yetki > 2) { ?>
                        <button type="button" class="btn text-white"
                            style="display: inline;width: max-content;float: right;position: absolute;right: 0;top: 0rem;" data-bs-toggle="modal" data-bs-target="#sikayet">
                            <i class="fi fi-ts-brake-warning fs-3"></i>
                        </button>
                        <div class="modal fade" id="sikayet" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header py-1 text-white">
                                        <h1 class="modal-title fs-5"><?= $U["KullaniciAdi"]; ?>'ı şikayet et!</h1>
                                        <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                                    </div>
                                    <div class="modal-body text-white">
                                        <label>Şikayet Konusu</label>
                                        <select class="form-control color-modal">
                                            <?php
                                            $SikayetKategorileri = $db->Get("SikayetKategorileri")->Where("Aktif", 1);
                                            foreach ($SikayetKategorileri as $Kategori) {
                                                echo '<option value="' . $Kategori["KategoriID"] . '">' . $Kategori["KategoriAdi"] . ' - ' . $Kategori["Aciklama"] . '</option>';
                                            }
                                            ?>t
                                        </select>
                                        <label class="mt-2">Şikayetiniz</label>
                                        <textarea class="form-control color-modal" rows=3></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-color col-3" data-bs-dismiss="modal">İptal</button>
                                        <button type="button" class="btn btn-sm btn-color col-3">Gönder</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if($Yetki == 6 || $Yetki < 3 && $Yetki < $U["Yetki"] && $KullaniciID != $U["KullaniciID"]){ ?>
                    <button type="button" class="btn text-white"
                    style="
                        display: inline;
                        width: max-content;
                        float: right;
                        position: absolute;
                        right: 0;
                        top: 0;" data-bs-toggle="modal" data-bs-target="#hesapdondur">
                    <i class="fi fi-ts-dot-pending fs-3"></i>
                    </button>
                    <div class="modal fade" id="hesapdondur" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header text-white py-1">
                            <h1 class="modal-title fs-5">Hesap Hakkında</h1>
                            <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                        </div>
                            <div class="modal-body text-white">
                                <div class="mt-2">
                                    <ul class="list-group mb-2">
                                        <li class="list-group-item">Doğum Tarihi: <?= date("d.m.Y", $U["DogumTarihi"]); ?></li>
                                        <li class="list-group-item">Cinsiyet: <?= cinsiyet($U["Cinsiyet"]); ?></li>
                                        <li class="list-group-item">Telefon: <a href="tel:0<?= $U["TelefonNumarasi"]; ?>">0<?= $U["TelefonNumarasi"]; ?></a></li>
                                        <li class="list-group-item">E-Mail: <a href="mailto:0<?= $U["Email"]; ?>"><?= $U["Email"]; ?></a></li>
                                        <li class="list-group-item">Coin: <?= $U["Puan"]; ?></li>
                                        <li class="list-group-item">Kayıt Kodu: <?php
                                        $KisiKayitKod = $db->Get("KayitKod")->Where("KayitKodKullaniciID", $U["KullaniciID"], "KayitKodNo");
                                        if($db->Row($KisiKayitKod)>0){
                                            echo $KisiKayitKod;
                                            echo " (".$db->Row($db->Get("Kullanici")->Where("KayitKod", $KisiKayitKod)).")";
                                        }
                                        ?></li>
                                    </ul>
                                    <ul class="list-group mb-2">
                                        <li class="list-group-item">Davet Kodu: 
                                        <?php 
                                            $KayitKodu = $U["KayitKod"]; 
                                            if($KayitKodu != ""){
                                            $KayitKoduKullaniciID = $db->Get("KayitKod")->Where("KayitKodNo", $KayitKodu, "KayitKodKullaniciID");
                                            $KayitKoduKullaniciAdi = $db->Get("Kullanici")->Where("KullaniciID", $KayitKoduKullaniciID, "KullaniciAdi");
                                            echo $KayitKodu . " - " . "<a href='".$KayitKoduKullaniciAdi."'>@".$KayitKoduKullaniciAdi."</a>"; 
                                            }else{
                                                echo "-";
                                            }
                                        ?></li>
                                        <li class="list-group-item">
                                            Kayıt Tarihi: <?= date("d.m.Y H:i:s", $U["KayitTarihi"]); ?>
                                            (<?= floor((time() - $U["KayitTarihi"]) / 86400); ?> gün)
                                        </li>
                                        <li class="list-group-item">Son Giriş: <?= date("d.m.Y H:i:s", $U["SonGiris"]); ?></li>
                                        <li class="list-group-item">Başarısız Giriş: <?= $U["BasarisizGirisDenemesi"]; ?></li>
                                        <li class="list-group-item">Aktif: <?= $U["Aktif"] == 1 ? "✅ Evet" : "❌ Hayır"; ?></li>
                                        <li class="list-group-item">E-Mail Doğrulandı: <?= $U["EpostaDogrulandi"] == 1 ? "✅ Evet" : "❌ Hayır"; ?></li>
                                    </ul>
                                    <ul class="list-group mb-2">
                                        <li class="list-group-item">IP Adresi: <a target="_blank" href="https://www.ipsorgu.com/?ip=<?= $U['IpAdresi']; ?>#sorgu"><?= $U["IpAdresi"]; ?></a></li>
                                        <li class="list-group-item">Cihaz Türü: <?= $U["CihazTur"]; ?></li>
                                        <li class="list-group-item">Tarayıcı: <?= $U["Tarayici"]; ?></li>
                                        <li class="list-group-item">İşletim Sistemi: <?= $U["IsletimSistemi"]; ?></li>
                                        <li class="list-group-item">UserAgent: <?= $U["UserAgent"]; ?></li>
                                    </ul>   
                                    <ul class="list-group mb-2">
                                        <li class="list-group-item">Son Güncelleme: <?= date("d.m.Y H:i:s", $U["GuncellenmeTarihi"]); ?></li>
                                        <li class="list-group-item">Güncelleyen Kullanıcı: <?php 
                                        if($U["GuncelleyenKullaniciID"] != ""){
                                            $GuncelleyenKullaniciAdi = $db->Get("Kullanici")->Where("KullaniciID", $U["GuncelleyenKullaniciID"], "KullaniciAdi");
                                            echo '<a href="'.$GuncelleyenKullaniciAdi.'">@'.$GuncelleyenKullaniciAdi.'</a>';
                                        }else{
                                            echo "-";
                                        }
                                        ?></li>
                                    </ul>   
                                </div>
                                <hr>    
                                <div class="mt-2">
                                    <label>Yetki:</label>
                                    <select id="yetkiSelect" class="form-control color">
                                        <?php
                                        $Yets = $db->Get("Yetkiler")->OrderBy("YetkiID", "ASC")->Where("Aktif", true);
                                        foreach($Yets as $Yet){
                                            if($Yetki <= $Yet["YetkiID"] && $Yet["YetkiID"] != $Yetki && $Yet["YetkiID"] != 5 && $Yet["YetkiID"] != 6){
                                             ?>
                                            <option 
                                            data-yetki-adi="<?= $Yet['YetkiAdi']; ?>"
                                            data-yetki-color="<?= $Yet['Color']; ?>"
                                            data-yetki-icon="<?= $Yet['Icon']; ?>"
                                            data-bs-dismiss="modal"  aria-label="Close"
                                            data-kullanici-id="<?= $U['KullaniciID']; ?>" 
                                            value="<?= $Yet["YetkiID"]; ?>" 
                                            <?php if($U["Yetki"] == $Yet["YetkiID"]) echo "selected"; ?>>
                                                <?= $Yet["YetkiAdi"]; ?>
                                            </option>
                                        <?php } } ?>
                                    </select>
                                </div>
                                <hr>
                                <div class="mt-2 row justify-content-around">
                                    <a class="btn btn-sm btn-danger col-11" style="display: none;" id="HesapDondurBtn" data-veri=<?php if($U["Aktif"] == true){ echo "0"; }else{ echo "1"; }  ?> data-id="<?= $U["KullaniciID"]; ?>"><i class="fi fi-ts-check-circle"></i>Emin misiniz!</a>
                                    <a class="btn btn-sm btn-danger col-11" href="javascript:void(0);" onclick="$('#HesapDondurBtn').show(); $(this).remove();"><i class="fi fi-rr-user-forbidden-alt"></i>Hesabı <?php if($U["Aktif"] == true){ echo " dondur"; }else{ echo " aktive et"; } ?>!</a>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary col-3" data-bs-dismiss="modal">İptal</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    <?php } ?>
                    <div class="text-center cursor-pointer position-relative" style="z-index: 1;">
                        <?php
                        if ($U["ProfilResmi"] != "") {
                            if($U["Sticker"]){
                                echo '<img class="profil-sticker" src="../images/sticker/'.$U["Sticker"].'.png">';
                            }
                            if($U["ProfilImageDesign"]){
                                echo '<img src="../images/circle/'.$U["ProfilImageDesign"].'.png" style="position: absolute;left: 0;right: 0;margin: auto;height: 5rem;top: 2rem;">';
                            }
                            echo '<img class="rounded-circle" style="width: 6rem;height: 6rem;background-image: url(../images/' . $U["ProfilResmi"] . ');background-position: center;background-size: cover;" data-bs-toggle="modal" data-bs-target="#ProfilResmi">';
                        }
                        if ($U["SonGorunme"] + (60 * 2) >= time()) { 
                            echo '<i class="fi fi-ss-circle text-teal" style="position: absolute; inset: 0; top: 4.5rem; left: -4rem;"></i>';
                         }
                        if($Yetki > 1 && $KullaniciID != $U["KullaniciID"]){
                            if($U["Yetki"] != 6){
                                if ($U["SonGorunme"] == 0 || $days > 0) {
                                    echo '<i class="fi fi-ss-circle text-danger" style="position: absolute; inset: 0; top: 4.5rem; left: -4rem;"></i>';
                                } elseif ($hours > 0) {
                                    echo '<i class="fi fi-ss-circle text-warning" style="position: absolute; inset: 0; top: 4.5rem; left: -4rem;"></i>';
                                } elseif ($minutes > 0) {
                                    echo '<i class="fi fi-ss-circle text-teal" style="position: absolute; inset: 0; top: 4.5rem; left: -4rem;"></i>';
                                }
                            }else{
                                echo '<i class="fi fi-ss-circle text-teal" style="position: absolute; inset: 0; top: 4.5rem; left: -4rem;"></i>';
                            }
                        }
                        if($KullaniciID == $U["KullaniciID"]){ ?>
                            <span class="position-absolute translate-middle badge rounded-pill bg-success" style="top: 4.7rem;height: 1.6rem;" data-bs-toggle="modal" data-bs-target="#ProfilResmi"><i data-bs-toggle="modal" data-bs-target="#ProfilResmi" class="fi fi-sr-pencil p-0"></i></span>
                        <?php }
                        ?>
                    </div>
                    <?php
                    if ($KullaniciID && $U["KullaniciID"] == $KullaniciID) {
                        $KontrolFotograf = true;
                        if (isset($_POST["ResimKaydet"])) {
                            $IcerikResim = $_FILES["ProfilResimInput"]["name"];
                            $adlandir = preg_replace('/[^a-zA-Z0-9_-]/', '', $U["KullaniciAdi"]) . "_" . time() . ".jpg";
                            $hedef = 'images/' . $adlandir;
                            if (move_uploaded_file($_FILES['ProfilResimInput']['tmp_name'], $hedef)) {
                                //echo "Dosya yüklendi: $hedef";
                            } else {
                                //echo "Dosya taşınamadı!";
                            }
                            $uploadPath = __DIR__ . "/images/$adlandir";
                            resizeImage("$uploadPath", "$uploadPath", 500, 500);

                            $Kayit = $db->Update(
                                "Kullanici",
                                [
                                    "GuncelleyenKullaniciID" => "$KullaniciID",
                                    "GuncellenmeTarihi" => time(),
                                    "ProfilResmi" => $adlandir
                                ],
                                [
                                    "KullaniciID" => $U["KullaniciID"]
                                ]
                            );
                            if ($U["ProfilResmi"] != "" && $U["ProfilResmi"] != "img0.png") {
                                unlink('images/' . $U["ProfilResmi"]);
                            }
                            if ($KontrolFotograf) {
                                $Mesaj = "Profil fotoğrafını güncelledi";
                            } else {
                                $Mesaj = "ID'si '$U[KullaniciID]' olan kullanıcının profil fotoğrafını güncelledi";
                            }
                           
                            $db->Insert(
                                "KullaniciLoglari",
                                [
                                    "KullaniciID " => "$KullaniciID",
                                    "Islem" => $Mesaj,
                                    "Tarih" => time()
                                ]
                            );
                            header("refresh: 0");
                        }
                    ?>
                        <div class="modal fade" id="ProfilResmi" tabindex="-1" aria-labelledby="profilResmiLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="post" enctype="multipart/form-data" class="modal-content">
                                    <div class="modal-header py-1">
                                        <h1 class="modal-title fs-5" id="profilResmiLabel">Profil Resmi Değiştir</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input required name="ProfilResimInput" type="file" class="form-control form-control-sm my-3 color-modal" accept=".png, .jpg, .jpeg, .webp, .heif, .raw">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="ResimKaydet" id="ResimKaydetBtn" class="btn btn-sm btn-primary col-8">Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php }
                    ?>
                    <h3 class="text-center fw-bold text-white mb-0">
                        <?php
                        if ($U["Onaylandi"] == true) { 
                            echo "<span class='tick $U[Color]'>@".$U["KullaniciAdi"]."</span>"; 
                        }else{
                            echo "<span>@".$U["KullaniciAdi"]."</span>";
                        }
                            if ($U["Onaylandi"] == true) {
                                if($U["Color"]){
                                    $Color = $U["Color"];
                                    echo '<i class="fi tick fi-ss-badge-check ps-1 ' . $Color . '"></i>';
                                }else{
                                    echo '<i class="fi fi-ss-badge-check text-primary ps-1"></i>';
                                }
                            } ?> </h3>
                    <p class="text-white fs-5" id="AdText"><?= $U["Ad"] . " " . $U["Soyad"]; ?></p>
                    <p class="text-white" id="BiyografiText"><?= $U["Biyografi"] ?></p>
                    <div class="row row-cols-2">
                        <?php if($U["Instagram"]){ ?>
                        <p class="col"><a class=" instagram-logo tek-satir" href="https://instagram.com/<?= $U["Instagram"] ?>" target="_blank"><i class="fi fi-brands-instagram"></i><?= karakterKes($U["Instagram"], 13); ?></a></p>
                        <?php } 
                        if($U["Snapchat"]){ ?>
                        <p class="co"><a class="snapchat-logo tek-satir" href="https://www.snapchat.com/add/<?= $U["Snapchat"] ?>" target="_blank"><i class="fi fi-brands-snapchat"></i><?= karakterKes($U["Snapchat"], 13); ?></a></p>
                        <?php }   
                        if($U["Tiktok"]){ ?>
                        <p class="col"><a class="tiktok-logo tek-satir" href="https://www.tiktok.com/@<?= $U["Tiktok"] ?>" target="_blank"><i class="fi fi-brands-tik-tok"></i><?= karakterKes($U["Tiktok"], 13); ?></a></p>
                        <?php } 
                        if($U["Linkedin"]){ ?>
                        <p class="col"><a class="tek-satir linkedin-logo" href="https://linkedin.com/in/<?= $U["Linkedin"] ?>" target="_blank"><i class="fi fi-brands-linkedin"></i><?= karakterKes($U["Linkedin"], 13); ?></a></p>
                        <?php } 
                        if($U["Youtube"]){ ?>
                        <p class="col"><a class="tek-satir youtube-logo" href="https://www.youtube.com/@<?= $U["Youtube"] ?>" target="_blank"><i class="fi fi-brands-youtube"></i><?= karakterKes($U["Youtube"], 13); ?></a></p>
                        <?php } 
                        if($U["Spotify"]){ ?>
                        <p class="col"><a class="spotify-logo tek-satir" href="<?= $U["Spotify"] ?>" target="_blank"><i class="fi fi-brands-spotify"></i>Spotify</a></p>
                        <?php } ?>
                    </div>
                    <div class="mt-3 row row-cols-3 text-center">
                        <div class="col p-0">
                            <div class="modal fade" id="takipci" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header py-1">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?= $U["KullaniciAdi"]; ?>'ın Takipçileri</h1>
                                            <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <ul class="list-group takipci-list list-group-flush">
                                                    <?php
                                                    $TakipciSayi = 0;
                                                    $Takipciler = $db->Get("Takipler")->OrderBy("Tarih", "DESC")->Where("TakipEdilenID", $U["KullaniciID"]);
                                                    if ($db->Row($Takipciler) > 0) {
                                                        foreach ($Takipciler as $Takipci) {
                                                            $TakipciKisi = $db->Get("Kullanici")->Where("KullaniciID", $Takipci["KullaniciID"]);
                                                            if ($db->Row($TakipciKisi) > 0) {
                                                                $TakipciKisi = $TakipciKisi[0];
                                                                if($TakipciKisi["Aktif"] == true){
                                                    ?>
                                                                <a id="user_a_<?= $TakipciKisi["KullaniciAdi"]; ?>" href="<?= $TakipciKisi["KullaniciAdi"]; ?>" class="list-group-item text-start text-white">
                                                                    <?php
                                                                    if ($TakipciKisi["ProfilResmi"] != "") {
                                                                        echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/' . $TakipciKisi["ProfilResmi"] . ');background-position: center;background-size: cover;">';
                                                                    }
                                                                    echo $TakipciKisi["KullaniciAdi"];
                                                                    if($TakipciKisi["Onaylandi"] == true){
                                                                        if($TakipciKisi["Color"]){
                                                                            $Color = $TakipciKisi["Color"];
                                                                            echo '<i class="fi ps-1 tick fi-ss-badge-check ' . $Color . '"></i>';
                                                                        }else{
                                                                            echo '<i class="fi ps-1 fi-ss-badge-check text-primary"></i>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </a>
                                                        <?php $TakipciSayi++; } }
                                                        }
                                                    } else { ?>
                                                        <a id="TakipciYok" class="list-group-item text-start text-white">
                                                            Takipçi yok...
                                                        </a>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#takipci">
                                <p class="text-white" id="TakipciAdet"><?= $TakipciSayi; ?></p>
                                <p>Takipçi</p>
                            </div>
                        </div>
                        <div class="col p-0">
                            <div class="modal fade" id="takip" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header py-1">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel"><?= $U["KullaniciAdi"]; ?>'ın Takipleri</h1>
                                            <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <ul class="list-group list-group-flush">
                                                    <?php
                                                    $TakipSayi = 0;
                                                    $Takipler = $db->Get("Takipler")->OrderBy("Tarih", "DESC")->Where("KullaniciID", $U["KullaniciID"]);
                                                    if ($db->Row($Takipler) > 0) {
                                                        foreach ($Takipler as $Takip) {
                                                            $TakipKisi = $db->Get("Kullanici")->Where("KullaniciID", $Takip["TakipEdilenID"]);
                                                            $TakipKisi = $TakipKisi[0];
                                                            if($TakipKisi["Aktif"] == true){
                                                    ?>
                                                            <a href="<?= $TakipKisi["KullaniciAdi"]; ?>" class="list-group-item text-start text-white">
                                                                <?php
                                                                if ($TakipKisi["ProfilResmi"] != "") {
                                                                    echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/' . $TakipKisi["ProfilResmi"] . ');background-position: center;background-size: cover;">';
                                                                }
                                                                echo $TakipKisi["KullaniciAdi"];
                                                                if($TakipKisi["Onaylandi"] == true){
                                                                    if($TakipKisi["Color"]){
                                                                        $Color = $TakipKisi["Color"];
                                                                        echo '<i class="fi ps-1 tick fi-ss-badge-check ' . $Color . '"></i>';
                                                                    }else{
                                                                        echo '<i class="fi ps-1 fi-ss-badge-check text-primary"></i>';
                                                                    }
                                                                }
                                                                ?>
                                                            </a>
                                                        <?php $TakipSayi++; } }
                                                    } else { ?>
                                                        <a id="TakipciYok" class="list-group-item text-start text-white">
                                                            Takip edilen yok...
                                                        </a>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#takip">
                                <p class="text-white"><?= $TakipSayi; ?></p>
                                <p>Takip Edilen</p>
                            </div>
                        </div>
                        <div class="col p-0">
                            <p class="text-white"><?php
                                                    $GonderilerAdet = 0;
                                                    $Gonderiler = $db->Get("Icerikler")->Where("YazarID", $U["KullaniciID"]);
                                                    if ($db->Row($Gonderiler) > 0) {
                                                        foreach ($Gonderiler as $Gonderi) {
                                                            if ($Gonderi["Durum"] == true && $Gonderi["Yasak"] == false) {
                                                                $GonderilerAdet++;
                                                            }
                                                        }
                                                    }
                                                    echo $GonderilerAdet;
                                                    ?></p>
                            <p>Gönderi</p>
                        </div>
                    </div>
                    <p class="text-center">
                        <?php
                        if (($KullaniciID) && $U["KullaniciID"] == $KullaniciID || $Yetki == 6 || $Yetki < 3 && $Yetki < $U["Yetki"]) {
                        ?>
                            <button type="button" class="btn btn-sm btn-primary mt-3 col-5 mb-0" data-bs-toggle="modal" data-bs-target="#duzenle">
                                Düzenle
                            </button>
                            <?php if($Yetki != 6 && $U["KullaniciID"] == $KullaniciID){ ?>
                            <button type="button" class="btn btn-sm mt-3 p-0 mb-0" data-bs-toggle="modal" data-bs-target="#koleksiyon">
                                <i class="p-0 fi fi-ss-wishlist-star text-white fs-4"></i>
                            </button>
                            <?php } ?>
                            <div class="modal fade" id="duzenle" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content text-white">
                                        <div class="modal-header py-1 text-white">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hesabımı düzenle</h1>
                                            <button type="button" class="btn btn-sm text-white p-0 fs-3" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                                        </div>
                                        <div class="modal-body text-white">
                                            <?php if($Yetki == 1): ?>
                                            <label>Kullanıcı Adı</label>
                                            <div class="mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput" id="KullaniciAdi" value="<?= $U["KullaniciAdi"]; ?>" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="KullaniciAdi" data-veri-id="KullaniciAdiVeri">
                                                <small class="m-0">Lütfen kullanıcı adını güncellerken dikkat ediniz!</small>
                                            </div>
                                            <?php endif; ?>
                                            <label>Ad</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput" id="AdVeri" value="<?= $U["Ad"]; ?>" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Ad" data-veri-id="AdVeri">
                                            </div>
                                            <label>Biyografi</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput" id="BiyografiVeri" value="<?= $U["Biyografi"]; ?>" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Biyografi" data-veri-id="BiyografiVeri">
                                            </div>
                                            <label>E-Mail
                                                <span id="EmailDogrulama"><?php
                                                                            if ($U["Email"]) {
                                                                                if ($U["EpostaDogrulandi"]) {
                                                                                    echo '<span class="text-success">(Doğrulandı)</span>';
                                                                                } else {
                                                                                    echo '<span class="text-danger">(Doğrulanmadı) <a href="../mail-dogrula?user=' . md5($U["KullaniciID"]) . '">Doğrula</a></span>';
                                                                                }
                                                                            }
                                                                            ?></span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control color-modal KullaniciInput" id="EmailVeri" value="<?= $U["Email"]; ?>" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Email" data-veri-id="EmailVeri">
                                            </div>
                                            <label class="text-danger fw-bold mb-2" id="EMailHata"></label>
                                            <p>Doğum Tarihi</p>
                                            <div class="input-group mb-2">
                                                <input type="date" class="form-control color-modal KullaniciInput" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="DogumTarihi" data-veri-id="DogumVeri" id="DogumVeri" value="<?= !empty($U["DogumTarihi"]) ? date("Y-m-d", $U["DogumTarihi"]) : date("Y-m-d"); ?>">
                                            </div>
                                            <label>Cinsiyet</label>
                                            <div class="input-group mb-2">
                                                <select class="form-control color-modal KullaniciInput" id="CinsiyetVeri" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Cinsiyet" data-veri-id="CinsiyetVeri">
                                                    <option value="0" <?php if ($U["Cinsiyet"] == 0) {
                                                                            echo "selected";
                                                                        } ?>>Diğer</option>
                                                    <option value="1" <?php if ($U["Cinsiyet"] == 1) {
                                                                            echo "selected";
                                                                        } ?>>Erkek</option>
                                                    <option value="2" <?php if ($U["Cinsiyet"] == 2) {
                                                                            echo "selected";
                                                                        } ?>>Kadın</option>
                                                </select>
                                            </div>
                                            <label>Telefon Numarası</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text color-modal">+90</span>
                                                <input type="tel" class="form-control color-modal KullaniciInput" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="TelefonNumarasi" data-veri-id="TelefonVeri" id="TelefonVeri"
                                                value='<?php $telefon = $U["TelefonNumarasi"]; $formatted = "(".substr($telefon, 0, 3).") ".substr($telefon, 3, 3)." ".substr($telefon, 6, 2)." ".substr($telefon, 8, 2); echo $formatted; ?>'>
                                            </div>
                                            <hr>
                                            <label>Instagram (Kullanıcı Adı)</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text color-modal">@</span>
                                                <input type="text" class="form-control color-modal KullaniciInput text-lowercase" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Instagram" data-veri-id="InstagramVeri" id="InstagramVeri" value="<?= $U["Instagram"]; ?>">
                                            </div>
                                            <label>Snapchat (Kullanıcı Adı)</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput text-lowercase" id="SnapchatVeri" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Snapchat" data-veri-id="SnapchatVeri" value="<?= $U["Snapchat"]; ?>">
                                            </div>
                                            <label>TikTok (Kullanıcı Adı)</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput text-lowercase" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Tiktok" data-veri-id="TiktokVeri" id="TiktokVeri" value="<?= $U["Tiktok"]; ?>">
                                            </div>
                                            <label>Spotify (Profil Linki)</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Spotify" data-veri-id="SpotifyVeri" id="SpotifyVeri" value="<?= $U["Spotify"]; ?>">
                                            </div>
                                            <label>Linkedin (Kullanıcı Adı)</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput text-lowercase" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Linkedin" data-veri-id="LinkedinVeri" id="LinkedinVeri" value="<?= $U["Linkedin"]; ?>">
                                            </div>
                                            <label>Youtube (Kullanıcı Adı)</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control color-modal KullaniciInput" data-user-id="<?= $U["KullaniciID"]; ?>" data-column="Youtube" data-veri-id="YoutubeVeri" id="YoutubeVeri" value="<?= $U["Youtube"]; ?>">
                                            </div>
                                            <div class="row">
                                                <div class="col-12"><a href="javascript:void(0);" onclick="$('#DigerIslemler').css('display', 'flex'); $(this).remove();" class="btn btn-sm btn-secondary col-12 my-1">Diğer İşlemler</a></div>
                                                <div class="col-12 row-cols-2" id="DigerIslemler" style="display:none;">
                                                    <div class="px-1 col"><a href="<?= $Link; ?>hesap-dondur?user=<?= md5($U["KullaniciID"]); ?>" class="btn btn-sm btn-secondary col-12 my-1">Hesabı Dondur</a></div>
                                                    <div class="px-1 col"><a href="<?= $Link; ?>exit" class="btn btn-sm btn-danger col-12 my-1">Hesaptan Çıkış Yap</a></div>
                                                    <!--<div class="col-6 px-1"><a href="<?= $Link; ?>hesap-sil?user=<?= md5($U["KullaniciID"]); ?>" class="btn btn-sm btn-secondary col-12 my-1">Hesabı Sil</a></div>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-primary col-4" data-bs-dismiss="modal">Kaydet</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if($Yetki != 6){ ?>
                            <div class="modal fade" id="koleksiyon" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                  <div class="modal-header py-1">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Sticker Koleksiyonum</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                        <ul class="p-0 row row-cols-2 justify-content-center g-1 mb-0">
                                            <?php
                                                $AlisverisSql2 = $db->Get("Alisveris")->OrderBy("Tarih", "DESC")->Where("KullaniciID", $KullaniciID);
                                                if($db->Row($AlisverisSql2) > 0){ 
                                                foreach($AlisverisSql2 as $Alisveris2){
                                                    $Urun2 = $db->Get("Urunler")->Where("UrunID", $Alisveris2["UrunID"]);
                                                    $Urun2 = $Urun2[0];
                                                    if($Urun2["UrunYeri"] == 1){
                                                ?>
                                                <div class="col h-100">
                                                    <input type="radio" class="btn-check" name="options-outlined" id="urun<?= $Urun2["UrunID"]; ?>" autocomplete="off" <?php if($U["Sticker"] != "" && $U["Sticker"] == $Urun2["UrunLink"]){ echo "checked"; } ?>>
                                                    <label data-bs-dismiss="modal" class="w-100 h-100 tek-satir btn-sm btn btn-outline-light my-1 koleksiyon-sticker" data-veri="<?= $Urun2["UrunLink"]; ?>" for="urun<?= $Urun2["UrunID"]; ?>">
                                                        <img style="height:2rem; padding-right:4px;" src="../images/sticker/<?= $Urun2["UrunLink"]; ?>.png"><?= $Urun2["UrunAdi"]; ?>
                                                    </label>
                                                </div>
                                               <?php } } }else{
                                                   echo "Koleksiyon boş...";
                                               }
                                                ?>
                                        </ul>
                                  </div>
                                  <div class="modal-footer">
                                    <a href="../alisveris" class="btn btn-sm text-light col-4 g-kakao"><i class="fi fi-ts-store-alt"></i>Mağaza</a>
                                    <button type="button" class="btn btn-sm btn-primary col-4" data-bs-dismiss="modal">Tamam</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                <?php } }
                    if (($KullaniciID) && $KullaniciID != $U["KullaniciID"]) { 
                     $TakipYok = true; ?>
                        <input type="hidden" value="<?= $U["KullaniciID"]; ?>" id="UserIDInput">
                    <?php
                            $Takipciler = $db->Get("Takipler")->Where("TakipEdilenID", $U["KullaniciID"]);
                            $TakipciSayi = $db->Row($Takipciler);
                            if ($TakipciSayi > 0) {
                                foreach ($Takipciler as $Takip) {
                                    if ($Takip["KullaniciID"] == $KullaniciID) {
                                    ?>
                                        <button type="button" class="btn btn-sm btn-danger mt-3 col-6 m-auto" id="TakipBirak">
                                            <i class="fi fi-rr-remove-user" id="TakipIcon"></i><span id="TakipText">Takibi Bırak</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success mt-3 col-6 m-auto" style="display: none;" data-onay="<?= $Kullanici["Onaylandi"]; ?>" data-tik-renk="<?= $Kullanici["Color"]; ?>" id="TakipEt">
                                            <i class="fi fi-rr-user-add" id="TakipIcon"></i><span id="TakipText">Takip Et</span>
                                        </button>
                            <?php   $TakipYok = false;
                                    }
                                }
                            // Takipçi sayısı sıfırsa    
                            } else { 
                            $TakipYok = false;
                            ?>
                            <button type="button" class="btn btn-sm btn-danger mt-3 col-6 m-auto" style="display: none;" id="TakipBirak">
                                <i class="fi fi-rr-remove-user" id="TakipIcon"></i><span id="TakipText">Takibi Bırak</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-success mt-3 col-6 m-auto" data-onay="<?= $Kullanici["Onaylandi"]; ?>" data-tik-renk="<?= $Kullanici["Color"]; ?>" id="TakipEt">
                                <i class="fi fi-rr-user-add" id="TakipIcon"></i><span id="TakipText">Takip Et</span>
                            </button>
                    <?php }
                    if($TakipYok){ ?>
                            <button type="button" class="btn btn-sm btn-danger mt-3 col-6 m-auto" style="display: none;" id="TakipBirak">
                                <i class="fi fi-rr-remove-user" id="TakipIcon"></i><span id="TakipText">Takibi Bırak</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-success mt-3 col-6 m-auto" data-onay="<?= $Kullanici["Onaylandi"]; ?>" data-tik-renk="<?= $Kullanici["Color"]; ?>" id="TakipEt">
                                <i class="fi fi-rr-user-add" id="TakipIcon"></i><span id="TakipText">Takip Et</span>
                            </button>
                    <?php }
                    ?>
                <?php } 
                if($KullaniciID != $U["KullaniciID"]){
                ?>
                    <a href="../messages?receiver_id=<?= $U["KullaniciID"]; ?>" type="button" class="btn btn-sm mt-3 p-0">
                        <i class="p-0 fi fi-ss-messages text-white fs-4"></i>
                    </a>
                            <?php }else{                             
                            ?>
                    <!--<small class="text-muted text-small position-absolute" style="bottom: 0.3rem;right:0.3rem;">IP: <?= $U["IpAdresi"]; ?></small>-->
                    
                 <?php }
                    $Yetkimiz = $db->Get("Yetkiler")->Where("YetkiID", $U["Yetki"]);
                    $Yetkimiz = $Yetkimiz[0];
                    $YetkiIcon = $Yetkimiz["Icon"];
                    $YetkiAdi = $Yetkimiz["YetkiAdi"];
                    $YetkiRenk = $Yetkimiz["Color"];
                    ?>
                    <small class="text-small position-absolute" style="bottom: 0.1rem;left:0.3rem;">
                        <span id="YetkiKonum" class="text-<?= $YetkiRenk; ?>">
                            <i id="YetkiIcon" class="fi fi-ts-<?= $YetkiIcon; ?> p-0"></i>
                            <small id="YetkiAdiSmall"><?= $YetkiAdi; ?></small>
                        </span>
                    </small>
                </div>
            </div>
            <hr>
            <div class="container">
                <h4>Paylaşımlar</h4>
                <?php
                $Icerikler = $db->Get("Icerikler")->OrderBy("YayimTarihi", "DESC")->Where("YazarID", $U["KullaniciID"]);
                $IcerikAdet = $db->Row($Icerikler);
                if ($IcerikAdet > 0) {
                    foreach ($Icerikler as $Icerik) {
                        if ($Icerik["Yasak"] == false) {
                            if ($Icerik["Durum"] == true || $Icerik["YazarID"] == $KullaniciID) {
                ?>
                                <div onclick="location.href='../post/<?= $Icerik["YayimTarihi"] ."-".$U["KullaniciID"]; ?>'" class="color cursor-pointer">
                                    <div class="card color shadow my-3" style>
                                        <div class="card-body">
                                            <?php
                                            if ($U["ProfilResmi"] != "") {
                                                echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/' . $U["ProfilResmi"] . ');background-position: center;background-size: cover;">';
                                            }
                                            echo "<span class='text-white'>" . $U["Ad"] . "</span>";
                                            if ($U["Onaylandi"] == true) {
                                                if($U["Color"]){
                                                    $Color = $U["Color"];
                                                    echo '<i class="fi tick fi-ss-badge-check ps-1 ' . $Color . '"></i>';
                                                }else{
                                                    echo '<i class="fi fi-ss-badge-check text-primary ps-1"></i>';
                                                }
                                            }
                                            echo '<span class="text-muted"> ● @'.$U["KullaniciAdi"].'</span>';
                                            if ($Icerik["Durum"] == false) {
                                                echo ' - <span class="text-danger">YAYINDA DEĞİL</span>';
                                            }
                                            ?>
                                            <p class="text-white">
                                                <?= $Icerik["IcerikMetni"]; ?>
                                            </p>
                                            <?php if ($Icerik["IcerikResim"]) { ?>
                                                <p class="text-center">
                                                    <span class="d-inline-block col-11 m-auto" style="background-image: url(../images/<?= $Icerik["IcerikResim"] ?>);height: 21rem;background-position: center;background-size: contain; background-repeat: no-repeat;"></span>
                                                </p>
                                            <?php } ?>
                                            <div class="row">
                                                <p class="mt-1 col-7 text-muted fst-italic">
                                                    <i class="fi fi-ts-calendar"></i>
                                                    <?= date("d.m.Y - H:i", $Icerik["YayimTarihi"]); ?>
                                                </p>
                                                <p class="mt-1 col-3 text-muted fst-italic">
                                                    <i class="fi fi-ts-comment-dots"></i>
                                                    <?= $db->Row($db->Get("Yorumlar")->Where("IcerikID", $Icerik["IcerikID"])); ?>
                                                </p>
                                                <p class="mt-1 col-2 text-end text-danger">
                                                    <i class="fi fi-<?php
                                                                    $KontrolBegeni = false;
                                                                    $IcerikBegeniler = $db->Get("Begeni")->Where("IcerikID", $Icerik["IcerikID"]);
                                                                    if ($db->Row($IcerikBegeniler) > 0) {
                                                                        foreach ($IcerikBegeniler as $IcerikBegeni) {
                                                                            if ($IcerikBegeni["KullaniciID"] == $KullaniciID) {
                                                                                $KontrolBegeni = true;
                                                                            }
                                                                        }
                                                                    }
                                                                    if ($KontrolBegeni) {
                                                                        echo "ss";
                                                                    } else if ($KullaniciID == false) {
                                                                        echo "ss";
                                                                    } else {
                                                                        echo "rr";
                                                                    } ?>-heart"></i>
                                                    <?= $db->Row($db->Get("Begeni")->Where("IcerikID", $Icerik["IcerikID"])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                <?php           } else {
                                $IcerikAdet--;
                            }
                        } else {
                            $IcerikAdet--;
                        }
                    }
                }
                if ($IcerikAdet == 0) {
                    echo '<p class="text-danger">Daha önce hiç paylaşım yapılmamış.</p>';
                }
                ?>
            </div>
<?php } else {
            header("location: $Link");
        }
    } else {
        header("location: $Link");
    }
} else {
    header("location: $Link");
}
?>