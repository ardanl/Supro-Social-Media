<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>OneSignal Bildirim Testi</title>
  <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
  <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
      await OneSignal.init({
        appId: "429d6fe4-2b12-4670-807c-a91143524dbe",
        promptOptions: {
          slidedown: {
            enabled: true,
            actionMessage: "Bildirim almak ister misiniz?",
            acceptButtonText: "Evet",
            cancelButtonText: "Hayır"
          }
        }
      });
    });
  </script>
</head>
<body>

  <h1>Bildirim Test Sayfası</h1>
  <button onclick="subscribeUser()">Bildirimlere Abone Ol</button>

  <script>
  
    // Fonksiyonu global tanımlıyoruz, sayfa yüklendikten sonra erişilebilir olacak
    function subscribeUser() {
      OneSignalDeferred.push(async function(OneSignal) {
        const permission = await OneSignal.Notifications.requestPermission();
        if (permission === 'granted') {
          alert("Bildirimlere başarıyla abone olundu!");
        } else {
          alert("Abonelik reddedildi.");
        }
      });
    }
  </script>

</body>
</html>
