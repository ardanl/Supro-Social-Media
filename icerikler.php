<?php
$Icerikler = $db->Get("Icerikler")->OrderBy("YayimTarihi", "DESC")->Where("Durum", true);
$Kontrol = true;
$i = 0;
foreach($Icerikler as $Icerik){
    if($i >= 100){
        $Kontrol = false;
    }
    if($Kontrol){
    if($Icerik["Yasak"] == false && $Icerik["Sabit"] == true){
    $Kullanici = $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"]);
    $Kullanici = $Kullanici[0];
    if($Kullanici["Aktif"] == 1 && $Kullanici["Silindi"] == 0){
?>
    <div>
        <div class="card color my-1 border-0" style>
            <div class="card-body pt-2 pb-0">
                <a class="color" href="u/<?= $Kullanici["KullaniciAdi"]; ?>">
                    <?php 
                    echo '<i class="fi fi-ss-thumbtack fs-4 text-danger" style="position: absolute;left: 3.6rem;top: -4px;"><span class="fs-6 position-absolute ps-1">Sabitlendi<span></i>';
                    if($Kullanici["ProfilResmi"] != ""){ 
                        echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(images/'.$Kullanici["ProfilResmi"].');background-position: center;background-size: cover;">';
                    }else{ 
                        echo '<i class="fi fi-ss-user fs-1"></i>'; 
                    }
                    if($Kullanici["Ad"] == ""){
                        $Kullanici["Ad"] = "İsimsiz Kullanıcı";
                    }
                    echo "<span class='text-white'>".$Kullanici["Ad"]." ". $Kullanici["Soyad"];
                    if($Kullanici["Onaylandi"] == true){
                        if($Kullanici["Color"]){
                            $Color = $Kullanici["Color"];
                            echo '<i class="fi tick fi-ss-badge-check ' . $Color . '"></i>';
                        }else{
                            echo '<i class="fi fi-ss-badge-check text-primary"></i>';
                        }
                    }
                    echo "<span class='text-muted'> ● @".$Kullanici["KullaniciAdi"]."</span></span>"; 
                    ?>
                </a>
                <div>
                    <div class="color d-block cursor-pointer mt-2" onclick="location.href='post/<?= $Icerik["YayimTarihi"] ."-".$Kullanici["KullaniciID"]; ?>'">
                        <?php
                        if($Kullanici["Yetki"] != 6){
                            echo karakterKes($Icerik["IcerikMetni"], 400);
                        }else{
                            echo $Icerik["IcerikMetni"];
                        }?>
                    </div>
                    <?php if($Icerik["IcerikResim"]){ ?>
                    <p class="text-center">
                        <a href="post/<?= $Icerik["YayimTarihi"] . "-" . $Kullanici["KullaniciID"]; ?>" class="d-inline-block col-11 m-auto" style="background-image: url(images/<?= $Icerik["IcerikResim"] ?>);height: 21rem;background-position: center;background-size: contain; background-repeat: no-repeat;"></a>
                    </p>
                    <?php } ?>
                    <div class="row align-items-center">
                        <a href="post/<?= $Icerik["YayimTarihi"] . "-" . $Kullanici["KullaniciID"]; ?>" class="mt-1 col-6 text-muted fst-italic">
                            <i class="fi fi-ts-calendar"></i>
                            <?= date("d.m.Y - H:i", $Icerik["YayimTarihi"]); ?>
                        </a>
                        <a href="post/<?= $Icerik["YayimTarihi"] . "-" . $Kullanici["KullaniciID"]; ?>" class="mt-1 col-3 text-end text-muted fst-italic">
                            <i class="fi fi-ts-comment-dots"></i>
                            <?= $db->Row($db->Get("Yorumlar")->Where("IcerikID", $Icerik["IcerikID"])); ?>
                        </a>
                        <p class="mt-1 col-3 text-end text-danger fw-bold">
                            <i class="fi <?php
                            if ($KullaniciID) {
                                $Bul = $db->Get("Begeni")->Where("KullaniciID", $KullaniciID);
                                $begeniVar = false;
                            
                                if ($db->Row($Bul) > 0) {
                                    foreach ($Bul as $value) {
                                        if ($value["IcerikID"] == $Icerik["IcerikID"]) {
                                            $begeniVar = true;
                                            break; // eşleşme bulunduysa döngüyü kır
                                        }
                                    }
                                }
                            
                                echo $begeniVar ? "fi-ss-heart" : "fi-rr-heart";
                            
                            } else {
                                echo "fi-ss-heart";
                            }
                        ?> fs-2 cursor-pointer" <?php
                            if($KullaniciID){ echo "onclick='begen($KullaniciID, $Icerik[IcerikID], this, $Icerik[YazarID])'"; } 
                        ?>></i>
                            <span id="BegeniText_<?= $Icerik["IcerikID"]; ?>"><?= $db->Row($db->Get("Begeni")->Where("IcerikID", $Icerik["IcerikID"])); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="m-0">
<?php $i++; } } } }

$Icerikler = $db->Get("Icerikler")->OrderBy("YayimTarihi", "DESC")->Where("Durum", true);
$Kontrol = true;
$i = 0;
foreach($Icerikler as $Icerik){
    if($i >= 100){
        $Kontrol = false;
    }
    if($Kontrol){
    if($Icerik["Yasak"] == false && $Icerik["Sabit"] == false){
    $Kullanici = $db->Get("Kullanici")->Where("KullaniciID", $Icerik["YazarID"]);
    $Kullanici = $Kullanici[0];
    if($Kullanici["Aktif"] == 1 && $Kullanici["Silindi"] == 0){
?>
    <div>
        <div class="card color my-1 border-0" style>
            <div class="card-body pt-2 pb-0">
                <a class="color" href="u/<?= $Kullanici["KullaniciAdi"]; ?>">
                    <?php 
                    if($Kullanici["ProfilResmi"] != ""){ 
                        echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(images/'.$Kullanici["ProfilResmi"].');background-position: center;background-size: cover;">';
                    }else{ 
                        echo '<i class="fi fi-ss-user fs-1"></i>'; 
                    }
                    if($Kullanici["Ad"] == ""){
                        $Kullanici["Ad"] = "İsimsiz Kullanıcı";
                    }
                    echo "<span class='text-white'>".$Kullanici["Ad"]." ". $Kullanici["Soyad"];
                    if($Kullanici["Onaylandi"] == true){
                        if($Kullanici["Color"]){
                            $Color = $Kullanici["Color"];
                            echo '<i class="fi tick fi-ss-badge-check ' . $Color . '"></i>';
                        }else{
                            echo '<i class="fi fi-ss-badge-check text-primary"></i>';
                        }
                    }
                    echo "<span class='text-muted'> ● @".$Kullanici["KullaniciAdi"]."</span></span>"; ?>
                </a>
                <div>
                    <div class="color d-block cursor-pointer mt-2" onclick="location.href='post/<?= $Icerik["YayimTarihi"] ."-".$Kullanici["KullaniciID"]; ?>'">
                        <?php
                        if($Kullanici["Yetki"] != 6){
                            echo karakterKes($Icerik["IcerikMetni"], 200);
                        }else{
                            echo $Icerik["IcerikMetni"];
                        }?>
                    </div>
                    <?php if($Icerik["IcerikResim"]){ ?>
                    <p class="text-center">
                        <a href="post/<?= $Icerik["YayimTarihi"] . "-" . $Kullanici["KullaniciID"]; ?>" class="d-inline-block col-11 m-auto" style="background-image: url(images/<?= $Icerik["IcerikResim"] ?>);height: 21rem;background-position: center;background-size: contain; background-repeat: no-repeat;"></a>
                    </p>
                    <?php } ?>
                    <div class="row align-items-center">
                        <a href="post/<?= $Icerik["YayimTarihi"] . "-" . $Kullanici["KullaniciID"]; ?>" class="mt-1 col-6 text-muted fst-italic">
                            <i class="fi fi-ts-calendar"></i>
                            <?= date("d.m.Y - H:i", $Icerik["YayimTarihi"]); ?>
                        </a>
                        <a href="post/<?= $Icerik["YayimTarihi"] . "-" . $Kullanici["KullaniciID"]; ?>" class="mt-1 col-3 text-end text-muted fst-italic">
                            <i class="fi fi-ts-comment-dots"></i>
                            <?= $db->Row($db->Get("Yorumlar")->Where("IcerikID", $Icerik["IcerikID"])); ?>
                        </a>
                        <p class="mt-1 col-3 text-end text-danger fw-bold">
                            <i class="fi <?php
                            if ($KullaniciID) {
                                $Bul = $db->Get("Begeni")->Where("KullaniciID", $KullaniciID);
                                $begeniVar = false;
                            
                                if ($db->Row($Bul) > 0) {
                                    foreach ($Bul as $value) {
                                        if ($value["IcerikID"] == $Icerik["IcerikID"]) {
                                            $begeniVar = true;
                                            break; // eşleşme bulunduysa döngüyü kır
                                        }
                                    }
                                }
                            
                                echo $begeniVar ? "fi-ss-heart" : "fi-rr-heart";
                            
                            } else {
                                echo "fi-ss-heart";
                            }
                        ?> fs-2 cursor-pointer" <?php
                            if($KullaniciID){ echo "onclick='begen($KullaniciID, $Icerik[IcerikID], this, $Icerik[YazarID])'"; } 
                        ?>></i>
                            <span id="BegeniText_<?= $Icerik["IcerikID"]; ?>"><?= $db->Row($db->Get("Begeni")->Where("IcerikID", $Icerik["IcerikID"])); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="m-0">
<?php $i++; } } } }
?>