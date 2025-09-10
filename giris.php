<?php
if(date("d") == "01"){
    $db->Update("Kullanici",
    ["BasarisizGirisDenemesi" => 0],
    ["Silindi" => false]);   
}
if(($KullaniciID)){ 
        header("location: $Link");
 }else{ ?>
<title><?= $SiteAdi." - Giriş Yap" ?></title>
<div class="d-flex align-items-center justify-content-center dark container" style="height: 75vh !important;">
    <div class="card p-4 shadow color m-1 col-12">
        <img class="m-auto col-sm-1 col-4 rounded" src='<?= $Link; ?>images/SuperIcon.png'>
        <h3 class="text-center mb-3"><?= $SiteAdi; ?> <br> Giriş Yap</h3>
        <div>
            <div class="mb-3">
                <label class="form-label">Kullanıcı Adı</label>
                <input value="<?php if(isset($_GET['kullaniciAdi'])){ echo $_GET['kullaniciAdi']; } ?>" autofocus type="text" class="form-control bg-transparent" id="KullaniciAdi" onkeyup="kontrolEt(event)" autocapitalize="off" autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Şifre</label>
                <input value="<?php if(isset($_GET['sifre'])){ echo $_GET['sifre']; } ?>" type="password" class="form-control bg-transparent" id="Sifre">
            </div>
            <p id="GirisDurum" class="fw-bold mb-3"></p>
            <button id="Giris" class="btn btn-primary w-100">Giriş Yap</button>
        </div>
        <!--<div class="text-center text-info mt-3">
            <a href="sifremi-unuttum" class="text-decoration-none">Şifremi unuttum</a>
        </div>-->
        <div class="text-center text-info mt-2">
            <a href="kayit" class="text-decoration-none btn btn-sm btn-outline-primary col-6">Hesap oluştur</a>
        </div>
        <div class="cursor-pointer text-center mt-2" data-bs-toggle="modal" data-bs-target="#kimiz">
            <a class="btn btn-sm btn-outline-info col-6">Supro Nedir?</a>
        </div>
        <div class="modal fade" id="kimiz" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header py-1">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Supro Nedir?</h1>
                        <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Supro; insanların fikirlerini, deneyimlerini ve içeriklerini özgürce paylaşabildiği, etkileşim kurabildiği modern bir sosyal medya ve paylaşım platformudur.
                            <br><br>Kullanıcılar burada gönderiler oluşturabilir, tartışmalara katılabilir, anketlere katılabilir ve topluluklarla iletişim halinde kalabilir. 
                            <br><br>Supro’nun amacı; sadece içerik paylaşmak değil, aynı zamanda insanların kendini ifade edebileceği, yeni insanlarla tanışabileceği ve ortak ilgi alanlarında buluşabileceği güvenli ve dinamik bir ortam sunmaktır.
                            <br><br>Sende 1 dakikada hesabını oluşturarak bizlere destek olarak katılabilirsin. 🙂
                        </p>
                    </div>             
                    <div class="modal-footer">
                        <a <?php if($k[0] == "giris"){ echo 'href="kayit"'; }else{ echo ' data-bs-dismiss="modal" aria-label="Close"'; } ?> class="btn btn-primary">Hesap Oluştur</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php  }
?>