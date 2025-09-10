<?php
if(isset($_GET["check"]) && $_GET["check"] == "OK"){
    if(isset($_SESSION["HesapYuklemeKodu"]) && isset($_SESSION["HesapYuklemeID"])){
        ?>
            <div class="alert alert-primary mt-3 col-11 m-auto" role="alert">
                <p class="mb-1">Mail adresinize gelen doğrulama kodunuzu aşağıdaki alana girerek hesap dondurma işlemini tamamlayabilirsiniz.</p>
                <div class="input-group">
                    <span class="input-group-text">Kod:</span>
                    <input autofocus class="form-control color-modal" type="tel" id="kod">
                    <button class="btn btn-primary" data-user-id="<?= $_SESSION["HesapYuklemeID"]; ?>" id="HesapYuklemeKontrol">Kontrol</button>
                </div>
                <p class="text-danger fw-bold" id="sonuc"></p>
            </div>
        <?php

} }
?>
<title><?= $SiteAdi; ?> - Hesap Yükleme Hakkında</title>
<div class="card mt-3 col-11 m-auto">
    <div class="card-body color shadow">
        <h3>Hesap Yükleme Hakkında</h3>
        <ul class="navbar-nav">
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Geri yüklenen hesap diğer kullancılar tarafından görüntülenebilir.</li>
            <li><i class="fi fi-ss-diamond-exclamation text-warning"></i>Geri yüklenen hesabın paylaşımları açılır.</li>
        </ul>
        <label class="mt-3">E-Mail Adresiniz:</label>
        <input class="form-control color-modal" id="EMailAdres">
        <a id="HesapYukle" class="btn btn-success mt-2">Hesabımı geri yükle</a>
    </div>
</div>