<?php
        if(empty($_SESSION["HesapAcmaKodu"])){
            $kod = $_SESSION["HesapAcmaKodu"] = kod();
            mailGonder(
                $Kullanici['Email'],
                "Hesap Açma Kodunuz: $kod",
                "Hesap açmak için gereken kodunuz: <b><h2>$kod</h2></b>
                 <p>Hesap açma hakkında daha fazla bilgi için 
                 <a href='https://talk.svenyazilim.com/hesap-ac'>tıklayınız</a>.</p>"
            );
        }
?>
<?php
if(isset($_GET["check"]) && $_GET["check"] == "OK"){
    if(isset($_SESSION["HesapAcmaKodu"]) && isset($_SESSION["HesapAcmaID"])){
        ?>
            <div class="alert alert-primary mt-3 col-11 m-auto" role="alert">
                <p class="mb-1">Mail adresinize gelen doğrulama kodunuzu aşağıdaki alana girerek hesap dondurma işlemini tamamlayabilirsiniz.</p>
                <div class="input-group">
                    <span class="input-group-text">Kod:</span>
                    <input autofocus class="form-control color-modal" type="tel" id="kod">
                    <button class="btn btn-primary" data-user-id="<?= $_SESSION["HesapAcmaID"]; ?>" id="HesapAcmaKontrol">Kontrol</button>
                </div>
                <p class="text-danger fw-bold" id="sonuc"></p>
            </div>
        <?php

} }
?>
<title><?= $SiteAdi; ?> - Hesap Açma Hakkında</title>
<div class="card mt-3 col-11 m-auto">
    <div class="card-body color shadow">
        <h3>Hesap Açma Hakkında</h3>
        <ul class="navbar-nav">
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Açılan hesap diğer kullancılar tarafından görüntülenebilir.</li>
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Açılan hesabın paylaşımları herkese açılır.</li>
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>İstediğiniz zaman tekrar hesabınızı dondurabilirsiniz.</li>
        </ul>
        <label class="mt-3">E-Mail Adresiniz:</label>
        <input class="form-control color-modal" id="EMailAdres">
        <a id="HesapAc" class="btn btn-success mt-2">Hesabımı aç</a>
    </div>
</div>