<?php
if($KullaniciID){
    if(isset($_GET["order"])){
        $Order = clean($_GET["order"]);
    }else{
        $Order = "UrunTarih";
    }
    if(isset($_GET["s"])){
        $Sira = clean($_GET["s"]);
    }else{
        $Sira = "DESC";
    }
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<title><?= $SiteAdi; ?> - Alışveriş</title>
<div class="card dark mt-3 mx-2">
    <div class="card-body">
        <?php if($Yetki != 6){ ?>
        <div class="alert alert-warning fw-bold p-1" role="alert" style="position: fixed;z-index: 1;right:1.6rem;top:1.5rem;">
              <img src="../images/scoin.png" width="25">
              <!--<i class="fi fi-sr-cryptocurrency fs-4 p-0"></i>-->
              <span style="padding-left: 3px;" classs="align-middle" id="MyTack"><?= $Kullanici["Puan"]; ?></span>
        </div>
        <?php }
         if(empty($_GET["urun"])){ ?>
        <h4>
            <!--<a href="diger" class="text-white"><i class="fi fi-ts-arrow-circle-left"></i></a>-->
            Mağaza
        </h4>
            <?php } ?>
        <?php
        if(isset($_GET["urun"]) && isset($_GET["urunAd"])){             
            $query = $_GET;
        ?>
        <h4>
            <a href="alisveris" class="text-white"><i class="fi fi-ts-arrow-circle-left"></i></a>
            Alışveriş
        </h4>
        <div class="dropdown">
            <button class="btn btn-outline-warning dropdown-toggle col-4" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Sırala
            </button>
            <ul class="dropdown-menu">
                <!--<li>
                    <a class="dropdown-item <?php if($Order == "UrunTarih"){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => 'UrunTarih'])); ?>">
                    Tarih
                    </a>
                </li>-->
                <li>
                    <a class="dropdown-item <?php if($Order == "UrunTack" || empty($_GET["order"])){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => 'UrunTack'])); ?>">
                    Fiyat
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?php if($Order == "UrunSeviye"){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => 'UrunSeviye'])); ?>">
                    Nadirlik
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?php if($Order == "UrunAdet"){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => 'UrunAdet'])); ?>">
                    Adet
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?php if($Order == "UrunAdi"){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => 'UrunAdi'])); ?>">
                    Ad
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item <?php if($Sira == "DESC"){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => $Order, 's' => 'DESC'])); ?>">
                    Büyük
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?php if($Sira == "ASC"){ echo "active"; } ?>" 
                    href="?<?php echo http_build_query(array_merge($query, ['order' => $Order, 's' => 'ASC'])); ?>">
                    Küçük
                    </a>
                </li>
            </ul>
        </div>    
       <?php  } ?>
        <div class="row row-cols-2 row-cols-md-3 g-1 justify-content-center pt-2">
        <?php
        if(isset($_GET["urun"]) && isset($_GET["urunAd"])){
            $Kontrol = $db->Get("UrunKategori")->Where("UrunKategoriID", $_GET["urun"]);
            if($db->Row($Kontrol) > 0 && $Kontrol[0]["Aktif"] == 1){
            // Varsayılan değerler
            $orderBy = "UrunTack";
            $orderDir = "DESC";

            // GET ile gelen varsa üzerine yaz
            if (!empty($_GET["order"])) {
                $orderBy = $_GET["order"];
            }
            if (!empty($_GET["s"]) && in_array(strtoupper($_GET["s"]), ["ASC", "DESC"])) {
                $orderDir = strtoupper($_GET["s"]);
            }

            // Sorgu
            $Urunler = $db->Get("Urunler")
                        ->OrderBy($orderBy, $orderDir)
                        ->Where("UrunKategori", $_GET["urun"]); 
            if($db->Row($Urunler) > 0){
            ?>
                <div class="col-12">
                    <div class="card dark h-100">
                        <div class="card-body">
                            <h5 class="card-title m-0">
                                <?php echo $Kontrol[0]["UrunKategoriAciklama"]; ?> Paketi <?php if($Kontrol[0]["UrunYeri"] == 1){ echo " - Sticker"; }elseif($Kontrol[0]["UrunYeri"] == 2){ echo " - Banner"; } ?>
                            </h5>
                            <!--<p class="card-text">
                            <?= $Kontrol[0]["UrunKategoriDetay"]; ?>
                            </p>-->
                        </div>
                    </div>
                </div>            
            <?php        
            foreach($Urunler as $Urun){ 
            if($Urun["Aktif"] == true){
            ?>
            <div class="col">
                <div class="card dark h-100">
                    <div class="card-body <?php if($Urun["UrunYeri"] == 2){ echo " pt-0 px-1"; } ?>">
                        <h5 class="card-title text-center sticker-header">
                            <?php
                                    if($Urun["UrunYeri"] == 1){
                                        echo '<img src="images/sticker/'.$Urun["UrunLink"].'.png" style="width: 3rem; margin-right: 3px;" class="d-inline-block"><br>';
                                        echo '<p class="scroll-text">' . $Urun["UrunAdi"] . '</p>';
                                    }elseif($Urun["UrunYeri"] == 2){ ?>
                                    <div style="
                                    height: 4rem; 
                                    background-image: url(../images/banner/<?= $Urun["UrunLink"]; ?>); 
                                    background-size: cover; background-position: center;" class="my-1"></div>
                                    <?php
                                        echo '<p class="scroll-text">' . $Urun["UrunAdi"] . '</p>';
                                    }
                            ?>
                        </h5>
                        <div class="mt-1">
                            <!--<p class="card-text col">
                                <i class="fi fi-ts-category"></i><?= $db->Get("UrunKategori")->Where("UrunKategoriID", $Urun["UrunKategori"], "UrunKategoriAciklama"); ?>
                            </p>-->
                            <?php
                                $Seviye = $db->Get("UrunSeviye")->Where("UrunSeviyeID", $Urun["UrunSeviye"]);
                                $Seviye = $Seviye[0];
                            ?>
                            <p class="card-text col" style="color: <?= $Seviye["UrunSeviyeRenk"]; ?>">
                                <i class="fi fi-ss-<?= $Seviye["UrunSeviyeIcon"]; ?>"></i><span><?= $Seviye["UrunSeviyeAdi"]; ?></span>
                            </p>
                            <p class="card-text col">
                                <i class="fi fi-ts-box-open-full"></i><span id="UrunAdet<?= $Urun["UrunID"]; ?>"><?= $Urun["UrunAdet"]; ?></span>
                            </p>
                        </div>
                        <p class="card-text col mt-1 text-center">
                            <?php
                            $Kontrol = false;
                            $Alisveris = $db->Get("Alisveris")->Where("KullaniciID", $KullaniciID);
                            if($db->Row($Alisveris) > 0){
                                foreach($Alisveris as $Alim){
                                    if($Alim["UrunID"] == $Urun["UrunID"]){
                                        $Kontrol = true;
                                    }
                                }
                            }
                            if($Kontrol || $Yetki == 6){ ?>
                            <button class="btn btn-sm text-white col-10" style="background: linear-gradient(to top, #620707, #ff0000);">Mevcut</button>
                            <?php }else{
                                if($Urun["UrunAdet"] == 0){ ?>
                                    <button class="btn btn-sm text-white col-10" style="background: linear-gradient(to bottom, #011485, #0072ff);">Tükendi</button>
                                <?php }else{ ?>
                                    <button class="btn btn-sm text-white col-10" id="Tukendi<?= $Urun["UrunID"]; ?>"  style="display:none; background: linear-gradient(to bottom, #011485, #0072ff);">Tükendi</button>
                                    <button class="btn btn-sm text-white col-10" id="Mevcut<?= $Urun["UrunID"]; ?>" style="display:none; background: linear-gradient(to top, #620707, #ff0000);">Mevcut</button>
                                    <button class="btn btn-sm text-white col-10 SatinAl" data-urun-id="<?= $Urun["UrunID"]; ?>" data-urun-tack="<?= $Urun["UrunTack"]; ?>" style="background: linear-gradient(to left, #fff3d0, #ffeaac);">
                                        <span class='tick fw-bold g-turuncu'><img src='images/scoin.png' style='width: 25px; margin-right: 5px;'><?= $Urun["UrunTack"]; ?></span>
                                    </button>
                            <?php } } ?>
                            <p class="text-danger fw-bold m-0" id="hata<?= $Urun["UrunID"]; ?>"></p>
                        </p>
                    </div>
                </div>
            </div>
        <?php } } }else{ echo "Henüz bu pakete ürün tanımlanmamıştır!"; } }else{
            header("location: alisveris");            
        }
        }else{
            $Urunler = $db->Get("UrunKategori")->OrderBy("UrunKategoriTarih", "DESC")->Where("Aktif", true);   
            foreach($Urunler as $Urun){
            $Urunler = $db->Get("Urunler")->Where("UrunKategori", $Urun["UrunKategoriID"]);
            if($db->Row($Urunler) > 0){
            ?>
                <div class="col col-12">
                    <a href="?urun=<?= $Urun["UrunKategoriID"]; ?>&urunAd=<?= $Urun["UrunKategoriAciklama"]; ?>" class="card dark h-100 d-flex flex-column">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <h5 class="card-title">
                                <?php echo $Urun["UrunKategoriAciklama"]; ?> Paketi <?php if($Urun["UrunYeri"] == 1){ echo " - Sticker"; }elseif($Urun["UrunYeri"] == 2){ echo " - Banner"; } ?>
                                <?php 
                                $Fiyat = 0;
                                foreach($Urunler as $Urunf){
                                    $Fiyat += $Urunf["UrunTack"];
                                }
                                $indirim = $Fiyat * 25 / 100 ;
                                $Fiyat = $Fiyat - $indirim;
                                ?>
                            </h5>
                            <p class="card-text text-center">
                            <?php
                                $Urunlerimiz = $db->Get("Urunler")->OrderBy("UrunTack", "DESC")->Where("UrunKategori", $Urun["UrunKategoriID"]);        
                                foreach($Urunlerimiz as $UrunDetay){ 
                                    if($UrunDetay["UrunYeri"] == 1){
                                        echo '<img src="images/sticker/'.$UrunDetay["UrunLink"].'.png" style="width: 2rem; margin-right: 3px;">';
                                    }elseif($UrunDetay["UrunYeri"] == 2){ ?>
                                    <div style="
                                    height: 8rem; 
                                    background-image: url(../images/banner/<?= $UrunDetay["UrunLink"]; ?>); 
                                    background-size: cover; background-position: center;" class="my-1"><?= $UrunDetay["UrunAdi"]; ?></div>
                                    <?php }
                                }?>
                            </p>
                            <!--<div class="row row-cols-2 mt-1">
                                <p class="col text-start">Paket Fiyatı:</p>
                                <p class="col text-end"><img width="20" src="images/scoin.png" class="me-1"><span class="tick g-turuncu fw-bold"><?= floor($Fiyat); ?></span></p>
                            </div>-->
                        </div>
                    </a>
                </div>
            <?php } } } ?>  
        </div>
    </div>
</div>
<?php }else{
    include "yetkisiz.php";
}?>