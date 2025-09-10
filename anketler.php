<?php
$Etkinlikler = $db->Get("Anketler", true); 
$Yaz = false; // varsayılan
foreach($Etkinlikler as $Etkinlik){
    if($Etkinlik["AnketTarihi"] >= time()){
        $Yaz = true;
        break; // burada break mantıklı çünkü EN AZ 1 tane yeterli
    }
}
if($Yaz){
?>
<div class="accordion mx-2" id="accordionExample">
  <div class="accordion-item color">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed color" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Anketler
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse <?php
        $Etkinlikler = $db->Get("Anketler", true);
        foreach($Etkinlikler as $Etkinlik){ 
            if($Etkinlik["AnketTarihi"] >= time()){
                $CevapDurum = false;
                $CevapMetin = "";
                $AnketCevaplari = $db->Get("AnketCevaplari")->Where("AnketID", $Etkinlik["AnketID"]);
    
                // Kullanıcının bu ankete cevabı var mı kontrol et
                if($db->Row($AnketCevaplari) > 0){
                    foreach($AnketCevaplari as $AnketCevap){
                        if($AnketCevap["CevapKullanici"] == $KullaniciID){
                            $CevapDurum = true;
                            $CevapMetin = $AnketCevap["CevapMetni"];
                            break;
                        }
                    }
                }
            }
        }
        if(!$CevapDurum){ echo " show"; }
      ?>" data-bs-parent="#accordionExample">
        <div class="color">
            <div class="container pt-3">
    <?php
    $i = 1;
    $Etkinlikler = $db->Get("Anketler", true);
    foreach($Etkinlikler as $Etkinlik){ 
        if($Etkinlik["AnketTarihi"] >= time()){
            $CevapDurum = false;
            $CevapMetin = "";
            $AnketCevaplari = $db->Get("AnketCevaplari")->Where("AnketID", $Etkinlik["AnketID"]);

            // Kullanıcının bu ankete cevabı var mı kontrol et
            if($db->Row($AnketCevaplari) > 0){
                foreach($AnketCevaplari as $AnketCevap){
                    if($AnketCevap["CevapKullanici"] == $KullaniciID){
                        $CevapDurum = true;
                        $CevapMetin = $AnketCevap["CevapMetni"];
                        break;
                    }
                }
            }

            // Cevaplar dizisi
            $Cevaplar = explode(",", $Etkinlik["AnketCevap"]);

            // Oy sayısı hesaplama
            $CevapSayilari = [];
            $ToplamCevap = 0;
            foreach($Cevaplar as $cevap){
                $sayac = 0;
            if($db->Row($AnketCevaplari) > 0){
                foreach($AnketCevaplari as $AnketCevap){
                    if(trim($AnketCevap["CevapMetni"]) == trim($cevap)){
                        $sayac++;
                    }
                }
            }
                $CevapSayilari[$cevap] = $sayac;
                $ToplamCevap += $sayac;
            }
    ?>
    <div class="alert alert-dark mb-3 p-1 px-3" role="alert">
        <i id="AnketTik<?= $Etkinlik["AnketID"]; ?>" class="fi fi-ss-comment-check fs-5" style="position: absolute;left:-0.5rem;top: -0.6rem;color: var(--bs-teal);<?php if(!$CevapDurum){ echo "display:none;"; }?>"></i>
        <?php 
        if($Etkinlik["AnketBaslik"]){ ?>
            <div><b><?= $Etkinlik["AnketBaslik"]; ?></b></div>
            <div id="AnketSecenekleri<?= $Etkinlik["AnketID"]; ?>" class="row row-cols-2 g-1 justify-content-center mt-1">
        <?php } 
        foreach($Cevaplar as $key => $Cevap){
        ?>
            <div class="tek-satir col <?php if($CevapDurum && $CevapMetin != $Cevap){ echo " d-none "; } ?> h-100 m-0">
                <input type="radio" class="btn-check"
                    <?php if($CevapMetin == $Cevap){ echo " checked"; } ?> 
                    name="anket<?= $Etkinlik["AnketID"]; ?>" 
                    id="radiobtn<?= $Etkinlik["AnketID"]; ?>_<?= $key; ?>" 
                    autocomplete="off"  
                    value="<?= $Cevap; ?>">

                <label data-veri="<?= $Cevap; ?>"
                    data-anket="<?= $Etkinlik["AnketID"]; ?>" 
                    class="btn btn-outline-light w-100 anket-cevap-btn 
                    <?php if($CevapDurum){echo " disabled ";} if($CevapDurum && $CevapMetin != $Cevap){ echo " d-none "; } ?>" 
                    for="radiobtn<?= $Etkinlik["AnketID"]; ?>_<?= $key; ?>">
                    <?= $Cevap; ?>
                </label>
            </div>
        <?php } ?>
        </div>

        <div class="mt-2" <?php if(!$CevapDurum){echo 'style="display: none";';} ?>  id="YuzdeKac">
            <?php foreach($Cevaplar as $cevap){ 
                $yuzde = ($ToplamCevap > 0) ? round(($CevapSayilari[$cevap] / $ToplamCevap) * 100) : 0;
                if($CevapSayilari[$cevap] > 0){
            ?>
            <div class="mb-1"><?= $cevap; ?>: (<?= $CevapSayilari[$cevap]; ?> oy)
                <div class="progress" style="height: 1.2rem;">
                    <div class="progress-bar bg-primary fs-6" style="width: <?= $yuzde; ?>%;">
                        <?= $yuzde; ?>%
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>

        <div class="my-2"><b>Bitiş: <?= date("d.m.Y - H:i", $Etkinlik["AnketTarihi"]); ?></b></div>
    </div>
    <?php } } ?>
</div>  
        </div>
    </div>
  </div>
</div>
<?php } ?>