<title><?= $SiteAdi; ?> - Diğer</title>
<form method="get" class="container mt-2">
	<h5>Ara</h5>
	<div class="input-group mb-3">
		<input autocomplete="off" name="ara" type="search" class="form-control color">
		<button class="btn btn-primary" type="submit"><i class="fi fi-rr-search p-0"></i></button>
	</div>
	<div class="card">
		<ul class="list-group list-group-flush">
			<?php
			if (isset($_GET["ara"])) {
				$aranan = clean($_GET["ara"]);
				$KarakterSayisi = 3;

				if (strlen($aranan) < $KarakterSayisi) {
					echo "<li class='list-group-item list-group-item-danger'>Arama terimi en az <span class='text-danger'>$KarakterSayisi karakter</span> olmalıdır.</li>";
					exit;
				}
                if($Yetki < 3){
                    $aktive = "";
                }else{
                    $aktive = "AND Aktif = 1";
                }
				$likeAranan = "%{$aranan}%";

				// Geliştirilmiş SQL sorgusu (Yazar bilgisiyle)
				$sql = "
                        SELECT *
                        FROM (
                            SELECT
                                1 AS tip,
                                Ad AS isim,
                                Soyad,
                                KullaniciID,
                                ProfilResmi,
                                Color,
                                Onaylandi,
                                KullaniciAdi,
                                NULL AS IcerikMetni,
                                NULL AS YayimTarihi,
                                NULL AS YazarID,
                                NULL AS YazarAdi
                            FROM Kullanici
                            WHERE (
                                CONCAT(Ad, ' ', Soyad) LIKE ?
                                OR Ad LIKE ?
                                OR Soyad LIKE ?
                                OR KullaniciAdi LIKE ?
                            )
                            $aktive
                            AND Silindi = 0

                            UNION ALL

                            SELECT
                                2 AS tip,
                                NULL AS isim,
                                NULL AS Soyad,
                                NULL AS KullaniciID,
                                NULL AS ProfilResmi,
                                NULL AS Color,
                                NULL AS Onaylandi,
                                NULL AS KullaniciAdi,
                                I.IcerikMetni,
                                I.YayimTarihi,
                                I.YazarID,
                                K.KullaniciAdi AS YazarAdi
                            FROM Icerikler I
                            LEFT JOIN Kullanici K ON K.KullaniciID = I.YazarID
                            WHERE (
                                I.IcerikMetni LIKE ?
                                OR I.YayimTarihi LIKE ?
                                OR I.YazarID LIKE ?
                            )
                            AND I.Durum = 1
                            AND I.Yasak = 0
                        ) AS t
                        ORDER BY
                            tip,
                            CASE WHEN tip = 1 THEN KullaniciAdi END ASC,
                            CASE WHEN tip = 2 THEN YayimTarihi END DESC
                    ";

				$stmt = $conn->prepare($sql);
				$stmt->execute([
					$likeAranan,
					$likeAranan,
					$likeAranan,
					$likeAranan, // Kullanıcı araması
					$likeAranan,
					$likeAranan,
					$likeAranan              // İçerik araması
				]);
				$sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($sonuclar) === 0) {
					echo "<li class='list-group-item list-group-item-danger'>\"$aranan\" için sonuç bulunamadı.</li>";
					exit;
				} else {
					echo "<li class='list-group-item list-group-item-primary'>\"$aranan\" için " . count($sonuclar) . " sonuç bulundu.</li>";
				}
				foreach ($sonuclar as $sonuc) {
					$yayimTarihi = $sonuc["YayimTarihi"];
					$yazarAdi = $sonuc["YazarAdi"];
					?>
					<a href="<?php
					if ($sonuc["KullaniciAdi"] && $sonuc["tip"] == 1) {
						echo "u/" . $sonuc["KullaniciAdi"];
					} elseif ($sonuc["IcerikMetni"] && $yayimTarihi && $yazarAdi) {
						echo "post/" . $yayimTarihi . "-" . $sonuc["YazarID"];
					}
					?>" class="list-group-item color">
						<?php if ($sonuc["tip"] == 1) { ?>
							<?php
							if ($sonuc["ProfilResmi"] != "") {
								echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(images/' . $sonuc["ProfilResmi"] . ');background-position: center;background-size: cover;">';
							} else {
								echo '<i class="fi fi-ss-user fs-3"></i>';
							}
							if ($sonuc["isim"]) {
								echo "<span class='text-white'>" . $sonuc["isim"] . " " . $sonuc["Soyad"];
							} else {
								echo "<span class='text-white'>İsimsiz Kullanıcı";
							}
							if ($sonuc["Onaylandi"]) {
								if ($sonuc["Color"]) {
									echo '<i class="fi tick fi-ss-badge-check ps-1 ' . $sonuc["Color"] . '"></i>';
								} else {
									echo '<i class="fi fi-ss-badge-check text-primary ps-1"></i>';
								}
							}
							echo "<span class='text-muted'> ● @" . $sonuc["KullaniciAdi"] . "</span></span>";
							?>
						<?php } elseif ($sonuc["tip"] == 2 && $sonuc["IcerikMetni"]) { ?>
							<?= strip_tags(karakterKes($sonuc["IcerikMetni"], 75)); ?> ● <?= $yazarAdi; ?>
						<?php } ?>
					</a>
					<?php
				}
			}
			?>
		</ul>
	</div>
</form>
<div class="container">
	<!--<div class="card color mt-2">
    	<div class="card-body p-2">
    		<h5><i class="fi fi-ts-store-alt"></i>Son Eklenenler</h5>
    		<div class="row row-cols-1 row-cols-sm-2 g-2">
            <?php $i = 0; $Urunler = $db->Get("UrunKategori")->OrderBy("UrunKategoriTarih", "DESC")->Where("Aktif", true);        
                foreach($Urunler as $Urun){ 
                if($i < 2){
                $Urunler = $db->Get("Urunler")->Where("UrunKategori", $Urun["UrunKategoriID"]);
                if($db->Row($Urunler) > 0){
                ?>
                    <div class="col">
                        <a href="alisveris" class="card dark h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title text-start">
                                    <?php echo $Urun["UrunKategoriAciklama"]; ?> Paketi <?php if($Urun["UrunYeri"] == 1){ echo " - Sticker"; }elseif($Urun["UrunYeri"] == 2){ echo " - Banner"; } ?>
                                     <?php 
                                    $Fiyat = 0;
                                    foreach($Urunler as $Urunf){
                                        $Fiyat += $Urunf["UrunTack"];
                                    }
                                    $indirim = $Fiyat * 25 / 100 ;
                                    $Fiyat = $Fiyat - $indirim;
                                    ?>
                                </h5>
                                <p class="card-text text-center">
                                <?php
                                $Urunlerimiz = $db->Get("Urunler")->OrderBy("UrunTack", "DESC")->Where("UrunKategori", $Urun["UrunKategoriID"]);        
                                foreach($Urunlerimiz as $UrunDetay){ 
                                    if($UrunDetay["UrunYeri"] == 1){
                                        echo '<img src="images/sticker/'.$UrunDetay["UrunLink"].'.png" style="width: 2rem; margin-right: 3px;">';
                                    }elseif($UrunDetay["UrunYeri"] == 2){ ?>
                                    <div style="
                                    height: 8rem; 
                                    background-image: url(../images/banner/<?= $UrunDetay["UrunLink"]; ?>); 
                                    background-size: cover; background-position: center;" class="my-1"><?= $UrunDetay["UrunAdi"]; ?></div>
                                    <?php }
                                }?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php $i++; } } } ?>
                        <a class="btn btn-sm text-white col-4 m-auto mt-2 g-kakao" href="alisveris">
                            <i class="fi fi-ts-store-alt"></i>Mağaza
                        </a>
    	</div>
    	</div>
    </div>-->
    <div id="alert-container" class="container p-0 card color mt-2">
        <div class="alert color alert-dismissible fade show progressive shadow-sm py-1 pe-3 m-0">
            <strong>Arkadaşını davet et ve 125 coin kazan!</strong>
            <p class="m-0 text-sm">Davet Kodun:  <?= $DavetKodu; ?></p>
            <!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>-->
            <div class="text-center">
                <a target="_blank" href="https://wa.me/?text=Selam! Sende Supro'ya üye ol ve bize katıl.%0ADavet Kodum: <?= $DavetKodu; ?>%0Ahttps://supro.com.tr/kayit?U=<?= $DavetKodu; ?>" class="btn btn-outline-success mt-1 col-5">
                    <i class="fi fi-brands-whatsapp p-0"></i>
                </a>
                <a id="kopyalaBtn" target="_blank" class="btn btn-outline-primary mt-1 col-5">
                    <i class="fi fi-ss-link-alt p-0"></i>
                </a>
            </div>
            <div class="alert-progress"></div>
        </div>
    </div>
	<div class="card color mt-2">
    	<div class="card-body p-2">
    		<h5><i class="fi fi-ts-rank"></i>Supro Sıralama</h5>
    		<div class="row row-cols-2 g-2">
                <?php
                $Kullanicilar = $db->Get("Kullanici")->Where("Aktif", true);
                $Degerler = [];
                
                foreach($Kullanicilar as $Kullanici){
                    if($Kullanici["Yetki"] != 6){
                        $GonderilerAdet = 0;
                        $Gonderiler = $db->Get("Icerikler")->Where("YazarID", $Kullanici["KullaniciID"]);
                        if ($db->Row($Gonderiler) > 0) {
                            foreach ($Gonderiler as $Gonderi) {
                                if ($Gonderi["Durum"] && !$Gonderi["Yasak"]) {
                                    $GonderilerAdet++;
                                }
                            }
                        }
                    
                        $YorumAdet = 0;
                        $Yorumlar = $db->Get("Yorumlar")->Where("KullaniciID", $Kullanici["KullaniciID"]);
                        $YorumAdet = $db->Row($Yorumlar);
                    
                        $BegeniAdet = 0;
                        $Begeniler = $db->Get("Begeni")->Where("KullaniciID", $Kullanici["KullaniciID"]);
                        $BegeniAdet = $db->Row($Begeniler);
                    
                        $UrunKategoriListe = [];
                        $Alisverisler = $db->Get("Alisveris")->Where("KullaniciID",  $Kullanici["KullaniciID"]);
                        if($db->Row($Alisverisler) > 0){
                            foreach($Alisverisler as $Alisveris){
                                $Urunlerim = $db->Get("Urunler")->Where("UrunID",  $Alisveris["UrunID"]);   
                                foreach($Urunlerim as $Urun){
                                    $UrunKategoriListe[] = $Urun["UrunSeviye"]; 
                                }
                            }
                        }
                    
                        $HesapDeger = hesapDegeri($Kullanici["Puan"], $GonderilerAdet, $YorumAdet, $BegeniAdet, $UrunKategoriListe);

                        $Degerler[] = [
                            "KullaniciAdi" => $Kullanici["KullaniciAdi"],
                            "ProfilResmi" => $Kullanici["ProfilResmi"],
                            "Banner" => $Kullanici["Banner"],
                            "Sticker" => $Kullanici["Sticker"],
                            "Yetki" => $Kullanici["Yetki"],
                            "HesapDeger" => $HesapDeger
                        ];
                    }
                }
                
                usort($Degerler, function ($a, $b) {
                    return $b["HesapDeger"] <=> $a["HesapDeger"];
                });
                
                $i = 0;
                foreach($Degerler as $veri){
                if($i < 7){
                ?>
                    <div class="col
                    <?php
                    if($i == 0){
                        echo " col-12";
                    }
                    ?> h-100">
                        <a href="u/<?= $veri["KullaniciAdi"]; ?>" class="card color h-100">
                            <div class="card-body text-center pb-0" style="background-image: url(../images/banner/<?= $veri['Banner']; ?>); background-size: cover; background-position: center;z-index:0;">
                            <div style="width: 100%; position: absolute; height: 100%; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(1px);z-index:-1;"></div>
                            <?php if($veri['Sticker'] != ""){ ?>
                                <img class="profil-sticker" style="height: 25px; top: 0rem;" src="images/sticker/<?= $veri['Sticker']; ?>.png">
                            <?php } ?>
                                <img class="rounded-circle mt-1" style="z-index:1;width: 4rem;height: 4rem;background-image: url(images/<?= $veri["ProfilResmi"]; ?>);background-position: center;background-size: cover;">
                                <p style="z-index:1;"><?php 
                                    echo "@" . $veri["KullaniciAdi"];
                                    $VeriOnaylandi = $db->Get("Kullanici")->Where("KullaniciAdi", $veri["KullaniciAdi"], "Onaylandi"); 
                                    if ($VeriOnaylandi == true) {
                                        $Color = $db->Get("Kullanici")->Where("KullaniciAdi", $veri["KullaniciAdi"], "Color"); 
                                        if($Color){
                                            echo '<i class="fi tick fi-ss-badge-check ps-1 ' . $Color . '"></i>';
                                        }else{
                                            echo '<i class="fi fi-ss-badge-check text-primary ps-1"></i>';
                                        }
                                    } 
                                    if($i == 0){
                                        $tickColor = "dilostunc";
                                    }elseif($i > 0 && $i < 2){
                                        $tickColor = "g-pembe";
                                    }elseif($i > 2 && $i < 4){
                                        $tickColor = "g-kor";
                                    }elseif($i > 4 && $i < 7){
                                        $tickColor = "g-turuncu";
                                    }
                                ?><p class="text-muted"><i class="fi fi-ss-flame tick <?= $tickColor; ?>"></i><?= $veri["HesapDeger"]; ?></p></p>
                                <i style="position: absolute; left: 3px; bottom: 0;font-size: 0.7rem;" 
                                class="fi fi-rr-<?= $db->Get('Yetkiler')->Where('YetkiID',$veri['Yetki'],'Icon'); ?> text-<?= $db->Get('Yetkiler')->Where('YetkiID',$veri['Yetki'],'Color'); ?>"></i>
                            </div>
                        </a>
                    </div>
                <?php $i++; } } ?>
            </div>
    	</div>
    </div>
	<div class="card color mt-2" id="PaketSatinAl">
    	<div class="card-body p-2">
    		<h5><i class="fi fi-tr-coins"></i>Paket Satın Al</h5>
            <div class="row row-cols-1 row-cols-sm-3 g-2 justify-content-center">
    		<?php
                $paketler = [
                    1 => ["1250 Supro Coin", "14.99"],
                    2 => ["3000 Supro Coin", "29.99"],
                    3 => ["7000 Supro Coin", "59.99"],
                    4 => ["Onaylı Hesap Profil Rozeti", "109.99"],
                    5 => ["Sponsor Rolü", "10.99"]
                ];

                foreach ($paketler as $id => $paket) { 
                    [$aciklama, $fiyat] = $paket; ?>
                    <div class="col h-100">
                        <div class="card color h-100">
                            <div class="card-body h-100">
                                <h5>Paket <?= $id; ?> - <small class="text-teal fw-bold"><?= $fiyat; ?>₺</small></h5>
                                <p><?= $aciklama; ?></p>
                                <?php if($id == 4 && $Onaylandi == 1){ ?>
                                    <a class="btn btn-sm btn-success col-4 mt-2 disabled">Mevcut</a>  
                                <?php }elseif($id == 5 && $Yetki < 3){ ?>
                                    <a class="btn btn-sm btn-success col-4 mt-2 disabled">Mevcut</a>  
                                <?php }else{ ?>   
                                    <a href="https://svenyazilim.com/tr/odeme_sonuc?Supro=1&Kullanici=<?= $KullaniciAdi; ?>&Paket=<?= $id; ?>" class="btn btn-sm btn-primary col-4 mt-2">Satın Al</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php }
    		?>
            </div>
    	</div>
    </div>
	<div class="card color mt-2">
    	<div class="card-body p-2">
    		<h5><i class="fi fi-tr-rotate-square"></i>Son Güncellemeler</h5>
    		<?php
    		$Guncellemeler = $db->Get("Guncelleme")->OrderBy("GuncellemeTarih", "DESC")->Where("Aktif", true);
    		$GuncellemeVarMi = false;
    
    		foreach ($Guncellemeler as $Guncelleme) {
    			// 1 HAFTA = 60 * 60 * 24 * 7
    			// 3 GÜN = 60 * 60 * 24 * 3
    			// 2 GÜN = 60 * 60 * 24 * 2
    			if (time() < ($Guncelleme["GuncellemeTarih"] + (60 * 60 * 24 * 2))) {
    				$GuncellemeVarMi = true;
    				?>
    				<div class="card color mt-2 cursor-pointer" onclick="location.href='guncellemeler'">
    					<div class="card-body"><?= karakterKes($Guncelleme["GuncellemeMetin"], 180); ?></div>
    					<div class="card-footer fst-italic">
    						<i class="fi fi-ts-calendar"></i><?= date("d.m.Y H:i", $Guncelleme["GuncellemeTarih"]); ?>
    					</div>
    				</div>
    				<?php
    			}
    		}
    		if (!$GuncellemeVarMi) {
    			?>
    			<div class="card color mt-2">
    				<div class="card-body text-center">
    				    <p>2 gün içerisinde güncelleme yapılmamıştır.</p>
    				</div>
    			</div>
    			<?php
    		}
    		?>
    		<div class="text-center mt-2">
    	        <a href="guncellemeler" class="btn btn-primary btn-sm">Tüm güncellemeler</a>
    		</div>
    	</div>
    </div>
    <div class="card color mt-2">
        <div class="card-body p-2">
            <h4><img src="images/SuperIcon.png" width="40" class="rounded me-1"><span class="align-middle">Supro Hakkında</span></h4>
            <div>
              <h5>Merhaba Supro Ailesi! 🌟</h5>
              <p><span>Aramıza hoş geldin!</span> Şimdi sana kısaca <strong>Supro</strong> uygulamasından bahsedelim.</p>
            
              <p><strong>Supro</strong>; gönderi paylaşabileceğin, coin kazanabileceğin ve alışveriş yapabileceğin sosyal bir platformdur. Düşüncelerini özgürce paylaşabilir, toplulukla etkileşime geçebilir ve aktif oldukça ödüller kazanabilirsin.</p>
            </div>
            <br>
            <div>
              <h5>📌 Platformda Neler Yapabilirsin?</h5>
              <p><span>✔️ Gönderi paylaş</span></p>
              <p><span>✔️ Diğer kullanıcıların gönderilerini beğen</span></p>
              <p><span>✔️ Yorum yap</span></p>
              <p><span>✔️ Kullanıcıları takip et</span></p>
              <p><span>✔️ Coin kazan, ürün al ve sıralamada yüksel</span></p>
            </div>
            <br>
            <div>
              <h5>💰 Coin Nasıl Kazanılır?</h5>
              <p><strong>Yaptığın işlemlerle coin kazanırsın:</strong></p>
              <p>👉 Gönderi Beğenmek: <span>1 - 5 coin</span></p>
              <p>👉 Yorum Yapmak: <span>1 - 10 coin</span></p>
              <p>👉 Kullanıcı Takip Etmek: <span>1 - 20 coin</span></p>
              <p>👉 Gönderi Paylaşmak: <span>1 - 50 coin</span></p>
            
              <small><strong>Not:</strong> Coinler rastgele (random) hesaplanır.</small>
            </div>
            <br>
            <div>
              <h5>⚠️ Coin Kaybetme Durumları</h5>
              <p>Coin kazanmak kadar, kaybetmek de mümkün örnek:</p>
              <p>❌ Beğenini geri çekersen: <span>-5 coin</span></p>
              <p>❌ Yorum silersen: <span>-10 coin</span></p>
              <p>❌ Gönderini silersen: <span>-50 coin</span></p>
              <small>İşlemi geri alırsan, o işlemden kazanabileceğin <strong>maksimum coin miktarını</strong> kaybedersin.</small>
            </div>
            <br>
            <div>
              <h5>🛠️ Yetkiler</h5>
              <p>Platformdaki bütün hesapların bir yetkisi vardır:</p>
              <?php
              $YetkilerSql = $db->Get("Yetkiler")->OrderBy("YetkiID", "ASC")->Where("Aktif", true);
              foreach($YetkilerSql as $Yetkiler){
                $BulYetki = $db->Row($db->Get("Kullanici")->Where("Yetki", $Yetkiler["YetkiID"]));
                 ?>
                  <p>
                    <span class="text-<?= $Yetkiler["Color"]; ?>"><i class="fi fi-ts-<?= $Yetkiler["Icon"]; ?>"></i><?= $Yetkiler["YetkiAdi"] . " (" . $BulYetki . "): </span>" . $Yetkiler["Aciklama"]; ?>
                  </p>
              <?php } ?>
            </div>
            <br>
            <div>
              <h5>🎖️ Rank Sistemi Nasıl İşliyor?</h5>
              <p>Kullanıcılar, platformdaki performansına göre sıralanır. Yani daha çok etkileşim, daha yüksek rank demektir.</p>
                <br>
              <h4>Katsayılar:</h4>
              <p><span>Ürün Değeri Katsayısı:</span> x7</p>
              <p><span>Gönderi Katsayısı:</span> x4</p>
              <p><span>Yorum Katsayısı:</span> x3</p>
              <p><span>Beğeni Katsayısı:</span> x2</p>
              <p><span>Coin Katsayısı:</span> x0.05</p>
              <small>❗Not: Ne kadar çok sticker, o kadar çok rank demek.</small>
            </div>
            <br>
            <div>
              <h5>💎 Ürün Değerleri!</h5>
              <p>Her ürün 7 seviyeden birine sahiptir. Ne kadar yüksek seviyeli ürününüz varsa rankınız o kadar yüksek olur demektir.</p>
              <?php
              $Seviyeler = $db->Get("UrunSeviye", true);
              $i = 1;
              foreach($Seviyeler as $Seviye){ ?>
                  <p>
                    <?= $i; ?>. Seviye:
                    <span style="color: <?= $Seviye["UrunSeviyeRenk"]; ?>"> <span><i class="fi fi-ss-<?= $Seviye["UrunSeviyeIcon"]; ?>"></i></span><?= $Seviye["UrunSeviyeAdi"]; ?></span>
                  </p>
              <?php $i++; } ?>
            </div>
            <br>
            <div>
              <h5>🚀 Hazırsan Başlayalım!</h5>
              <p>Düşüncelerini paylaş, etkileşim kur, coin kazan ve sıralamada yüksel! Herhangi bir sorununuz olduğunda <a href="u/Supro">@Supro</a> hesabını etiketleyerek bizlere ulaşabilirsiniz.</p>
              <small>Sevgilerle, Supro Ekibi 💙</small>
            </div>
            <br>
            <div>
              <h5>🔗 Linkler</h5>
              <p>Topluluk Kuralları için <a href="topluluk-kurallari">tıklayınız</a>.</p>
              <p>KVKK Aydınlatma Metni için <a href="kvkk">tıklayınız</a>.</p>
              <p>Çerez Politikası için <a href="cerezler">tıklayınız</a>.</p>
            </div>

        </div>
    </div>
</div>