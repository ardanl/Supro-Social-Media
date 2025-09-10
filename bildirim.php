<button type="button" class="btn btn-primary rounded-circle" data-bs-toggle="modal" id="BildirimButon" data-bs-target="#bildirimModal"
style="position: fixed;right: 1rem;top: 0.5rem;z-index:100;">
    <?php
    $SS = false;
    $Adet = 0;
    $Bildirimler = $db->Get("Bildirimler")->OrderBy("BildirimTarihi", "DESC")->Where("AliciID", $KullaniciID);
    if($db->Row($Bildirimler) > 0){
        foreach($Bildirimler as $Bildirim){
            if($Bildirim["Okundu"] == false){
                $SS = true;
                $Adet++;
            }
        } 
    }
    if($SS){ ?>
        <i class="fi fi-ss-bell-notification-social-media p-0"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
             <?= $Adet; ?>
          </span>
    <?php }else{ ?>
        <i class="fi fi-ts-bell p-0"></i>
    <?php }
    ?>
</button>
<a href="mesajlarim" type="button" class="btn btn-secondary rounded-circle" style="position: fixed;right: 4rem;top: 0.5rem;z-index:100;">
    <?php
    $SS = false;
    $Adet = 0;
    $BildirimlerMesaj = $db->Get("messages")->OrderBy("time", "DESC")->Where("receiver_id", $KullaniciID);
    if($db->Row($BildirimlerMesaj) > 0){
        foreach($BildirimlerMesaj as $Bildirim){
            if($Bildirim["okundu"] == false){
                $SS = true;
                $Adet++;
            }
        } 
    }
    if($SS){ ?>
        <i class="fi fi-ss-comment-alt-dots p-0"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
             <?= $Adet; ?>
          </span>
    <?php }else{ ?>
        <i class="fi fi-ts-comment-alt-dots p-0"></i>
    <?php } ?>
</a>
<div class="modal fade" id="bildirimModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-1">
            <h1 class="modal-title fs-5">Bildirimlerim</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <ul class="list-group">
            <?php
            if($db->Row($Bildirimler) > 0){
                foreach($Bildirimler as $Bildirim){ 
                    if(($Bildirim["BildirimTarihi"] + (60*60*24*14)) > time() && $Bildirim["Aktif"] == true){
                ?>
                        <li class="list-group-item cursor-pointer" onclick="location.href='<?php
                    $Kategori = $Bildirim["BildirimKategoriID"];
                    $Url = (string)$Bildirim["BildirimIcerikID"];
                    $IcerikLink = "";
                    if($Kategori == 1){
                        if($db->Row($db->Get("Icerikler")->Where("IcerikID", "$Url")) > 0){
                            $IcerikLink = "post/" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YayimTarihi") . "-" . $Bildirim["KullaniciID"];
                        }
                    }elseif($Kategori == 2){
                        if($db->Row($db->Get("Icerikler")->Where("IcerikID", "$Url")) > 0){
                            $IcerikLink = "post/" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YayimTarihi") . "-" . $KullaniciID;
                        }
                    }elseif($Kategori == 6){
                        $Ayir = explode(",", $Url);
                        $Url = $Ayir[0];
                        $Yorum = $Ayir[1];
                        if($db->Row($db->Get("Icerikler")->Where("IcerikID", "$Url")) > 0){
                            $IcerikLink = "post/" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YayimTarihi") . "-" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YazarID") . "#PostYorum_" . $Yorum;
                        }
                    }elseif($Kategori == 7){
                        $Ayir = explode(",", $Url);
                        $Url = $Ayir[0];
                        $Yorum = $Ayir[1];
                        if($db->Row($db->Get("Icerikler")->Where("IcerikID", "$Url")) > 0){
                            $IcerikLink = "post/" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YayimTarihi") . "-" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YazarID") . "#YorumCevapLi" . $Yorum;
                        }
                    }elseif($Kategori == 8){
                        $Ayir = explode(",", $Url);
                        $Url = $Ayir[0];
                        $Yorum = $Ayir[1];
                        if($db->Row($db->Get("Icerikler")->Where("IcerikID", "$Url")) > 0){
                            $IcerikLink = "post/" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YayimTarihi") . "-" . $db->Get("Icerikler")->Where("IcerikID", $Url, "YazarID") . "#CevapYorumLi" . $Yorum;
                        }
                    }
                    echo $IcerikLink;
                    ?>'">
                        <?php
                            if($Kategori == 2 || $Kategori == 1 || $Kategori == 4 || $Kategori == 6 || $Kategori == 7 || $Kategori == 8){
                                $Gonderen = $Bildirim["KullaniciID"];
                                $GonderenImg = $db->Get("Kullanici")->Where("KullaniciID", $Gonderen, "ProfilResmi");
                                $GonderenKullaniciAdi = $db->Get("Kullanici")->Where("KullaniciID", $Gonderen, "KullaniciAdi");
                                $GonderenOnaylandi = $db->Get("Kullanici")->Where("KullaniciID", $Gonderen, "Onaylandi");
                                ?>
                                <a href="u/<?= $GonderenKullaniciAdi; ?>">
                                <img class="me-0 rounded-circle" 
                                style="width: 40px;
                                height: 40px;
                                background-image: url(images/<?= $GonderenImg; ?>);
                                background-position: center;background-size: cover;"></a>
                                <?php }else{ ?>
                                <img class="me-0 rounded-circle" 
                                style="width: 40px;
                                height: 40px;
                                background-image: url(images/internet.png);
                                background-position: center;background-size: cover;">
                                <?php }
                            echo $Bildirim["BildirimIcerik"]; 
                            if($Bildirim["Okundu"] == false){
                                echo '<span class="position-absolute top-0 start-100 translate-middle p-2 bg-primary border border-light rounded-circle"></span>';
                            }
                        ?>
                    </li>
            <?php } }
            }else{ ?>
                    <li class="list-group-item">Henüz bildiriminiz yok...</li>
            <?php } ?>
        </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Tamam</button>
        </div>
    </div>
  </div>
</div>