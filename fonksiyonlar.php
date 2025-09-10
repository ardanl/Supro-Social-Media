<?php 
function clean($x)
{
	$x = htmlspecialchars($x);
	//$x = str_replace("'", "''", $x);
    $x = trim($x);
    $x = stripslashes($x);
	return $x;
}
function cinsiyet($cinsiyet) {
    if ($cinsiyet === null) {
        return "-";
    } elseif ($cinsiyet == 1) {
        return "Erkek";
    } elseif ($cinsiyet == 2) {
        return "Kadın";
    } elseif ($cinsiyet == 0) {
        return "Belirtmek istemiyorum";
    } else {
        return "-";
    }
}
function getDeviceType($agent) {
    $agent = strtolower($agent);
    $mobileKeywords = ['iphone', 'android', 'ipad', 'ipod', 'blackberry', 'mobile', 'opera mini', 'windows phone'];
    foreach ($mobileKeywords as $keyword) {
        if (strpos($agent, $keyword) !== false) return "Mobil";
    }
    return "Bilgisayar";
}

function getVideoDuration($videoPath) {
    $output = shell_exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 \"$videoPath\"");
    return (float)$output;
}

function getBrowser($agent) {
    if (strpos($agent, 'Edg') !== false) return "Microsoft Edge";
    elseif (strpos($agent, 'OPR') !== false || strpos($agent, 'Opera') !== false) return "Opera";
    elseif (strpos($agent, 'Chrome') !== false) return "Google Chrome";
    elseif (strpos($agent, 'Safari') !== false) return "Safari";
    elseif (strpos($agent, 'Firefox') !== false) return "Mozilla Firefox";
    elseif (strpos($agent, 'MSIE') !== false || strpos($agent, 'Trident') !== false) return "Internet Explorer";
    else return "Bilinmeyen Tarayıcı";
}

function getOS($agent) {
    if (preg_match('/windows nt 10/i', $agent)) return "Windows 10";
    elseif (preg_match('/windows nt 6.3/i', $agent)) return "Windows 8.1";
    elseif (preg_match('/windows nt 6.2/i', $agent)) return "Windows 8";
    elseif (preg_match('/windows nt 6.1/i', $agent)) return "Windows 7";
    elseif (preg_match('/iphone/i', $agent)) return "iOS";
    elseif (preg_match('/android/i', $agent)) return "Android";
    elseif (preg_match('/macintosh|mac os x/i', $agent)) return "Mac OS X";
    elseif (preg_match('/linux/i', $agent)) return "Linux";
    else return "Bilinmeyen OS";
}
function karakterKes($text, $limit)
{
    return mb_substr($text, 0, $limit, "UTF-8") . (mb_strlen($text, "UTF-8") > $limit ? "..." : "");
}

function kod() {
    return rand(1000, 9999);
}

function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight) {
    ini_set('memory_limit', '512M'); // büyük resimler için ek güvenlik

    list($width, $height, $type) = getimagesize($sourcePath);

    $orientation = 1;
    if ($type == IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($sourcePath);
        if (isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
        }
    }

    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }

    // EXIF yön düzeltme
    switch ($orientation) {
        case 2:
            imageflip($sourceImage, IMG_FLIP_HORIZONTAL); break;
        case 3:
            $sourceImage = imagerotate($sourceImage, 180, 0); break;
        case 4:
            imageflip($sourceImage, IMG_FLIP_VERTICAL); break;
        case 5:
            imageflip($sourceImage, IMG_FLIP_VERTICAL);
            $sourceImage = imagerotate($sourceImage, -90, 0); break;
        case 6:
            $sourceImage = imagerotate($sourceImage, -90, 0); break;
        case 7:
            imageflip($sourceImage, IMG_FLIP_HORIZONTAL);
            $sourceImage = imagerotate($sourceImage, -90, 0); break;
        case 8:
            $sourceImage = imagerotate($sourceImage, 90, 0); break;
    }

    // Yeni boyutları al
    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);
    $ratio = $width / $height;

    if ($maxWidth / $maxHeight > $ratio) {
        $newHeight = $maxHeight;
        $newWidth = $maxHeight * $ratio;
    } else {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;
    }

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // PNG için saydamlık
    if ($type == IMAGETYPE_PNG) {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
    }

    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0,
                       $newWidth, $newHeight, $width, $height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($newImage, $destinationPath, 90); break;
        case IMAGETYPE_PNG:
            imagepng($newImage, $destinationPath); break;
        case IMAGETYPE_GIF:
            imagegif($newImage, $destinationPath); break;
    }

    imagedestroy($sourceImage);
    imagedestroy($newImage);

    return true;
}

function mailGonder($alici, $konu, $mesaj){
    $alici = urlencode($alici);
    $konu = urlencode($konu);
    $mesaj = urlencode($mesaj);
    
    header("Location: mail?alici=$alici&konu=$konu&mesaj=$mesaj");
    exit;
}

function hesapDegeri($coin, $gonderiSayisi, $yorumSayisi, $begeniSayisi, $urunlerKategoriListesi) {
    $toplamUrunDegeri = 0;
    foreach ($urunlerKategoriListesi as $kategori) {
        if ($kategori >= 1 && $kategori <= 7) {
            $toplamUrunDegeri += $kategori;
        }
    }
    $toplamUrun = $toplamUrunDegeri * 7;
    $toplamGonderi = $gonderiSayisi * 4;
    $toplamCoin = $coin * 0.05;
    $toplamYorum = $yorumSayisi * 3;
    $toplamBegeni = $begeniSayisi * 2;
    
    $toplamDeger = $toplamUrun + $toplamCoin + $toplamGonderi + $toplamBegeni + $toplamYorum;

    $toplamDeger = $toplamDeger / 1000;
    $toplamDeger = min($toplamDeger, 100);

    return number_format($toplamDeger, 1);
}

function paketDegeri($paketID){
    $Urunler = $db->Get("Urunler")->Where("UrunKategori", $paketID);
    $Fiyat = 0;
    foreach($Urunler as $Urun){
        $Fiyat += $Urun["UrunTack"];
    }
    return intval($Fiyat);
}

function davetKodu($username) {
    // Harfleri çek (rakamları, özel karakterleri sil)
    $letters = preg_replace('/[^a-zA-Z]/', '', $username);

    // Büyük harf yap
    $letters = strtoupper($letters);

    // Tekrarlayan harfleri kaldır (örn: AAA -> A)
    $letters = implode('', array_unique(str_split($letters)));

    // İlk 3 harfi al
    $prefix = substr($letters, 0, 3);

    // Eğer harf yoksa fallback
    if ($prefix === '') {
        $prefix = 'USR';
    }

    // 2 haneli rastgele sayı üret (10-99 arası)
    $random = rand(10, 99);

    return $prefix . $random;
}

function guncellemeKodu() {
    // Türkçe karakter yok → sadece İngilizce harfler
    $harfler = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rakamlar = '0123456789';

    // İlk 3 harf
    $ilk3 = '';
    for ($i = 0; $i < 3; $i++) {
        $ilk3 .= $harfler[rand(0, strlen($harfler) - 1)];
    }

    // 2 rakam
    $rakam2 = '';
    for ($i = 0; $i < 2; $i++) {
        $rakam2 .= $rakamlar[rand(0, strlen($rakamlar) - 1)];
    }

    // Son 3 harf
    $son3 = '';
    for ($i = 0; $i < 3; $i++) {
        $son3 .= $harfler[rand(0, strlen($harfler) - 1)];
    }

    return strtoupper($ilk3 . $rakam2 . $son3);
}
?>