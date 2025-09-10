<title><?= $SiteAdi; ?> - Güncellemeler</title>
<div class="card color mt-2 mx-1">
	<div class="card-body p-2">
        <h4>
            <a href="./" class="text-white"><i class="fi fi-ts-arrow-circle-left"></i></a>
            Anasayfa
        </h4>
		<h4><i class="fi fi-tr-rotate-square"></i>Son Güncellemeler</h4>
		<?php
		$Guncellemeler = $db->Get("Guncelleme")->OrderBy("GuncellemeTarih", "DESC")->Where("Aktif", true);

		foreach ($Guncellemeler as $Guncelleme){
			?>
			<div class="card color mt-2">
				<div class="card-body"><?= $Guncelleme["GuncellemeMetin"]; ?></div>
				<small class="card-footer fst-italic">
					<i class="fi fi-ts-calendar"></i><?= date("d.m.Y H:i", $Guncelleme["GuncellemeTarih"]); ?>
                    <span class="float-end">Onay Kodu: <?= $Guncelleme["GuncellemeKodu"]; ?></span>
				</small>
			</div>
		<?php } ?>
	</div>
</div>