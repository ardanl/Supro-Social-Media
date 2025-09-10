<title><?= $SiteAdi; ?></title>
<?php
    if($KullaniciID){
        include "etkinlikler.php";
        include "anketler.php";
            
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $device = getDeviceType($userAgent);
        $browser = getBrowser($userAgent);
        $os = getOS($userAgent);
    
        $db->Update("Kullanici",
        [
            "CihazTur" => $device,
            "Tarayici" => $browser,
            "IsletimSistemi" => $os,
            "UserAgent" => $userAgent
        ],
        [
            "KullaniciID" => $KullaniciID
        ]);
    }
?> 
<h4 class='mt-3 ps-3'><img class="rounded" src="images/SuperIcon.png" width="50px"> Gönderiler</h4>
<?php 
    if($KullaniciID){ 
        include "bildirim.php";
    }else{ ?>
<!--<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">🎉 <?= $SiteAdi; ?>'e Hoş Geldin!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <p>Burası fikirlerin, hayallerin ve tartışmaların buluşma noktası!</p>
                <p>Sven'de düşüncelerini özgürce paylaşabilir, başkalarının fikirlerine yorum yapabilir ve ilham verici sohbetlerin bir parçası olabilirsin.</p>
                
                <h6>
                🔐 Giriş yaparak neler kazanırsın?</h6>
                <ul>
                    <li>
                📢 Kendi gönderilerini paylaş</li>
                    <li>
                💬 Yorum yap ve etkileşim kur</li>
                    <li>
                ❤️ Beğeni topla, topluluğun bir parçası ol</li>
                    <li>
                📈 Popüler konuları takip et</li>
                    <li>
                🛡️ Profilini oluştur, kendini tanıt</li>
                </ul>
                <p>Hadi, katıl bize!</p>
                
                <p>👉 Giriş yaparak <?= $SiteAdi; ?>’un tüm ayrıcalıklarının kilidini aç!</p>
              
      </div>
      <div class="modal-footer">
        <a href="giris" class="btn btn-primary">Giriş Yap</a>
      </div>
    </div>
  </div>
</div>-->

<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (!sessionStorage.getItem('modalShown')) {
      var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
      myModal.show();

      document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
        sessionStorage.setItem('modalShown', 'true');
      });
    }
  });
</script>

    <?php  }
    include "icerikler.php";
?>