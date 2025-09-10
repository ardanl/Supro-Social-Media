<?php
if(($KullaniciID)){ 
        header("location: $Link");
 }else{
 ?>
<title><?= $SiteAdi." - Giriş Yap" ?></title>
<div class="d-flex align-items-center justify-content-center dark container">
    <div class="card p-4 shadow color m-1 col-12">
        <img class="m-auto col-sm-1 col-4 rounded" src='<?= $Link; ?>images/SuperIcon.png'>
        <h3 class="text-center mb-3"><?= $SiteAdi; ?> <br> Hesap Oluştur</h3>
        <div>
            <div class="mb-3">
                <label class="form-label">Kullanıcı Adı</label>
                <input autofocus type="text" class="form-control text-lowercase bg-transparent" id="KullaniciAdi" onkeyup="kontrolEt(event)">
            </div>
            <div class="mb-3">
                <label class="form-label">E-Mail</label>
                <input type="email" class="form-control bg-transparent color" id="Email">
            </div>
            <div class="mb-3">
                <label class="form-label">Şifre</label>
                <input type="password" class="form-control bg-transparent color" id="Sifre">
            </div>
            <div class="mb-3">
                <label class="form-label">Tekrar Şifre</label>
                <input type="password" class="form-control bg-transparent color" id="TekrarSifre">
            </div>
            <div class="mb-3">
                <label class="form-label">Davet Kodu (varsa)</label>
                <input <?php if(isset($_GET['U'])){ echo "disabled"; } ?> value="<?php if(isset($_GET['U'])){ echo $_GET['U']; } ?>" type="text" class="text-uppercase form-control bg-transparent color" id="KayitKod">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required checked>
                <label class="form-check-label" for="invalidCheck">
                    <a href="kvkk">KVKK Aydınlatma Metnini okudum.</a>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="topluluk" required checked>
                <label class="form-check-label" for="topluluk">
                    <a href="topluluk-kurallari">Topluluk Kurallarını okudum.</a>
                </label>
            </div>
            <p id="GirisDurum" class="fw-bold mb-2"></p>
            <button id="hesap-olustur" class="btn btn-primary w-100">Hesap Oluştur</button>
        </div>
        <div class="text-center text-info mt-2">
            <a href="giris" class="text-decoration-none btn btn-sm btn-outline-primary col-6">Giriş Yap</a>
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Hesap Oluştur</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php }
?>