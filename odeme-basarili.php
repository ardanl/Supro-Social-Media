<?php
$kullaniciAdi = filter_input(INPUT_GET, "Kullanici", FILTER_SANITIZE_STRING);
$paketID = filter_input(INPUT_GET, "Paket", FILTER_VALIDATE_INT);
$zaman = filter_input(INPUT_GET, "time", FILTER_SANITIZE_STRING);
if($zaman + 30 >= time()){

    $bugun = date("d.m.Y H:i");

    if (!$kullaniciAdi || !$paketID) die("❌ Kullanıcı veya paket bilgisi eksik!");

    $Kullanici = $db->Get("Kullanici")->Where("KullaniciAdi", $kullaniciAdi);
    if (!$Kullanici) die("❌ Kullanıcı bulunamadı!");
    $Kullanici = $Kullanici[0];

    $Paketler = [
        1 => ["1250 Supro Coin", "14.99"],
        2 => ["3000 Supro Coin", "29.99"],
        3 => ["7000 Supro Coin", "59.99"],
        4 => ["Onaylı Hesap Profil Rozeti", "109.99"],
        5 => ["Sponsor Rolü", "10.99"],
    ];

    $Puanlar = [
        1 => 1250,
        2 => 3000,
        3 => 7000
    ];

    // PayTR kaydı
    $db->Insert("paytr", [
        "KullaniciID" => $Kullanici["KullaniciID"],
        "PaketID" => $paketID,
        "PaketAciklama" => $Paketler[$paketID][0],
        "PaketFiyat" => $Paketler[$paketID][1],
        "Tarih" => time()
    ]);

    if (in_array($paketID, [1, 2, 3])) {
        $PuanGuncel = $Kullanici["Puan"] + $Puanlar[$paketID];
        $db->Update("Kullanici", [
            "Puan" => $PuanGuncel,
            "GuncellenmeTarihi" => time()
        ], ["KullaniciID" => $Kullanici["KullaniciID"]]);
    } elseif(in_array($paketID, [4])) {
        $db->Update("Kullanici", [
            "Onaylandi" => 1,
            "GuncellenmeTarihi" => time()
        ], ["KullaniciID" => $Kullanici["KullaniciID"]]);
    }elseif(in_array($paketID, [5])){
        $db->Update("Kullanici", [
            "Yetki" => 5,
            "GuncellenmeTarihi" => time()
        ], ["KullaniciID" => $Kullanici["KullaniciID"]]);
    }

    $db->Insert("Bildirimler",
    [
        "KullaniciID" => 0,
        "AliciID" => $Kullanici["KullaniciID"],
        "BildirimKategoriID" => 3,
        "BildirimIcerik" => "$bugun tarihinde satın alımınız onaylanmıştır",
        "BildirimTarihi" => time()
    ]);
}

header("location: u/$kullaniciAdi");
?>