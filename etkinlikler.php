<div class="container">
    <?php
    $i = 1;
    $Etkinlikler = $db->Get("Etkinlikler", true);
    foreach($Etkinlikler as $Etkinlik){ 
    if($Etkinlik["EtkinlikTarihi"] >= time()){
        if($i == 1){
            echo "<h4 class='mt-3'><i class='fi fi-ts-rocket-lunch'></i> Etkinlikler</h4>";
            $i++;
        }
    ?>
    <div class="alert alert-primary my-2" role="alert">
        <p>
            <?php if($Etkinlik["EtkinlikAdi"]){ ?>
            <p><b><?= $Etkinlik["EtkinlikAdi"]; ?></b></p>
            <?php } if($Etkinlik["EtkinlikAciklama"]){ ?>
            <p><?= $Etkinlik["EtkinlikAciklama"]; ?></p>
            <?php } if($Etkinlik["EtkinlikTarihi"]){ ?>
            <div class="row">
                <p class="col"><?= date("d.m.Y - H:i", $Etkinlik["EtkinlikTarihi"]); ?></p>
                <?php } if($Etkinlik["EtkinlikYer"]){ ?>
                <p class="col text-end"><?= $Etkinlik["EtkinlikYer"]; ?></p>
            </div>
            <?php }
            ?>
        </p>
        <?php
        if($db->Row($Etkinlikler) > 1){
            echo '<hr>';
        }
        ?>
    </div>
    <?php } } ?>
</div>