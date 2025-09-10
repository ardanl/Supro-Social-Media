<!doctype html>
<html lang="tr">
<?php
include "connection.php";
$SistemAyarlar = $db->Get("SistemAyarlar");

$jsVersion = "1.3.8";
$cssVersion = "1.0.8";

$Bakim = false;
$Baslangic = "16:30";
$Bitis = "00:00";

$Link = $SistemAyarlar->Where("AyarAdi", "Link", "AyarDegeri");
$SiteAdi = $SistemAyarlar->Where("AyarAdi", "SiteAdi", "AyarDegeri");
if (isset($_SESSION['current_page'])) {
    $_SESSION['last_page'] = $_SESSION['current_page'];
}
$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];
if(isset($_COOKIE["KullaniciID"])){
    $IpAdresi = $_SERVER['REMOTE_ADDR'];
    $KullaniciID = $_COOKIE["KullaniciID"];
    $Kullanici = $db->Get("Kullanici")->Where("KullaniciID", "$_COOKIE[KullaniciID]");
    if($db->Row($Kullanici) > 0){
        $Kullanici = $Kullanici[0];
        $KullaniciAdi = $_SESSION["KullaniciAdi"] = $Kullanici["KullaniciAdi"];
        $KullaniciProfilResmi = $Kullanici["ProfilResmi"];
        $Yetki = $Kullanici["Yetki"];
        $Puan = $Kullanici["Puan"];
        $Ad = $Kullanici["Ad"];
        if($Ad == ""){
            $Ad = "İsimsiz";
        }
        $Color = $Kullanici["Color"];
        $Onaylandi = $Kullanici["Onaylandi"];
        
        if($Kullanici["Aktif"] == false){
            header("location: exit.php");
        }
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $device = getDeviceType($userAgent);
        $browser = getBrowser($userAgent);
        $os = getOS($userAgent);
    
        $db->Update("Kullanici",
        [
            "SonGorunme" => time(),
            "IpAdresi" => $IpAdresi
        ],
        [
            "KullaniciID" => $KullaniciID
        ]);
        
        if($Puan < 0){
            $db->Update("Kullanici",
            [
                "Puan" => 0   
            ],
            [
                "KullaniciID" => $KullaniciID
            ]);
        }
        $DavetKodu = $db->Get("KayitKod")->Where("KayitKodKullaniciID", $KullaniciID, "KayitKodNo");
        if($db->Row($db->Get("KayitKod")->Where("KayitKodKullaniciID", $KullaniciID)) == 0){
            $DavetKodu = davetKodu("$KullaniciAdi");
             
            $db->Insert("KayitKod",
            ["KayitKodKullaniciID" => $KullaniciID,
            "KayitKodNo" => $DavetKodu,
            "KayitKodTarih" => time()]); ?>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>
            window.onload = function () {
            // Daha önce konfeti çalıştı mı?
            if (!sessionStorage.getItem('confettiShown')) {
                // Konfeti çalıştır
                confetti({
                particleCount: 150,
                spread: 100,
                origin: { y: 0.2 },
                startVelocity: 30,
                });

                // Artık konfeti gösterildiğini kaydet
                sessionStorage.setItem('confettiShown', 'true');
            }
            };
        </script>
        <?php } 
        echo "<script> const KullaniciAdi = '$KullaniciAdi'; const Onaylandi = '$Onaylandi'; const Color = '$Color'; const Ad = '$Ad'; const ProfilResmi = '$KullaniciProfilResmi'</script>";
        $TumKullanicilarGet = $db->Get("Kullanici")->Where("Aktif", true);
        
        $TumKullanicilar = array_map(function($k) {
            return $k["KullaniciAdi"];
        }, $TumKullanicilarGet);
    
        if($Yetki < 3 || $Yetki == 6){
            $Bakim = false;
        }
    }else{
        header("location: https://supro.com.tr/exit.php");
    }
}else{
    $KullaniciID = false;
}

// ÜÇ AY GİRİLMEZSE HESAPLAR DONDURULUR 
$BirAy = (32 * 24 * 60 * 60) * 3;
$Simdi = time(); 
if(isset($TumKullanicilarGet)){
    foreach($TumKullanicilarGet as $KullaniciHesap){
        if($KullaniciHesap["SonGorunme"] < ($Simdi - $BirAy) && $KullaniciHesap["SonGorunme"] != 0){
            $db->Update("Kullanici", 
            [
                "Aktif" => false    
            ],
            [
                "KullaniciID" => $KullaniciHesap["KullaniciID"]
            ]);
        }
    }
}

$Silinecekler = $db->Get("Kullanici")->Where("Silindi", 1);
if($db->Row($Silinecekler) > 0){
    foreach($Silinecekler as $Sil){
        // 32 GÜN SONRA HESABI VERİTABANLARINDAN SİL
        $Hesapla = $Sil["SilinmeTarihi"] + (60 * 60 * 24 * 32);
        if(time() > $Hesapla){
            $db->Delete("Kullanici", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Begeni", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Favoriler", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Icerikler", ["YazarID" => $Sil["KullaniciID"]]);
            $db->Delete("IcerikSikayet", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("KullaniciLoglari", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Sikayet", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Sikayet", ["SikayetKim" => $Sil["KullaniciID"]]);
            $db->Delete("Takipler", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Yorumlar", ["KullaniciID" => $Sil["KullaniciID"]]);
            $db->Delete("Bildirimler", ["AliciID" => $Sil["KullaniciID"]]);
            unlink("images/" . $Sil["ProfilResmi"]);
        }
    }
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="google-site-verification" content="2vkyccGnBudlhdUpI4vqNqXEvA6b-aSQszgdL-7QSpA" />
    <!-- FAVICON -->
    <link rel="icon" href="<?= $Link; ?>images/SuperIcon.png">
    <link rel="apple-touch-icon" href="<?= $Link; ?>images/SuperIcon.png">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- ICONS -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-duotone-chubby/css/uicons-duotone-chubby.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-chubby/css/uicons-solid-chubby.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-chubby/css/uicons-thin-chubby.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <!-- HARİCİ CSS -->
    <link rel='stylesheet' href='<?= $Link; ?>assets/style.css?v<?= $cssVersion; ?>'>
    <?php if($KullaniciID){ ?>
    <script>
      const TumKullanicilar = <?php echo json_encode($TumKullanicilar); ?>;
    </script>
    <?php } ?>
</head>

<body class="dark pb-5" data-bs-theme="dark">
    <div class="col-md-10 col-12 m-auto container pb-3 px-0">
        <p class="d-none">
            Supro; insanların fikirlerini, deneyimlerini ve içeriklerini özgürce paylaşabildiği, etkileşim kurabildiği modern bir sosyal medya ve paylaşım platformudur.
            <br><br>Kullanıcılar burada gönderiler oluşturabilir, tartışmalara katılabilir, anketlere katılabilir ve topluluklarla iletişim halinde kalabilir. 
            <br><br>Supro’nun amacı; sadece içerik paylaşmak değil, aynı zamanda insanların kendini ifade edebileceği, yeni insanlarla tanışabileceği ve ortak ilgi alanlarında buluşabileceği güvenli ve dinamik bir ortam sunmaktır.
            <br><br>Sende 1 dakikada hesabını oluşturarak bizlere destek olarak katılabilirsin. 🙂
        </p>
        <?php
        if($Bakim){
            include "bakim.php";
        }
        if (isset($_GET["seo"])) {
            $k = explode("/", rtrim($_GET["seo"], "/"));
            $dosya_kategori = $k[0];
            if($KullaniciID){
                include "navbar.php";
            }
        } else {
            $k[0] = "";
            include "navbar.php";
        }
        if(!$KullaniciID && $k[0] != "giris" && $k[0] != "yetkisiz" && $k[0] != "mail-dogrula" && $k[0] != "kayit" && isset($_SESSION["login"])){
            header("location: mail-dogrula");
        }
        if(!$KullaniciID && $k[0] != "giris" && $k[0] != "yetkisiz" && $k[0] != "mail-dogrula" && $k[0] != "kayit" && $k[0] != "kvkk" && $k[0] != "cerezler" && $k[0] != "topluluk-kurallari" && $k[0] != "odeme-basarili"){
            header("location: giris");
        }
        if($Bakim){
            include "bakim.php";
        }
        if (isset($_GET["seo"])) {
            if (file_exists($dosya_kategori . ".php")) {
                include $dosya_kategori . ".php";
            } else {
                $icerik = $conn->prepare("SELECT * FROM Kategori WHERE KategoriLink='$dosya_kategori'");
                $icerik->execute();
                $icerikgetir = $icerik->fetch(PDO::FETCH_ASSOC);
                if ($icerikgetir) {
                    $baslik_kategori = $icerikgetir['KategoriAciklama'];
                    if (isset($k[1])) {
                        include "detail.php";
                    } else {
                        include "list.php";
                    }
                } else {
                    include "error.php";
                }
            }
        } else {
            include "home.php";
        }
        include "footer.php";
        ?>
    </div>
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JQUERY -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- HARİCİ JS -->
    <script>
        const webLink = "https://supro.com.tr/";
    </script>
    <script src="<?= $Link; ?>assets/script.js?v<?= $jsVersion; ?>"></script>
</body>

</html>