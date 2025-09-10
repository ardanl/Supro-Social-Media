<nav class="fixed-bottom row text-center dark pt-2 border-top border-secondary" style="box-shadow: 0 -15px 28px black;">
    <a href="<?= $Link; ?>" class="col nav-link">
        <i class="fi fi-<?= empty($k[0]) ? "sc-home" : "tc-home"; ?> fs-2 p-0"></i>
    </a>
    <?php if(($KullaniciID)){ ?>
    <a href="<?= $Link; ?>paylas" class="col nav-link">
        <i class="fi fi-<?= $k[0] == "paylas" ? "sc-add" : "tc-add"; ?> fs-2 p-0"></i>
    </a>
    <?php }
    ?>
    <a href="<?= $Link; ?>diger" class="col nav-link">
        <i class="fi fi-<?= $k[0] == "diger" ? "sc-search" : "tc-search"; ?> fs-2 p-0"></i>
    </a>
    <a href="<?= $Link; ?>alisveris" class="col nav-link">
        <i class="fi fi-<?= $k[0] == "alisveris" ? "sc-basket-shopping-simple" : "tc-basket-shopping-simple"; ?> fs-2 p-0"></i>
    </a>
    <a href="<?php echo $Link; if($KullaniciID){ echo  "u/".$Kullanici["KullaniciAdi"]; }else{ echo "giris"; } ?>" class="col nav-link">
        <?php
            if($KullaniciID){ ?>
                <img class="rounded-circle" style="width: 2rem;height: 2rem;background-image: url(<?= $Link; ?>images/<?= $KullaniciProfilResmi ?>);background-position: center;background-size: cover;">
            <?php }else{ ?>
            <i class="fi fi-<?= ($k[0] == "u" && isset($k[1])) && $KullaniciID && $k[1] == $Kullanici["KullaniciAdi"] || $k[0] == "giris" ? "ss" : "tr"; ?>-circle-user fs-1"></i>
        <?php } ?></a>
</nav>