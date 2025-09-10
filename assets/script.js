url = webLink + "ajax.php";

if (window.location.href === webLink + "giris") {
    $('#Sifre').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $('#Giris').click();
        }
    });
    $("#kullaniciAdi").on("input", function() {
        this.value = this.value.toLowerCase();
    });
}
if (window.location.href === webLink + "giris") {
    $("form").on("submit", function() {
        $("#ResimKaydetBtn").prop("disabled", true).text("Güncelleniyor...");
    });
}


if (window.location.href.includes("/post/")) {
    $('#IcerikMetni').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $('#YorumGonder').click();
        }
    });
}

var etiketlenenKullanicilar = [];

function setupMentionInput(input, dropdown) {
if (!input || !dropdown) return;

dropdown.addEventListener('click', function (e) {
  if (!e.target.matches('.dropdown-item')) return;
  e.preventDefault();

  const cursorPos = input.selectionStart;
  const value = input.value;
  const before = value.slice(0, cursorPos);
  const after = value.slice(cursorPos);
  const match = before.match(/@[\p{L}0-9_]*$/u);

  if (match) {
    const start = cursorPos - match[0].length;
    const username = e.target.textContent.trim();

    if (!etiketlenenKullanicilar.includes(username)) {
      etiketlenenKullanicilar.push(username);
    }

    const newText = value.slice(0, start) + '@' + username + ' ' + after;
    input.value = newText;

    const newCursor = start + username.length + 2;
    input.setSelectionRange(newCursor, newCursor);
  }

  dropdown.classList.remove('show');
  input.focus();
});

input.addEventListener('input', function () {
  const cursorPos = input.selectionStart;
  const value = input.value;
  const textBeforeCursor = value.slice(0, cursorPos);
  const mentionMatch = textBeforeCursor.match(/@([\p{L}0-9_]*)$/u);

  if (mentionMatch && mentionMatch[1]) {
    const query = mentionMatch[1].toLowerCase();
    const items = Array.from(dropdown.querySelectorAll('a.dropdown-item'));

    let matchCount = 0;

    items.forEach(li => {
      const text = li.textContent.toLowerCase();
      if (text.includes(query) && matchCount < 4) {
        li.style.display = 'block';
        matchCount++;
      } else {
        li.style.display = 'none';
      }
    });

    if (matchCount > 0) {
      dropdown.classList.add('show');
    } else {
      dropdown.classList.remove('show');
    }
  } else {
    dropdown.classList.remove('show');
  }
});
}

// Sayfa tipi kontrolü
if (window.location.href === webLink + "paylas" || window.location.pathname.startsWith("/post")) {
    // Ana içerik alanı
    const mainInput = document.getElementById('IcerikMetni');
    const mainDropdown = document.getElementById('dropdown');
    setupMentionInput(mainInput, mainDropdown);
    
    // Yorum cevap alanları
    document.querySelectorAll('[id^="YorumCevaplar"]').forEach(wrapper => {
      const idNum = wrapper.id.match(/\d+$/)?.[0];
      if (!idNum) return;
    
      const input2 = document.getElementById('CevapInput' + idNum);
      const dropdown2 = wrapper.previousElementSibling?.querySelector('.dropdown2');
      setupMentionInput(input2, dropdown2);
    });

    // Tüm mention dropdown'larını kapatma
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.position-relative')) {
        document.querySelectorAll('.dropdown, .dropdown2').forEach(dd => dd.classList.remove('show'));
      }
    });
}

function kontrolEt(event) {
    var input = document.getElementById("KullaniciAdi");
    var value = input.value;
    
    // Yalnızca harf, rakam, nokta ve alt çizgi kabul et
    var yeniDeger = value.replace(/[^a-zA-Z0-9._]/g, '');

    // Eğer değişiklik olduysa, input'u güncelle
    if (value !== yeniDeger) {
        input.value = yeniDeger;
    }
}

function formatTarihSaat() {
  const now = new Date();

  const gun = String(now.getDate()).padStart(2, '0');
  const ay = String(now.getMonth() + 1).padStart(2, '0'); // Aylar 0-11 arası
  const yil = now.getFullYear();

  const saat = String(now.getHours()).padStart(2, '0');
  const dakika = String(now.getMinutes()).padStart(2, '0');

  return `${gun}.${ay}.${yil} - ${saat}:${dakika}`;
}

function etiketleriLinkeDonustur(metin) {
    return metin.replace(/@([\p{L}0-9_]+)/gu, function(match, p1) {
        if (etiketlenenKullanicilar.includes(p1)) {
          return `<a href="${webLink}u/${p1}">@${p1}</a>`;
        } else {
          return match; // Etiket dizide yoksa aynen bırak
        }
    });
}

function cevapla(id, eleman) {
    $(eleman).hide();
    $("#Cevapla" + id).show();
    $("#CevapInput" + id).focus();
}

function cevaplaYorum(id, eleman) {
    $(eleman).hide();
    $("#CevaplaYorum" + id).show();
    $("#CevaplaYorumInput" + id).focus();
}

const tarihSaat = formatTarihSaat();
const sayi = Math.floor(Math.random() * 100) + 1;

// 1: giriş yap
$("#Giris").on("click", function () {
    var KullaniciAdi = $("#KullaniciAdi");
    var Sifre = $("#Sifre");
    var $this = $(this);
    var Durum = $("#GirisDurum");
    if(KullaniciAdi.val() != ""){
        if(Sifre.val() != ""){
            $this.prop("disabled", true);
            KullaniciAdi.prop("disabled", true);
            Sifre.prop("disabled", true);
            KullaniciAdi.removeClass("is-invalid");
            Sifre.removeClass("is-invalid");
            $.ajax({
                url: url,
                method: "POST",
                data:{
                    KullaniciAdi: KullaniciAdi.val(),
                    Sifre: Sifre.val(),
                    ajax: 1
                },
                success: function (data) {
                    console.log(data);
                    if(data == "KullaniciAdi"){
                        $this.prop("disabled", false);
                        KullaniciAdi.prop("disabled", false);
                        Sifre.prop("disabled", false);
                        KullaniciAdi.addClass("is-invalid");
                        Sifre.removeClass("is-valid");
                        KullaniciAdi.focus();
                        Durum.addClass("text-danger");
                        Durum.text("Kullanıcı adınız yanlış");
                    }else if(data == "Sifre"){
                        $this.prop("disabled", false);
                        Sifre.prop("disabled", false);
                        KullaniciAdi.addClass("is-valid");
                        Sifre.addClass("is-invalid");
                        Sifre.focus();
                        Durum.addClass("text-danger");
                        Durum.text("Şifreniz yanlış");
                    }else if(data == "Silindi"){
                        $this.prop("disabled", false);
                        KullaniciAdi.prop("disabled", false);
                        Sifre.prop("disabled", false);
                        Durum.addClass("text-danger");
                        Durum.html("Hesabınız silinmiştir geri almak için <a href='hesap-yukle'>tıklayınız</a>");
                    }else if(data == "Pasif"){
                        $this.prop("disabled", false);
                        KullaniciAdi.prop("disabled", false);
                        Sifre.prop("disabled", false);
                        Durum.addClass("text-danger");
                        //Durum.html("Hesabınız dondurulmuştur açmak için <a href='hesap-ac'>tıklayınız</a>");
                        Durum.html("Hesabınız dondurulmuştur");
                    }else if(data == true){
                        $this.addClass("btn-success");
                        Durum.removeClass("text-danger");
                        Durum.addClass("text-success");
                        Durum.text("Giriş yapılıyor...");
                        Sifre.addClass("is-valid");
                        KullaniciAdi.addClass("is-valid");
                        setTimeout(function() {
                            location.href = "/"; // veya "/"
                        }, 1000);
                    }else if(data == "Eposta"){
                        Durum.addClass("text-danger");
                        Durum.text("E-Mail Doğrulayınız...");
                        setTimeout(function() {
                            location.href = "mail-dogrula?k="+KullaniciAdi.val(); // veya "/"
                        }, 1000);
                    }else{
                        $this.prop("disabled", false);
                        KullaniciAdi.prop("disabled", false);
                        Sifre.prop("disabled", false);
                        Durum.addClass("text-danger");
                        Durum.text(data + " hesabınız açılacaktır");
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }else{
            Sifre.focus();
            KullaniciAdi.removeClass("is-invalid");
            Sifre.addClass("is-invalid");
        }
    }else{
        KullaniciAdi.focus();
        Sifre.removeClass("is-invalid");
        KullaniciAdi.addClass("is-invalid");
    }

});
// 2: hesap oluştur
$("#hesap-olustur").on("click", function () {
    var KullaniciAdi = $("#KullaniciAdi");
    var Sifre = $("#Sifre");
    var Tekrar = $("#TekrarSifre");
    var Durum = $("#GirisDurum");
    var Email = $("#Email");
    var KayitKod = $("#KayitKod");
    var invalidCheck = $("#invalidCheck");
    var topluluk = $("#topluluk");
    var $this = $(this);
    if(KullaniciAdi.val() != "" && KullaniciAdi.val().length > 4){
        if(Email.val() != ""){
            if(Sifre.val() != "" && Sifre.val().length > 7){
                if(Tekrar.val() != ""){
                    if (Sifre.val() == Tekrar.val()) {
                        if (invalidCheck.is(":checked")) {
                            if (topluluk.is(":checked")) {
                                $this.prop("disabled", true);
                                KullaniciAdi.prop("disabled", true);
                                Sifre.prop("disabled", true);
                                KullaniciAdi.removeClass("is-invalid");
                                Sifre.removeClass("is-invalid");
                                $.ajax({
                                    url: url,
                                    method: "POST",
                                    data: {
                                        KullaniciAdi: KullaniciAdi.val().toLowerCase(),
                                        Sifre: Sifre.val(),
                                        KayitKod: KayitKod.val(),
                                        Email: Email.val().toLowerCase(),
                                        ajax: 2
                                    },
                                    success: function (data) {
                                        console.log(data);
                                        if (data == "KullaniciVar") {
                                            $this.prop("disabled", false);

                                            KullaniciAdi.prop("disabled", false);
                                            Sifre.prop("disabled", false);

                                            KullaniciAdi.addClass("is-invalid");
                                            Tekrar.removeClass("is-invalid");

                                            KullaniciAdi.focus();
                                            Durum.addClass("text-danger");
                                            Durum.text("Kullanıcı adı daha önce alınmış!");
                                        } else if (data == "EMailVar") {
                                            $this.prop("disabled", false);
                                            Email.focus();
                                            Durum.addClass("text-danger");
                                            Durum.text("Mail adresi zaten kayıtlı!");
                                        } else if (data == true) {
                                            Durum.removeClass("text-danger");
                                            Durum.addClass("text-success");
                                            Durum.text("Hesabınız oluşturuldu! Yönlendiriliyorsunuz...");
                                            setTimeout(function () {
                                                //location.href = "mail-dogrula?k="+KullaniciAdi.val(); // veya "/"
                                                location.href = "giris?kullaniciAdi=" + KullaniciAdi.val().toLowerCase() + "&sifre=" + Sifre.val();
                                            }, 1500);
                                        } else {
                                            Durum.removeClass("text-success");
                                            Durum.addClass("text-danger");
                                            Durum.text(data);
                                        }
                                    },
                                    error: function (data) {
                                        console.log(data);
                                    }
                                });
                            } else {
                                Durum.addClass("text-danger");
                                Durum.text("Lütfen Topluluk Kurallarını kabul edin!");
                                topluluk.focus();
                            }
                        } else {
                            Durum.addClass("text-danger");
                            Durum.text("Lütfen KVKK Şartlarını kabul edin!");
                            invalidCheck.focus();
                        }
                    }else{
                        Tekrar.focus();
                        Tekrar.addClass("is-invalid");
                        KullaniciAdi.focus();
                        Durum.addClass("text-danger");
                        Durum.text("Şifreler uyuşmuyor!");
                    }
                }else{
                    Tekrar.focus();
                    Sifre.removeClass("is-invalid");
                    Tekrar.addClass("is-invalid");
                }
            }else{
                Sifre.focus();
                KullaniciAdi.removeClass("is-invalid");
                Email.removeClass("is-invalid");
                Sifre.addClass("is-invalid");
                Durum.addClass("text-danger");
                Durum.text("Şifreniz en az 8 karakter içermelidir!");
            }
        }else{
            Email.focus();
            Sifre.removeClass("is-invalid");
            Email.addClass("is-invalid");
            Durum.addClass("text-danger");
            Durum.text("Lütfen mail adresinizi giriniz!");
        }
    }else{
        KullaniciAdi.focus();
        Sifre.removeClass("is-invalid");
        KullaniciAdi.addClass("is-invalid");
        Durum.addClass("text-danger");
        Durum.text("Kullanıcı adınız en az 5 karakter içermelidir!");
    }

});
// 3: begen
function begen(KullaniciID, IcerikID, eleman, IcerikKullaniciID){
    var $eleman = $(eleman);
    $.ajax({
        url: url,
        method: "POST",
        data: {
            KullaniciID: KullaniciID,
            IcerikID: IcerikID,
            AliciID: IcerikKullaniciID,
            ajax: 3
        },
        success: function (data) {
            if (data == true) {
                $eleman.removeClass("fi-rr-heart");
                $eleman.addClass("fi-ss-heart");
                $("#BegeniText_" + IcerikID).text(function(_, currentText) {
                    return parseInt(currentText) + 1;
                });
                if($("#BegeniText_" + IcerikID).text() == 1){
                    $("#BegeniYok").hide();
                }
                const yeniYorum = `<a href="../u/${KullaniciAdi}" class="list-group-item text-start text-white" id="BegenenKullanici${KullaniciID}">
                                        <img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/${ProfilResmi});background-position: center;background-size: cover;">${KullaniciAdi}<i class="fi ps-1 tick fi-ss-badge-check ${Color}"></i>      
                                    </a>`;
                $('#Begeniler').prepend(yeniYorum);
            }else{
                $eleman.removeClass("fi-ss-heart");
                $eleman.addClass("fi-rr-heart");
                $("#BegeniText_" + IcerikID).text(function(_, currentText) {
                    return parseInt(currentText) - 1;
                });
                $("#BegenenKullanici"+KullaniciID).remove();
                if($("#BegeniText_" + IcerikID).text() == 0){
                const yeniYorum = `<a id="BegeniYok" class="list-group-item text-start text-white">
                                            Henüz beğeni yok...
                                        </a>`;
                $('#Begeniler').prepend(yeniYorum);
                }
            }
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    });
}
// 4: yorum
$("#YorumGonder").on("click", function () {
    var Yorum = $("#IcerikMetni");
    var IcerikID = $("#IcerikId");
    var KullaniciAdi = $("#KullaniciAdi").val();
    var AliciID = $("#AliciID").val();
    var $this = $(this);
    $this.prop("disabled", true);
    var icerikId = IcerikID.val()
    if($.trim(Yorum.val()) !== ""){
        $.ajax({
            url: url,
            method: "POST",
            data: {
                IcerikID: IcerikID.val(),
                Yorum: Yorum.val(),
                AliciID: AliciID,
                ajax: 4
            },
            success: function (data) {
                    let sonuc = data.split(",");
                    id = sonuc[0];
                    img = sonuc[1];
                    if(img == ""){
                        ikon = '<i class="fi fi-ss-user fs-2"></i>';
                    }else{
                        ikon ='<img class="me-2 rounded-circle" style="width: 40px;height: 40px;background-image: url(../images/'+img+');background-position: center;background-size: cover;">';
                    }
                    if(Onaylandi == true){
                         tik = `<i class="fi tick fi-ss-badge-check ps-1 ${Color}"></i>` 
                    }else{
                        tik = ``;
                    }
                    const yorumIcerik = etiketleriLinkeDonustur(Yorum.val());
                    const yeniYorum = `
                  <div class="card text-white my-2 color" id="PostYorum_`+id+`">
                    <button type="button" class="btn text-white" style="display: inline; width: max-content; float: right; position: absolute; right: 0; top: 0;" data-bs-toggle="modal" 
                    data-bs-target="#duzenlePostA`+id+`">
                      <i class="fi fi-ts-dot-pending fs-3 text-muted"></i>
                    </button>
                    <div class="modal fade" id="duzenlePostA`+id+`" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content color-modal">
                          <div class="modal-header text-white">
                            <h1 class="modal-title fs-5">Yorumunu düzenle</h1>
                            <button type="button" class="btn text-white fs-3 p-0" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-ts-circle-xmark"></i></button>
                          </div>
                          <div class="modal-body text-white text-center">
                            <a class="btn btn-sm btn-danger col-8" style="display: none;" id="YorumSilBtn_`+id+`" onclick="YorumSilBtn(`+id+`)"><i class="fi fi-ts-check-circle"></i> Emin misiniz!</a>
                            <a class="btn btn-sm btn-danger col-8" href="javascript:void(0);" onclick="$('#YorumSilBtn_`+id+`').show(); $(this).remove();"><i class="fi fi-rr-trash"></i> Yorumu Sil</a>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-color col-3" data-bs-dismiss="modal">İptal</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-header py-1 px-2">
                      <a class="text-white" href="../u/`+KullaniciAdi+`">
                        `+ikon+`<span class="">`+Ad+tik+` <span class="text-muted"> ● @`+KullaniciAdi+`</span></span>
                      </a>
                    </div>
                    <div class="card-body py-1">
                      `+yorumIcerik+`
                      <p class="text-muted fst-italic">
                        <i class="fi fi-ts-calendar"></i>`+tarihSaat+`
                      </p>
                        <div class="ps-3 d-flex align-items-center">
                            <i class="fi fi-ts-arrow-turn-down-right me-1"></i>
                            <div class="YorumCevapDiv cursor-pointer w-100">
                                <i id="YanitlaBtn${id}" onclick="cevapla(${id}, this)">Yanıtla</i>
                                <div style="display: none;" id="Cevapla${id}" class="input-group">
                                    <input class="form-control color p-0 px-2" id="CevapInput${id}" value="@${KullaniciAdi} ">
                                    <button class="btn btn-sm p-0 px-1 btn-outline-primary YorumGonder" data-kullanici-adi="${KullaniciAdi}" data-icerik-id="${icerikId}" data-yorum-id="${id}" type="button" style="border-color: #86b7fe;color: #86b7fe;">Gönder</button>
                                </div>
                                <ul class="dropdown-menu dropdown2" style="overflow-y: scroll;z-index: 1050;">
                                
                                  </ul>
                            </div>
                        </div>
                        <div class="ps-3" id="YorumCevaplar${id}"></div>
                    </div>
                  </div>
                `;
                    // 1. Yorum DOM'a eklendikten sonra
                    $('#Yorumlar').prepend(yeniYorum);
                    
                    // 2. Dropdown'u seç
                    const dropdown = document.querySelector(`#PostYorum_${id} .dropdown2`);
                    
                    // 3. Kullanıcı adlarını doldur
                    TumKullanicilar.forEach(adi => {
                      const li = document.createElement("li");
                      const a = document.createElement("a");
                      a.className = "dropdown-item";
                      a.href = "#";
                      a.textContent = adi;
                      li.appendChild(a);
                      dropdown.appendChild(li);
                    });
                    
                    // 4. Gerekirse mention input'u aktif et (eğer setupMentionInput fonksiyonunu kullanıyorsan)
                    const input = document.getElementById(`CevapInput${id}`);
                    setupMentionInput(input, dropdown);

                    Yorum.removeClass("is-invalid");
                    Yorum.val("");
                    $("#YorumlarAdet").text(function(_, currentText) {
                        return parseInt(currentText) + 1;
                    });
                    $("#YorumYok").remove();
                    $this.prop("disabled", false);
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }else{
        Yorum.addClass("is-invalid");
        $this.prop("disabled", false);
    }
});
// 5: takip et
$("#TakipEt").on("click", function () {
    var TakipId = $("#UserIDInput").val();
    const button = this;
    var OnayTik = button.getAttribute('data-onay');
    var TikRenk = button.getAttribute('data-tik-renk');
    $.ajax({
       url: url,
       method: "POST",
       data: {
           TakipID: TakipId,
           ajax: 5
        },
        success: function (data) {
            $("#TakipciAdet").text(function(_, currentText) {
                return parseInt(currentText) + 1;
            });
            $("#TakipEt").css("display", "none");
            $("#TakipBirak").css("display", "");
        
            if($("#TakipciAdet").text() == 1){
                $("#TakipciYok").remove();                 
            }
            if(OnayTik == "1"){
                if(TikRenk != ""){
                    Tik = `<i class="fi tick fi-ss-badge-check ps-1 ${TikRenk}"></i>`;
                }else{
                    Tik = `<i class="fi fi-ss-badge-check ps-1 text-primary"></i>`;
                }
            }else{
                Tik = `<i class="fi tick fi-ss-badge-check ps-1 ${TikRenk}"></i>`;
            }
            if(ProfilResmi != ""){
                var kullaniciResmi = "../images/" + ProfilResmi;
                var yeniTakipci = `
                    <a id="user_a_${KullaniciAdi}" href="${KullaniciAdi}" class="list-group-item text-start text-white">
                        <img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(${kullaniciResmi});background-position: center;background-size: cover;">
                        ${KullaniciAdi + Tik}
                    </a>`;
            }else{
                var yeniTakipci = `
                    <a id="user_a_${KullaniciAdi}" href="${KullaniciAdi}" class="list-group-item text-start text-white">
                        <img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(../images/user.png);background-position: center;background-size: cover;">
                        ${KullaniciAdi + Tik}
                    </a>`;
            }
            $(".takipci-list").prepend(yeniTakipci); // Listeye ekler
            console.log(data);
        },
       error: function (data){
           console.log(data);
       }
    });
});
// 6: takip bırak
$("#TakipBirak").on("click", function () {
    var TakipId = $("#UserIDInput").val();
    $.ajax({
       url: url,
       method: "POST",
       data: {
           TakipID: TakipId,
           ajax: 6
       },
       success: function (data) {
            $("#TakipciAdet").text(function(_, currentText) {
                return parseInt(currentText) - 1;
            });
            if($("#TakipciAdet").text() == 0){
                var yeniTakipci = `
                <a id="TakipciYok" class="list-group-item text-start text-white">
                    Takipçi yok...
                </a>`;
                $(".list-group").prepend(yeniTakipci); // Listeye ekler
            }
            $("#TakipBirak").css("display", "none");
            $("#TakipEt").css("display", "");
            $("#user_a_"+KullaniciAdi).remove();
            console.log(data);
       },
       error: function (data){
           console.log(data);
       }
    });
});
// 7: icerik şikayet gönder
$("#PostSikayet").on("click", function () {
    var IcerikId = $("#IcerikIDInput").val();
    var SikayetKonu = $("#SikayetKonu").val();
    var SikayetMetin = $("#SikayetMetin").val();
    var mesaj = $("#SikayetMesaj");
    if(SikayetKonu != "" && SikayetMetin != ""){
        $.ajax({
        url: url,
        method: "POST",
        data: {
            IcerikID: IcerikId,
            Konu: SikayetKonu,
            Metin: SikayetMetin,
            ajax: 7
        },
        success: function (data) {
            mesaj.text("Şikayetiniz başarılı bir şekilde gönderildi. En kısa sürede incelenecektir.").removeClass("text-danger-emphasis").addClass("text-success-emphasis");
        },
        error: function (xhr, status, error) {
            mesaj.text("Bir hata oluştu. Lütfen tekrar deneyin.").removeClass("text-success-emphasis").addClass("text-danger-emphasis");
        }
    });
    }else{
        mesaj.text("Lütfen boş alan bırakmayın.").addClass("text-danger-emphasis");
    }
});
// 8: icerik gizleme gösterme
$("#GonderiGizleGoster").on("click", function () {
    const button = this;
    const icerikId = button.getAttribute('data-id');
    const icerikDurum = button.getAttribute('data-status');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            IcerikID: icerikId,
            Durum: icerikDurum,
            ajax: 8
        },
        success: function (data) {
            if(data == true){
                mesaj = "Gönderiyi Göster";
                $("#IcerikDurumText").text(" - YAYINDA DEĞİL");
                $("#GonderiGGIcon").addClass("fi-rr-eye");
                $("#GonderiGGIcon").removeClass("fi-rr-eye-crossed");
            }else{
                mesaj = "Gönderiyi Gizle";
                $("#IcerikDurumText").text("");
                $("#GonderiGGIcon").removeClass("fi-rr-eye");
                $("#GonderiGGIcon").addClass("fi-rr-eye-crossed");
            }
            $("#GonderiGGText").text(mesaj);
            button.setAttribute('data-status', data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});
// 9: icerik silme
$("#GonderiSilBtn").on("click", function () {
    const button = this;
    const icerikId = button.getAttribute('data-id');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            IcerikID: icerikId,
            ajax: 9
        },
        success: function (data) {
            location.href = "/";
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});
// 10: yorum silme
function YorumSilBtn(id, IcerikID) {
    IcerikId = $("#IcerikId").val();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            YorumID: id,
            IcerikID: IcerikId,
            ajax: 10
        },
        success: function (data) {
            $("#PostYorum_"+id).remove();
            let yeniYorumSayisi = 0;
            $("#YorumlarAdet").text(function(_, currentText) {
                yeniYorumSayisi = parseInt(currentText) - 1;
                return yeniYorumSayisi;
            });
            if(yeniYorumSayisi == 0){
                $("#Yorumlar").html('<span id="YorumYok">Henüz yorum yok</span>');
            }
            $(".modal-backdrop.fade.show").hide();
            $(".modal.fade.show").hide();
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });    
}
// 11: yorum şikayet gönder
function SikayetYorum(id) {
    var SikayetKonu = $("#YorumSikayetKonu_"+id).val();
    var SikayetMetin = $("#YorumSikayetMetin_"+id).val();
    var mesaj = $("#YorumSikayetMesaj_"+id);
    if(SikayetKonu != "" && SikayetMetin != ""){
        $.ajax({
        url: url,
        method: "POST",
        data: {
            IcerikID: id,
            Konu: SikayetKonu,
            Metin: SikayetMetin,
            ajax: 11
        },
        success: function (data) {
            mesaj.text("Şikayetiniz başarılı bir şekilde gönderildi. En kısa sürede incelenecektir.").removeClass("text-danger-emphasis").addClass("text-success-emphasis");
        },
        error: function (xhr, status, error) {
            mesaj.text("Bir hata oluştu. Lütfen tekrar deneyin.").removeClass("text-success-emphasis").addClass("text-danger-emphasis");
        }
    });
    }else{
        mesaj.text("Lütfen boş alan bırakmayın.").addClass("text-danger-emphasis");
    }
}
// 12: kullanıcı bilgilerini güncelleme
$(".KullaniciInput").on("input", function () {
    const VeriId = this.id;
    const Column = this.getAttribute('data-column');
    const UserID = this.getAttribute('data-user-id');
    var input = $(this);

    var rawVeri = input.val().replace(/\D/g, ''); // sadece rakamları al

    // Sadece TelefonNumarasi için anlık formatlama
    if (Column == "TelefonNumarasi") {
        var displayVeri = rawVeri;
        if (rawVeri.length > 3) {
            displayVeri = `(${rawVeri.substr(0, 3)}) ${rawVeri.substr(3)}`;
        }
        if (rawVeri.length > 6) {
            displayVeri = `(${rawVeri.substr(0, 3)}) ${rawVeri.substr(3, 3)} ${rawVeri.substr(6)}`;
        }
        if (rawVeri.length > 8) {
            displayVeri = `(${rawVeri.substr(0, 3)}) ${rawVeri.substr(3, 3)} ${rawVeri.substr(6, 2)} ${rawVeri.substr(8, 2)}`;
        }
        input.val(displayVeri);
    }

    // AJAX'a sadece rakamları gönder
    $.ajax({
        url: url,
        method: "POST",
        data: {
            Veri: Column == "TelefonNumarasi" ? rawVeri : input.val(),
            Column: Column,
            UserID: UserID,
            ajax: 12
        },
        success: function (data) {
            if (data != "Zaten kayıtlı!") {
                if (Column == "Biyografi") {
                    $("#BiyografiText").text(input.val());
                }
                if (Column == "Ad") {
                    $("#AdText").text(input.val());
                }

                // görsel geri bildirim
                input.addClass("is-valid").removeClass("is-invalid");
                setTimeout(function () {
                    input.removeClass("is-valid");
                }, 1500);

                if (Column == "Email" && input.val() != "") {
                    $("#EMailHata").text("");
                    $("#EmailDogrulama").html(`<span class="text-danger">(Doğrulanmadı) <a href="../mail-dogrula?user=${data}">Doğrula</a></span>`);
                } else {
                    $("#EMailHata").text("");
                    $("#EmailDogrulama").html('');
                }
            } else {
                $("#EMailHata").text(data);
                input.addClass("is-invalid");
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 13: hesap dondurma doğrulama kodu
$("#HesapDondurmaKontrol").on("click", function () {
    var button = this;
    var userId = button.getAttribute('data-user-id');
    var kod = $("#kod").val();

    $.ajax({
        url: url,
        method: "POST",
        data: {
            userId: userId,
            kod: kod,
            ajax: 13
        },
        success: function (data) {
            if(data == true){
                location.href = "/e.php";
            }else{
                $("#sonuc").text(data);
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 14: hesap silme doğrulama kodu
$("#HesapSilmeKontrol").on("click", function () {
    var button = this;
    var userId = button.getAttribute('data-user-id');
    var kod = $("#kod").val();

    $.ajax({
        url: url,
        method: "POST",
        data: {
            userId: userId,
            kod: kod,
            ajax: 14
        },
        success: function (data) {
            if(data == true){
                location.href = "/e.php";
            }else{
                $("#sonuc").text(data);
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 15: hesap açma aşama 1
$("#HesapAc").on("click", function () {
    var email = $("#EMailAdres").val();
    if(email != ""){
        $.ajax({
        url: url,
        method: "POST",
        data: {
            email: email,
            ajax: 15
        },
        success: function (data) {
            if(data == ""){
                $("#sonuc").text("Lütfen kayıtlı bir mail adresi giriniz!");
            }else{
                location.href = "?check=OK";
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
    }else{
        $("#EMailAdres").addClass("is-invalid");
    }
});

// 16: hesap açma aşama 2 (son)
$("#HesapAcmaKontrol").on("click", function () {
    var button = this;
    var userId = button.getAttribute('data-user-id');
    var kod = $("#kod").val();

    $.ajax({
        url: url,
        method: "POST",
        data: {
            userId: userId,
            kod: kod,
            ajax: 16
        },
        success: function (data) {
            if(data == true){
                location.href = "/giris";
            }else{
                $("#sonuc").text(data);
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 17: hesap açma aşama 1
$("#HesapYukle").on("click", function () {
    var email = $("#EMailAdres").val();
    if(email != ""){
        $.ajax({
        url: url,
        method: "POST",
        data: {
            email: email,
            ajax: 17
        },
        success: function (data) {
            if(data == ""){
                $("#sonuc").text("Lütfen kayıtlı bir mail adresi giriniz!");
            }else{
                location.href = "?check=OK";
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
    }else{
        $("#EMailAdres").addClass("is-invalid");
    }
});

// 18: hesap açma aşama 2 (son)
$("#HesapYuklemeKontrol").on("click", function () {
    var button = this;
    var userId = button.getAttribute('data-user-id');
    var kod = $("#kod").val();

    $.ajax({
        url: url,
        method: "POST",
        data: {
            userId: userId,
            kod: kod,
            ajax: 18
        },
        success: function (data) {
            if(data == true){
                location.href = "/e.php";
            }else{
                $("#sonuc").text(data);
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 19: email dondurma doğrulama kodu
$("#EMailKontrol").on("click", function () {
    var button = this;
    var userId = button.getAttribute('data-user-id');
    var userName = button.getAttribute('data-username');
    var kod = $("#kod").val();

    $.ajax({
        url: url,
        method: "POST",
        data: {
            userId: userId,
            kod: kod,
            ajax: 19
        },
        success: function (data) {
            if(data == true){
                location.href = "/u/" + userName;
            }else{
                $("#sonuc").text(data);
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 20: bildirim okundu
$("#notification-modal").on("click", function () {
    $.ajax({
        url: url,
        method: "POST",
        data: {
            ajax: 20
        },
        success: function (data) {
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 21: ürün satın alma
$(".SatinAl").on("click", function () {
    var button = $(this);
    var urunId = button.attr('data-urun-id');
    var tack = button.attr('data-urun-tack');
    $.ajax({
        url: url,
        method: "POST",
        data: {
            urunId: urunId,
            ajax: 21
        },
        success: function (data) {
            if (data == true) {
                var adetElement = $("#UrunAdet" + urunId);
                adetElement.text(function(_, currentText) {
                    return parseInt(currentText) - 1;
                });
                var adetElement = $("#MyTack");
                adetElement.text(function(_, currentText) {
                    return parseInt(currentText) - tack;
                });
                button.hide(); 
                $("#Mevcut" + urunId).show();
            }else{
                $("#hata"+urunId).text("Yetersiz Coin!");
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 22: koleksiyon değiştirme
$(".koleksiyon-sticker").on("click", function () {
    var button = $(this);
    var veri = button.attr('data-veri');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            veri: veri,
            ajax: 22
        },
        success: function (data) {
            if (data == true) {
                var imgSrc = '../images/sticker/' + veri + '.png';
                var stickerImg = $('.profil-sticker');

                if (stickerImg.length > 0) {
                    // Varsa src'yi güncelle
                    stickerImg.attr('src', imgSrc);
                } else {
                    // Yoksa oluştur ve #profil-photo içine ekle
                    var newImg = $('<img>', {
                        class: 'profil-sticker',
                        src: imgSrc
                    });
                    $('#profil-photo').append(newImg);
                }
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 23: bildirim okundu
$("#BildirimButon").on("click", function () {
    var button = $(this);
    $.ajax({
        url: url,
        method: "POST",
        data: {
            ajax: 23
        },
        success: function (data) {
            if (data == true) {
                  button.html('<i class="fi fi-ts-bell p-0"></i>');
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 24: yorum cevap silme
$(document).on("click", ".YorumSilBtn", function () {
    const button = this;
    const yorumId = button.getAttribute('data-yorum-id');
    $.ajax({
        url: url,
        method: "POST",
        data: {
            yorumId: yorumId,
            ajax: 24
        },
        success: function (data) {
            $(".SilLiClass" + yorumId).remove();
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 25: yorum cevap gönderme
$(document).on("click", ".YorumGonder", function () {
    const button = this;
    const yorumId = button.getAttribute('data-yorum-id');
    const icerikId = button.getAttribute('data-icerik-id');
    const YorumCevapKullaniciAdi = button.getAttribute('data-kullanici-adi');
    var yorum = $("#CevapInput"+yorumId).val();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            yorumId: yorumId,
            icerikId: icerikId,
            yorum: yorum,
            ajax: 25
        },
        success: function (data) {
            console.log(yorum);
            if(Onaylandi == true){
                 tik = `<i class="fi tick fi-ss-badge-check ps-1 ${Color}"></i>` 
            }else{
                tik = ``;
            }
            const yorumLinkli = yorum.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/u/$1">@$1</a>');
            const yeniYorum = `<li class="nav-link my-2 ps-3 position-relative" id="YorumCevapLi${data}">
                <a href="../u/${KullaniciAdi}">
                    <img class="rounded-circle" style="width: 30px;height: 30px;background-image: url(../images/${ProfilResmi});background-position: center;background-size: cover;">
                    <b class="text-light">@${KullaniciAdi}${tik}:</b>
                </a>
                <span>${yorumLinkli} <i data-yorum-id="${data}" class="YorumSilBtn fs-5 cursor-pointer text-danger fi fi-ts-circle-trash p-0" style="position: absolute;left: -0.5rem;top: 0.1rem;"></i></span>
            </li>`;
            $("#CevapInput"+yorumId).val("@"+YorumCevapKullaniciAdi+" ");
            $('#YorumCevaplar'+yorumId).prepend(yeniYorum);
            $("#Cevapla"+yorumId).hide();
            $("#YanitlaBtn"+yorumId).show();
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 26: anket cevaplama
$(".anket-cevap-btn").on("click", function () {
    var button = $(this);
    var veri = button.attr('data-veri');
    var anket = button.attr('data-anket');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            anket: anket,
            veri: veri,
            ajax: 26
        },
        success: function (data) {
            $("#AnketSecenekleri"+anket+" :input").prop("disabled", true);
            $("#AnketTik"+anket).show();
            //$("#YuzdeKac").show();
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 27: koleksiyon değiştirme
$(".koleksiyon-banner").on("click", function () {
    var button = $(this);
    var veri = button.attr('data-veri');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            veri: veri,
            ajax: 27
        },
        success: function (data) {
            if (data == true) {
                var imgSrc = '../images/banner/' + veri;
                var stickerImg = $('.card-img-top');

                if (stickerImg.length > 0) {
                    // Eğer elementin background-image'ı değiştirilecekse
                    stickerImg.css('background-image', 'url(' + imgSrc + ')');
                }
            }
            console.log(data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});
// 28: hesap dondurma (yetkili)
$(document).on("click", "#HesapDondurBtn", function () {
    const button = this;
    const kullaniciID = button.getAttribute('data-id');
    const veri = button.getAttribute('data-veri');
    $.ajax({
        url: url,
        method: "POST",
        data: {
            kullaniciID: kullaniciID,
            veri: veri,
            ajax: 28
        },
        success: function (data) {
            location.href = "../"; // veya "/"
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});
// 29: icerik sabitleme
$("#GonderiSabitle").on("click", function () {
    const button = this;
    const icerikId = button.getAttribute('data-id');
    const icerikDurum = button.getAttribute('data-status');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            IcerikID: icerikId,
            Durum: icerikDurum,
            ajax: 29
        },
        success: function (data) {
            if (data == true) {
                mesaj = "Sabitle";
                $("#SabitUyari").text("Gönderi sabitlenmesi kaldırıldı");
                $("#GonderiSabitIcon").removeClass("fi-rr-location-exclamation");
                $("#GonderiSabitIcon").addClass("fi-rr-thumbtack");
            } else {
                mesaj = "Sabitleme";
                $("#SabitUyari").text("Gönderi sabitlendi");
                $("#GonderiSabitIcon").addClass("fi-rr-location-exclamation");
                $("#GonderiSabitIcon").removeClass("fi-rr-thumbtack");
            }
            $("#GonderiSabitText").text(mesaj);
            button.setAttribute('data-status', data);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});
// 30: yorum cevap gönderme
$(document).on("click", ".YorumCevapGonder", function () {
    const button = this;
    const yorumId = button.getAttribute('data-yorum-id');
    const icerikId = button.getAttribute('data-icerik-id');
    const YorumCevapKullaniciAdi = button.getAttribute('data-kullanici-adi');
    var yorum = $("#CevaplaYorumInput" + yorumId).val();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            yorumId: yorumId,
            yorum: yorum,
            icerikId: icerikId,
            ajax: 30
        },
        success: function (data) {
            if (Onaylandi == true) {
                tik = `<i class="fi tick fi-ss-badge-check ps-1 ${Color}"></i>`
            } else {
                tik = ``;
            }
            const yorumLinkli = yorum.replace(/@([a-zA-Z0-9_]+)/g, '<a href="/u/$1">@$1</a>');
            const yeniYorum = `<li class="nav-link my-2 position-relative" id="CevapYorumLi${data}" style="margin-left: 2.1rem;">
                <a href="../u/${KullaniciAdi}">
                    <img class="rounded-circle" style="width: 30px;height: 30px;background-image: url(../images/${ProfilResmi});background-position: center;background-size: cover;">
                    <b class="text-light">@${KullaniciAdi}${tik}:</b>
                </a>
                <span>${yorumLinkli} <i data-yorum-id="${data}" class="CevapYorumSilBtn fs-5 cursor-pointer text-danger fi fi-ts-circle-trash p-0" style="position: absolute;left: -1.6rem;top: 0.1rem;"></i></span>
            </li>`;
            $("#CevaplaYorumInput" + yorumId).val("@" + YorumCevapKullaniciAdi + " ");
            $('#YorumlarCevapliDiv' + yorumId).prepend(yeniYorum);
            $("#CevaplaP" + yorumId).show();
            $("#CevaplaYorum" + yorumId).hide();
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 31: yorum cevap silme
$(document).on("click", ".CevapYorumSilBtn", function () {
    const button = this;
    const yorumId = button.getAttribute('data-yorum-id');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            yorumId: yorumId,
            ajax: 31
        },
        success: function (data) {
            $("#CevapYorumLi" + yorumId).remove();
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});

// 32: yetki değiştirme
$('#yetkiSelect').change(function () {
    var yetkiID = $(this).val();
    var kullaniciID = $(this).find(':selected').data('kullanici-id');

    var adi = $(this).find(':selected').data('yetki-adi');
    var color = $(this).find(':selected').data('yetki-color');
    var icon = $(this).find(':selected').data('yetki-icon');

    $.ajax({
        url: url,
        method: "POST",
        data: {
            yetkiID: yetkiID,
            kullaniciID: kullaniciID,
            ajax: 32
        },
        success: function (data) {
            $("#YetkiAdiSmall").text(adi);
            $("#YetkiIcon").removeClass().addClass("fi p-0 fi-ts-" + icon);
            $("#YetkiKonum").removeClass().addClass("text-" + color);
        },
        error: function (xhr, status, error) {
            console.error("Hata:", error);
        }
    });
});