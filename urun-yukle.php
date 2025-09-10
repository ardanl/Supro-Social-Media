<title><?= $SiteAdi; ?> - Ürün Yükle</title>
<?php if($Yetki < 3){ ?>
<div class="card mx-2 color mt-4">
    <div class="card-body">
        <h5>Ürün Yükle</h5>
        <div class="mb-2">
            <label>Ürün Adı:</label>
            <input class="form-control color">
        </div>
        <div class="mb-2">
            <label>Ürün Fiyatı:</label>
            <input class="form-control color">
        </div>
        <div class="mb-2">
            <label>Ürün Kategorisi:</label>
            <select class="form-control color">
                <?php
                $Kats = $db->Get("UrunKategori")->OrderBy("UrunKategoriTarih", "DESC")->Where("UrunKategoriDetay", "");
                foreach($Kats as $Kat){ ?>
                    <option><?= $Kat["UrunKategoriAciklama"]; ?></option>
                <?php }
                ?>
            <select>
        </div>
        <div class="mb-2">
            <label>Ürün Konum:</label>
            <ul class="p-0 row row-cols-2 justify-content-center g-1 mb-0">
                <div class="col h-100">
                    <input type="radio" class="btn-check" name="options-outlined" id="StickerCheck" autocomplete="off">
                    <label data-bs-dismiss="modal" class="w-100 h-100 tek-satir btn-sm btn btn-outline-light my-1" data-veri="1" for="StickerCheck">
                        Sticker
                    </label>
                </div>
                <div class="col h-100">
                    <input type="radio" class="btn-check" name="options-outlined" id="BannerCheck" autocomplete="off">
                    <label data-bs-dismiss="modal" class="w-100 h-100 tek-satir btn-sm btn btn-outline-light my-1" data-veri="2" for="BannerCheck">
                        Banner
                    </label>
                </div>
            </ul>
        </div>
        <div class="mb-2">
            <label>Ürün Seviye:</label>
            <select class="form-control color">
                <?php
                $Kats = $db->Get("UrunSeviye")->OrderBy("UrunSeviyeID", "ASC")->Where("Aktif", true);
                foreach($Kats as $Kat){ ?>
                    <option><?= $Kat["UrunSeviyeID"] . " - " . $Kat["UrunSeviyeAdi"]; ?></option>
                <?php }
                ?>
            <select>
        </div>
        <div class="mb-2">
            <label>Ürün Adeti:</label>
            <input type="number" class="form-control color">
        </div>
        <div class="mb-2">
            <label>Ürün Linki:</label>
            <input class="form-control color">
        </div>
    </div>
</div>
<?php } ?>