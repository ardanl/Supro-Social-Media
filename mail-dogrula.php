<title><?= $SiteAdi; ?> - E-Mail Doğrula</title>
<?php
if(isset($_GET["user"]) && isset($_GET["check"]) && $_GET["check"] == "OK"){
    if($_GET["user"] == md5($KullaniciID) || isset($_SESSION["login"])){
        if(empty($_SESSION["EMailDogrulamaKodu"])){
            $kod = $_SESSION["EMailDogrulamaKodu"] = kod();
            if(isset($_SESSION["login"])){
                $EMailGonderilen = $_SESSION["login"];
            }else{
                $EMailGonderilen = $Kullanici['Email'];
            }
            mailGonder(
                $EMailGonderilen,
                "E-Mail Doğrulama Kodunuz: $kod",
                "E-Mail doğrulamak için gereken kodunuz: <b><h2>$kod</h2></b>
                 <p>E-Mail doğrulama hakkında daha fazla bilgi için 
                 <a href='http://supro.com.tr/mail-dogrula'>tıklayınız</a>.</p>"
            );
        }
    }else{ ?>
        <div class="alert alert-danger mt-3 col-11 m-auto" role="alert">
          <i class="fi fi-ss-diamond-exclamation fs-3"></i>Lütfen hesap bilgilerinizi kontrol ediniz!
        </div>
    <?php exit; }
?>
<?php }

if(isset($_GET["k"])){
    $_SESSION["k"] = $_GET["k"];
    $KullaniciHashID = md5($db->Get("Kullanici")->Where("KullaniciAdi", "$_GET[k]", "KullaniciID"));
    header("location: mail-dogrula?user=".$KullaniciHashID."&check=OK");
    if(empty($_SESSION["login"]) || empty($_SESSION["loginID"])){
        $_SESSION["loginID"] = $db->Get("Kullanici")->Where("KullaniciAdi", "$_GET[k]", "KullaniciID");
        $_SESSION["login"] = $db->Get("Kullanici")->Where("KullaniciAdi", "$_GET[k]", "Email");
    }
}

if(isset($_GET["user"]) && isset($_SESSION["EMailDogrulamaKodu"])){
    if(isset($_SESSION["login"]) && isset($_SESSION["k"])){
        $KullaniciAdi = $_SESSION["k"];
        $KullaniciID = $_SESSION["loginID"];
    }
        ?>
            <div class="alert alert-primary mt-3 col-11 m-auto" role="alert">
                <p class="mb-1">Mail adresinize gelen doğrulama kodunuzu aşağıdaki alana girerek mail doğrulama işlemini tamamlayabilirsiniz.</p>
                <div class="input-group">
                    <span class="input-group-text">Kod:</span>
                    <input autofocus class="form-control color-modal" type="tel" id="kod">
                    <button class="btn btn-primary" data-username="<?= $KullaniciAdi; ?>" data-user-id="<?= $KullaniciID ?>" id="EMailKontrol">Kontrol</button>
                </div>
                <p class="text-danger fw-bold" id="sonuc"></p>
            </div>
        <?php
$KullaniciID = null;
} 
?>
<div class="card mt-3 col-11 m-auto">
    <div class="card-body color shadow">
        <h3>E-Mail Doğrula</h3>
        <ul class="navbar-nav">
            <li><i class="fi fi-ss-shield-check text-success"></i>E-Mailinizi doğrulayarak çeşitli avantajlardan faydalanabilirsiniz.</li>
            <li><i class="fi fi-ss-shield-check text-success"></i>E-Mailinizi doğrulayarak hesabınızı yüksek güvenlik seviyesine çıkarabilirsiniz.</li>
        </ul>
        <?php
        if(isset($_GET["user"]) && $KullaniciID && $Kullanici['Email']){
            echo '<a href="?user='.$_GET["user"].'&check=OK" class="btn btn-primary mt-2">E-Mail doğrula</a>';
        }
        if($KullaniciID && $Kullanici["EpostaDogrulandi"] == true){
            echo '<a class="btn btn-success mt-2"><i class="fi fs-5 fi-ts-shield-check"></i>E-MAIL DOĞRULANDI</a>';
        }
        ?>
    </div>
</div>