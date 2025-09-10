<?php
if(isset($_GET["user"]) && isset($_GET["check"]) && $_GET["check"] == "OK"){
    if($_GET["user"] == md5($KullaniciID)){
        if(empty($_SESSION["HesapDondurmaKodu"])){
            $kod = $_SESSION["HesapDondurmaKodu"] = kod();
            mailGonder(
                $Kullanici['Email'],
                "Hesap Dondurma Kodunuz: $kod",
                "Hesap dondurmak için gereken kodunuz: <b><h2>$kod</h2></b>
                 <p>Hesap dondurmak hakkında daha fazla bilgi için 
                 <a href='https://talk.svenyazilim.com/hesap-dondur'>tıklayınız</a>.</p>"
            );
        }
    }else{ ?>
        <div class="alert alert-danger mt-3 col-11 m-auto" role="alert">
          <i class="fi fi-ss-diamond-exclamation fs-3"></i>Lütfen hesap bilgilerinizi kontrol ediniz!
        </div>
    <?php exit; }
?>
<?php }

if(isset($_GET["user"]) && isset($_SESSION["HesapDondurmaKodu"])){
        ?>
            <div class="alert alert-primary mt-3 col-11 m-auto" role="alert">
                <p class="mb-1">Mail adresinize gelen doğrulama kodunuzu aşağıdaki alana girerek hesap dondurma işlemini tamamlayabilirsiniz.</p>
                <div class="input-group">
                    <span class="input-group-text">Kod:</span>
                    <input autofocus class="form-control color-modal" type="tel" id="kod">
                    <button class="btn btn-primary" data-user-id="<?= $KullaniciID ?>" id="HesapDondurmaKontrol">Kontrol</button>
                </div>
                <p class="text-danger fw-bold" id="sonuc"></p>
            </div>
        <?php

} 
?>
<title><?= $SiteAdi; ?> - Hesap Dondurma Hakkında</title>
<div class="card mt-3 col-11 m-auto">
    <div class="card-body color shadow">
        <h3>Hesap Dondurma Hakkında</h3>
        <ul class="navbar-nav">
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Hesap dondurabilmeniz için hesabınıza tanımlı bir mail adresi olması lazım.</li>
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Dondurulan hesap diğer kullancılar tarafından görüntülenemez.</li>
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Dondurulan hesabın paylaşımları gizlenir.</li>
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Dondurulan hesaba tekrar giriş yapmak için hesabınıza tanımlı mail adresine ulaşabilmeniz lazım veya teknik ekibimizle iletişime geçmeniz gerekli.</li>
        </ul>
        <?php
        if(isset($_GET["user"]) && $KullaniciID && $Kullanici['Email']){
            echo '<a href="?user='.$_GET["user"].'&check=OK" class="btn btn-danger mt-2">Hesabımı dondur</a>';
        }
        ?>
        
    </div>
</div>