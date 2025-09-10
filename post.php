<?php
$veri = explode("-", clean($k[1]));
$Icerikler = $db->Get("Icerikler")->Where("YayimTarihi", $veri[0]);
if($db->Row($Icerikler) > 0){
    $Icerik = $Icerikler[0];
    if($Icerik["YazarID"] == $veri[1]){
        if($Icerik["YazarID"] == $KullaniciID || $Icerik["Durum"] == true && $Icerik["Yasak"] == false){
?>
<title><?= $SiteAdi; ?> ● @<?= $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"KullaniciAdi"); ?>'ın Paylaşımı</title>
<h4 class="mt-4 container d-flex justify-content-between align-items-center">
    <div>
        @<span class="fw-bold"><?= $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"KullaniciAdi"); ?></span>
        <span style="font-size: 15px;">kullanıcısının paylaşımı
        <?php 
            if($Icerik["Durum"] == false){
                echo '<span id="IcerikDurumText" class="text-danger"> - YAYINDA DEĞİL</span>';
            }else{
                echo '<span id="IcerikDurumText" class="text-danger"></span>';
            }
        ?>
        </span> 
    </div>
    <a class="btn p-0" style="background-color: #4aca59; width: 2rem; height: 2rem;" href="https://wa.me/?text=<?= $SiteAdi; ?> ● @<?= $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"KullaniciAdi"); ?>'ın Paylaşımı%0A<?= "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" target="_blank">
      <i class="fi fi-brands-whatsapp p-0 text-white fs-5"></i>
    </a>
</h4>

<hr class="m-0">
<div class="card color mb-3 color mt-2 border-0">
    <?php if($KullaniciID && $Icerik["YazarID"] != $KullaniciID && $Yetki != 6 && $Yetki > 2){ ?>
        <button type="button" class="btn text-white"
        style="
            display: inline;
            width: max-content;
            float: right;
            position: absolute;
            right: 0;
            top: 0;" data-bs-toggle="modal" data-bs-target="#PostSikayet">
          <i class="fi fi-ts-brake-warning fs-3 text-muted"></i>
        </button>
        <div class="modal fade" id="PostSikayet" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header py-1 text-white">
                <h1 class="modal-title fs-5"><?= $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"KullaniciAdi"); ?>'ın paylaşımını şikayet et!</h1>
                <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
              </div>
              <div class="modal-body text-white">
                <label>Şikayet Konusu</label>
                <select class="form-control color-modal" id="SikayetKonu">
                    <?php
                    $SikayetKategorileri = $db->Get("SikayetKategorileri")->Where("Aktif", 1);
                    foreach($SikayetKategorileri as $Kategori){
                        echo '<option value="'.$Kategori["KategoriID"].'">'.$Kategori["KategoriAdi"].' - '.$Kategori["Aciklama"].'</option>';
                    }
                    ?>
                </select>
                <label class="mt-2">Şikayetiniz</label>
                <textarea class="form-control color-modal" rows=3 id="SikayetMetin"></textarea>
                <input type="hidden" id="IcerikIDInput" value="<?= $Icerik["IcerikID"]; ?>">
                <p id="SikayetMesaj"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary col-3" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-sm btn-primary col-3" id="PostSikayet">Gönder</button>
              </div>
            </div>
          </div>
        </div>
        <?php } 
        if($KullaniciID &&  $Icerik["YazarID"] == $KullaniciID || $Yetki == 6 || $Yetki < 3){ ?>
        <button type="button" class="btn text-white"
        style="
            display: inline;
            width: max-content;
            float: right;
            position: absolute;
            right: 0;
            top: 0;" data-bs-toggle="modal" data-bs-target="#duzenlePost">
          <i class="fi fi-ts-dot-pending fs-3"></i>
        </button>
        <div class="modal fade" id="duzenlePost" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header py-1 text-white">
                <h1 class="modal-title fs-5">Paylaşımını düzenle</h1>
                <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
              </div>
                <div class="modal-body text-white">
                    <div class="mt-2 row justify-content-around">
                        <?php if($Icerik["Durum"] == true){ ?>
                        <button class="btn btn-sm btn-primary col-5 my-1" id="GonderiGizleGoster" data-id="<?= $Icerik["IcerikID"]; ?>" data-status="0"><i id="GonderiGGIcon" class="fi fi-rr-eye-crossed"></i> <span id="GonderiGGText">Gönderiyi Gizle</span></button>
                        <?php }else{ ?>
                        <button class="btn btn-sm btn-primary col-5 my-1" id="GonderiGizleGoster" data-id="<?= $Icerik["IcerikID"]; ?>" data-status="1"><i id="GonderiGGIcon" class="fi fi-rr-eye"></i> <span id="GonderiGGText">Gönderiyi Göster</span></button>
                        <?php }   
                        if($Yetki == 6 || $Yetki < 3){
                        if($Icerik["Sabit"] == true){ ?>
                        <button class="btn btn-sm btn-success col-5 my-1" id="GonderiSabitle" data-id="<?= $Icerik["IcerikID"]; ?>" data-status="0"><i id="GonderiSabitIcon" class="fi fi-rr-location-exclamation"></i> <span id="GonderiSabitText">Sabitleme</span></button>
                        <?php }else{ ?>
                        <button class="btn btn-sm btn-success col-5 my-1" id="GonderiSabitle" data-id="<?= $Icerik["IcerikID"]; ?>" data-status="1"><i id="GonderiSabitIcon" class="fi fi-rr-thumbtack"></i> <span id="GonderiSabitText">Sabitle</span></button>
                        <?php } } ?>
                        <a class="btn btn-sm btn-danger col-5 my-1" style="display: none;" id="GonderiSilBtn" data-id="<?= $Icerik["IcerikID"]; ?>"><i class="fi fi-ts-check-circle"></i>Emin misiniz!</a>
                        <a class="btn btn-sm btn-danger col-5 my-1" href="javascript:void(0);" onclick="$('#GonderiSilBtn').show(); $(this).remove();"><i class="fi fi-rr-trash"></i> Gönderiyi Sil</a>
                        <p id="SabitUyari" class="text-center text-small text-success"></p>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary col-3" data-bs-dismiss="modal">İptal</button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
    <div class="card-body">
        <a class="text-white" href="../u/<?= $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"KullaniciAdi"); ?>">
            <?php 
            if($db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"ProfilResmi") != ""){ 
                echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/'.$db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"ProfilResmi") .');background-position: center;background-size: cover;">';
            }else{ 
                echo '<i class="fi fi-ss-user fs-1"></i>'; 
            }
            $PostGonderenAd = $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"Ad");
            if($PostGonderenAd == ""){
                $PostGonderenAd = "İsimsiz Kullanıcı";
            }
            $PostGonderenKullaniciAdi = $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"],"KullaniciAdi");
            echo "<span class=''>".$PostGonderenAd; 
            $Onaylandi = $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"], "Onaylandi");
            $Color = $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"], "Color");
            if($Onaylandi == true){
                if($Color){
                    echo '<i class="fi tick fi-ss-badge-check ps-1 ' . $Color . '"></i>';
                }else{
                    echo '<i class="fi fi-ss-badge-check text-primary ps-1"></i>';
                }
            }
            echo "<span class='text-muted'> ● @" . $PostGonderenKullaniciAdi ."</span>";
            ?>
        </a>
        <p class="text-white mt-2"><?= $Icerik["IcerikMetni"]; ?></p>
        <?php if($Icerik["IcerikResim"]){ ?>
            <p class="text-center">
                <span class="d-inline-block col-11 m-auto" style="background-image: url(../images/<?= $Icerik["IcerikResim"] ?>);max-height: 50vh;height: 25rem;background-position: center;background-size: contain; background-repeat: no-repeat;"></span>
            </p>
        <?php } ?>
        <div class="fst-italic row">
            <p class="mt-1 col text-muted">
                <i class="fi fi-ts-calendar"></i>
                <span><?= date("d.m.Y - H:i", $Icerik["YayimTarihi"]); ?></span>
            </p>
            <p class="mt-1 col text-muted text-end">
                <span class=""><i style='color: <?= $db->Get("Kategoriler")->Where("KategoriID", $Icerik["KategoriID"], "Color"); ?>;' class="fi fi-ts-<?= $db->Get("Kategoriler")->Where("KategoriID", $Icerik["KategoriID"], "Icon"); ?>"></i><?= $db->Get("Kategoriler")->Where("KategoriID", $Icerik["KategoriID"], "KategoriAdi"); ?></span>
            </p>
        </div>
        <p class="mt-1 col text-danger">
            <i class="fi <?php
                        if ($KullaniciID) {
                            $Bul = $db->Get("Begeni")->Where("KullaniciID", $KullaniciID);
                            $begeniVar = false;
                        
                            if ($db->Row($Bul) > 0) {
                                foreach ($Bul as $value) {
                                    if ($value["IcerikID"] == $Icerik["IcerikID"]) {
                                        $begeniVar = true;
                                        break; // eşleşme bulunduysa döngüden çık
                                    }
                                }
                            }
                        
                            echo $begeniVar ? " fi-ss-heart " : " fi-rr-heart ";
                        
                        } else {
                            echo " fi-ss-heart ";
                        }
                        ?> fs-1 cursor-pointer" <?php
                            if($KullaniciID){ echo "onclick='begen($KullaniciID, $Icerik[IcerikID], this, $Icerik[YazarID])'"; } 
                        ?>></i>
                    <span data-bs-toggle="modal" data-bs-target="#begenenler" class="fs-5 pe-4" id="BegeniText_<?= $Icerik["IcerikID"]; ?>"><?= $db->Row($db->Get("Begeni")->Where("IcerikID", $Icerik["IcerikID"])); ?></span>
                        <?php if($KullaniciID == $Icerik["YazarID"]){ ?>
                        <div class="modal fade" id="begenenler" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                              <div class="modal-header py-1">
                                <h1 class="modal-title fs-5">Gönderiyi Beğenenler</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <div class="card">
                                    <ul class="list-group list-group-flush" id="Begeniler">
                                  <?php
                                  $Begenenler = $db->Get("Begeni")->Where("IcerikID", $Icerik["IcerikID"]);
                                  if($db->Row($Begenenler) > 0){
                                  foreach($Begenenler as $Begenen){ 
                                  $BegenenKisi = $db->Get("Kullanici")->Where("KullaniciID", $Begenen["KullaniciID"]);
                                  $BegenenKisi = $BegenenKisi[0];
                                  ?>
                                        <a href="../u/<?= $BegenenKisi["KullaniciAdi"]; ?>" class="list-group-item text-start text-white" id="BegenenKullanici<?= $BegenenKisi["KullaniciID"]; ?>">
                                            <img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/<?= $BegenenKisi["ProfilResmi"]; ?>);background-position: center;background-size: cover;"><?= $BegenenKisi["KullaniciAdi"]; ?><?php if($BegenenKisi["Onaylandi"] == true){ ?><i class="fi ps-1 tick fi-ss-badge-check <?= $BegenenKisi["Color"]; ?>"></i><?php } ?>
                                        </a>   
                                  <?php } }else{ ?>
                                        <a id="BegeniYok" class="list-group-item text-start text-white">
                                            Henüz beğeni yok...
                                        </a>
                                  <?php }
                                  ?>
                                  
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
        </p>
    </div>
    <hr class="m-0">
    <div class="card-body px-2" style="overflow-x: hidden!important;">
        <?php if($KullaniciID){ ?>
        <h5 class="text-white"><i class="fi fi-ts-comment-pen"></i> Yorum Yap</h5>
        <div class="input-group">
          <input type="text" class="form-control color" id="IcerikMetni">        
          <input type="hidden" class="form-control color-modal" id="IcerikId" value="<?= $Icerik["IcerikID"]; ?>">
          <input type="hidden" class="form-control color-modal" id="KullaniciAdi" value="<?= $KullaniciAdi; ?>">
          <input type="hidden" class="form-control color-modal" id="AliciID" value="<?= $Icerik["YazarID"]; ?>">
          <button class="btn btn-sm btn-primary" type="button" id="YorumGonder">Gönder</button>
        </div>
          <ul class="dropdown-menu" id="dropdown" style="overflow-y: scroll;">
              <?php
              if($Yetki == 1 || $Yetki == 6 || $Yetki < 3){
                  echo '<li><a class="dropdown-item" href="#">everyone</a></li>';
              }
              $Uyeler = $db->Get("Kullanici")->OrderBy("KullaniciAdi", "ASC")->Where("Aktif", "1");
              foreach($Uyeler as $Uye){ ?>
                  <li><a class="dropdown-item" href="#"><?= $Uye["KullaniciAdi"]; ?></a></li>
              <?php }
              ?>
            
          </ul>  
        <?php
        }
        $Yorumlar = $db->Get("Yorumlar")->OrderBy("YorumTarihi", "DESC")->Where("IcerikID", $Icerik["IcerikID"]);
        echo '<h5 class="text-white mt-3"><i class="fi fi-ts-comment-dots"></i> Yorumlar (<span id="YorumlarAdet">'.$db->Row($Yorumlar).'</span>)</h5>
        <div id="Yorumlar">';
        if($db->Row($Yorumlar) > 0){
            foreach($Yorumlar as $Yorum){ 
            if($Yorum["YorumOnay"] == true){ ?>
                <div class="card text-white my-2 color" id="PostYorum_<?= $Yorum["YorumID"]; ?>">
                    <?php if($KullaniciID && $Yorum["KullaniciID"] != $KullaniciID && $Yetki != 6 && $Yetki > 2){ ?>
                        <button type="button" class="btn text-white"
                        style="
                            display: inline;
                            width: max-content;
                            float: right;
                            position: absolute;
                            right: 0;
                            top: 0;" data-bs-toggle="modal" data-bs-target="#sikayet<?= $Yorum["YorumID"]; ?>">
                          <i class="fi fi-ts-brake-warning fs-3 text-muted"></i>
                        </button>
                        <div class="modal fade" id="sikayet<?= $Yorum["YorumID"]; ?>" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header py-1 text-white">
                                <h1 class="modal-title fs-5"><?= $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"KullaniciAdi"); ?>'ın yorumunu şikayet et!</h1>
                                <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                              </div>
                              <div class="modal-body text-white">
                                <label>Yorum:</label>
                                <textarea class="form-control bg-transparent mb-2" disabled><?= $Yorum["YorumIcerigi"]; ?></textarea>
                                <label>Şikayet Konusu</label>
                                <select class="form-control color-modal" id="YorumSikayetKonu_<?= $Yorum["YorumID"]; ?>">
                                    <?php
                                    $SikayetKategorileri = $db->Get("SikayetKategorileri")->Where("Aktif", 1);
                                    foreach($SikayetKategorileri as $Kategori){
                                        echo '<option value="'.$Kategori["KategoriID"].'">'.$Kategori["KategoriAdi"].' - '.$Kategori["Aciklama"].'</option>';
                                    }
                                    ?>
                                </select>
                                <label class="mt-2">Şikayetiniz</label>
                                <textarea class="form-control color-modal" rows=3 id="YorumSikayetMetin_<?= $Yorum["YorumID"]; ?>"></textarea>
                                <p id="YorumSikayetMesaj_<?= $Yorum["YorumID"]; ?>"></p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary col-3" data-bs-dismiss="modal">İptal</button>
                                <button type="button" class="btn btn-sm btn-primary col-3" onclick="SikayetYorum(<?= $Yorum["YorumID"]; ?>)">Gönder</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php }
                        if($KullaniciID && $Yorum["KullaniciID"] == $KullaniciID || $Yetki == 6 || $Yetki < 3){
                            ?>
                            <button type="button" class="btn text-white"
                            style="
                                display: inline;
                                width: max-content;
                                float: right;
                                position: absolute;
                                right: 0;
                                top: 0;" data-bs-toggle="modal" data-bs-target="#duzenlePost<?= $Yorum["YorumID"]; ?>">
                              <i class="fi fi-ts-dot-pending fs-3 text-muted"></i>
                            </button>
                            <div class="modal fade" id="duzenlePost<?= $Yorum["YorumID"]; ?>" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header py-1 text-white">
                                    <h1 class="modal-title fs-5">Yorumunu düzenle</h1>
                                      <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                                  </div>
                                  <div class="modal-body text-white text-center">
                                    <a class="btn btn-sm btn-danger col-8" style="display: none;" data-bs-dismiss="modal" id="YorumSilBtn_<?= $Yorum["YorumID"]; ?>" onclick="YorumSilBtn(<?= $Yorum["YorumID"]; ?>)"><i class="fi fi-ts-check-circle"></i> Emin misiniz!</a>
                                    <a class="btn btn-sm btn-danger col-8" href="javascript:void(0);" onclick="$('#YorumSilBtn_<?= $Yorum["YorumID"]; ?>').show(); $(this).remove();"><i class="fi fi-rr-trash"></i> Yorumu Sil</a>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-primary col-3" data-bs-dismiss="modal">İptal</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php } ?>
                    <div class="card-header py-1 px-2">
                        <a class="text-white" href="../u/<?= $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"KullaniciAdi"); ?>">
                            <?php 
                            $YorumOnaylandi = $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"Onaylandi");
                            $YorumColor = $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"Color");
                            if($YorumOnaylandi == true){
                                $YorumTik = '<i class="fi tick fi-ss-badge-check ps-1 '.$YorumColor.'"></i>';
                            }else{
                                $YorumTik = "";
                            }
                            if($db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"ProfilResmi") != ""){ 
                                echo '<img class="me-2 rounded-circle" style="width: 40px;height: 40px;background-image: url(../images/'. $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"ProfilResmi") .');background-position: center;background-size: cover;">';
                            }else{ 
                                echo '<i class="fi fi-ss-user fs-2"></i>'; 
                            }
                            $YorumGonderenAd = $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"], "Ad");
                            if($YorumGonderenAd == ""){
                                $YorumGonderenAd = "İsimsiz";
                            }
                            echo "<span class=''>". $YorumGonderenAd . $YorumTik." <span class='text-muted'> ● @".$db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"KullaniciAdi")."</span></span>"; ?>
                        </a>
                    </div>
                    <div class="card-body py-1">
                        <?= $Yorum["YorumIcerigi"]; ?>
                        <p class="text-muted fst-italic">
                            <i class="fi fi-ts-calendar"></i><?= date("d.m.Y - H:i", $Yorum["YorumTarihi"]); ?>
                        </p>
                        <?php if($KullaniciID){ ?>
                        <div class="ps-3 d-flex align-items-center">
                            <i class="fi fi-ts-arrow-turn-down-right me-1"></i>
                            <div class="YorumCevapDiv cursor-pointer w-100">
                                <i id="YanitlaBtn<?= $Yorum['YorumID']; ?>" onclick="cevapla(<?= $Yorum['YorumID']; ?>, this)">Yanıtla</i>
                                <div style="display: none;" id="Cevapla<?= $Yorum['YorumID']; ?>" class="input-group">
                                    <input class="form-control color p-0 px-2 YorumCevapEt" id="CevapInput<?= $Yorum['YorumID']; ?>" value="@<?= $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"KullaniciAdi"); ?> ">
                                    <button class="btn btn-sm p-0 px-1 btn-outline-primary YorumGonder" data-kullanici-adi="<?= $db->Get("Kullanici")->Where("KullaniciID", $Yorum["KullaniciID"],"KullaniciAdi"); ?>" data-yorum-id="<?= $Yorum['YorumID']; ?>" data-icerik-id="<?= $Icerik['IcerikID']; ?>" type="button" style="border-color: #86b7fe;color: #86b7fe;">Gönder</button>
                                </div>
                                  <ul class="dropdown-menu dropdown2" style="overflow-y: scroll;z-index: 1050;">
                                      <?php
                                      if($Yetki == 1 || $Yetki == 6 || $Yetki < 3){
                                          echo '<li><a class="dropdown-item" href="#">everyone</a></li>';
                                      }
                                      $Uyeler = $db->Get("Kullanici")->OrderBy("KullaniciAdi", "ASC")->Where("Aktif", "1");
                                      foreach($Uyeler as $Uye){ ?>
                                          <li><a class="dropdown-item" href="#"><?= $Uye["KullaniciAdi"]; ?></a></li>
                                      <?php }
                                      ?>
                                    
                                  </ul>  
                            </div>
                        </div>
                        <?php } ?>
                        <div id="YorumCevaplar<?= $Yorum['YorumID']; ?>">
                            <?php 
                            $YorumCevaplar = $db->Get("YorumCevap")->OrderBy("YorumCevapTarih", "DESC")->Where("YorumCevapYorumID", $Yorum['YorumID']);
                            if($db->Row($YorumCevaplar) > 0){
                                foreach($YorumCevaplar as $YorumCevap){
                                $YorumCevapKullanici = $db->Get("Kullanici")->Where("KullaniciID", $YorumCevap["YorumCevapKullaniciID"]);
                                $YorumCevapKullanici = $YorumCevapKullanici[0]; 
                                $Cevaplars = $db->Get("YorumCevapla")->OrderBy("YorumCevaplaTarih", "DESC")->Where("YorumCevaplaYorumID", $YorumCevap['YorumCevapID']);
                                $YazKullaniciYorum = $db->Get("Kullanici")->Where("KullaniciID", $YorumCevap["YorumCevapKullaniciID"],"KullaniciAdi");
                                if($db->Row($Cevaplars) > 0){
                                  $CevapKullanicisi = $db->Get("Kullanici")->Where("KullaniciID", $Cevaplars[0]["YorumCevaplaKullaniciID"]);
                                  $CevapKullanicisi = $CevapKullanicisi[0];
                                  if($KullaniciID == $YorumCevap["YorumCevapKullaniciID"]){
                                    $YazKullaniciYorum = $CevapKullanicisi["KullaniciAdi"];
                                  }
                                }
                                ?>
                                <li class="nav-link my-2 ps-3 position-relative SilLiClass<?= $YorumCevap["YorumCevapID"]; ?>" id="YorumCevapLi<?= $YorumCevap["YorumCevapID"]; ?>">
                                    <a href="../u/<?= $YorumCevapKullanici["KullaniciAdi"]; ?>">
                                        <img class="rounded-circle" style="width: 30px;height: 30px;background-image: url(../images/<?= $YorumCevapKullanici["ProfilResmi"]; ?>);background-position: center;background-size: cover;">
                                        <b class="<?php if($YorumCevapKullanici["Onaylandi"] == true){ echo "text-light";/*echo "tick ".$YorumCevapKullanici["Color"];*/ }else{ echo "text-light"; } ?>">@<?php echo $YorumCevapKullanici["KullaniciAdi"]; if($YorumCevapKullanici["Onaylandi"] == true){ echo '<i class="fi tick fi-ss-badge-check ps-1 '.$YorumCevapKullanici["Color"].'"></i>'; } ?>:</b></a>
                                    <span><?php echo $YorumCevap["YorumCevapMetin"]; if($KullaniciID == $YorumCevap["YorumCevapKullaniciID"] || $Yetki == 6 || $Yetki < 3){ ?> <i data-yorum-id="<?= $YorumCevap["YorumCevapID"]; ?>" class="YorumSilBtn fs-5 cursor-pointer text-danger fi fi-ts-circle-trash p-0" style="position: absolute;left: -0.5rem;top: 0.1rem;"></i> <?php } ?></span>
                                    <div class="row">
                                      <p class="fst-italic cursor-pointer ms-5" id="CevaplaP<?= $YorumCevap['YorumCevapID']; ?>" onclick="cevaplaYorum(<?= $YorumCevap['YorumCevapID']; ?>, this)"><i class="fi fi-ts-arrow-turn-down-right"></i><span>Yanıtla</span></p>
                                      <div style="display: none;" id="CevaplaYorum<?= $YorumCevap['YorumCevapID']; ?>" class="input-group">
                                          <i class="fi fi-ts-arrow-turn-down-right ms-5"></i>
                                          <input class="form-control color p-0 px-2 YorumCevapEt" 
                                          id="CevaplaYorumInput<?= $YorumCevap['YorumCevapID']; ?>" 
                                          value="@<?= $YazKullaniciYorum; ?> ">
                                          <button class="btn btn-sm p-0 px-1 btn-outline-primary YorumCevapGonder" 
                                          data-kullanici-adi="<?= $YorumCevapKullanici["KullaniciAdi"]; ?>" 
                                          data-yorum-id="<?= $YorumCevap['YorumCevapID']; ?>" 
                                          data-icerik-id="<?= $Icerik['IcerikID']; ?>" type="button" style="border-color: #86b7fe;color: #86b7fe;">Gönder</button>
                                      </div>
                                    </div>
                                    <div id="YorumlarCevapliDiv<?= $YorumCevap['YorumCevapID']; ?>">
                                    <?php
                                    if($db->Row($Cevaplars) > 0){
                                    foreach($Cevaplars as $Cevap){
                                      $CevapKullanici = $db->Get("Kullanici")->Where("KullaniciID", $Cevap["YorumCevaplaKullaniciID"]);
                                      $CevapKullanici = $CevapKullanici[0]; ?>
                                      <li class="nav-link my-2 position-relative ms-5 SilLiClass<?= $YorumCevap["YorumCevapID"]; ?>" id="CevapYorumLi<?= $Cevap['YorumCevaplaID']; ?>">
                                        <a href="../u/<?= $CevapKullanici["KullaniciAdi"]; ?>">
                                            <img class="rounded-circle" style="width: 30px;height: 30px;background-image: url(../images/<?= $CevapKullanici["ProfilResmi"]; ?>);background-position: center;background-size: cover;">
                                            <b class="<?php if($CevapKullanici["Onaylandi"] == true){ echo "text-light";/*echo "tick ".$CevapKullanici["Color"];*/ }else{ echo "text-light"; } ?>">@<?php echo $CevapKullanici["KullaniciAdi"]; if($CevapKullanici["Onaylandi"] == true){ echo '<i class="fi tick fi-ss-badge-check ps-1 '.$CevapKullanici["Color"].'"></i>'; } ?>:</b></a>
                                        <span><?php echo $Cevap["YorumCevaplaYorum"]; 
                                        if($Yetki == 6 || $Yetki < 3 || $KullaniciID == $Cevap["YorumCevaplaKullaniciID"]){ ?> 
                                        <i data-yorum-id="<?= $Cevap["YorumCevaplaID"]; ?>" class="CevapYorumSilBtn fs-5 cursor-pointer text-danger fi fi-ts-circle-trash p-0" style="position: absolute;left: -1.5rem;top: 0.1rem;"></i> 
                                        <?php } ?>
                                        </span>
                                      </li>
                                    <?php } } ?>
                                    </div>
                                </li>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            <?php } }
        }else{
            echo "<span id='YorumYok'>Henüz yorum yok</span>";
        }
        ?>
        </div>
    </div>
</div>
<?php
        }else{
            include "./error.php";
        }
    }else{
    header("location: $Link");
    }
        
    }else{
    header("location: $Link");
}?>