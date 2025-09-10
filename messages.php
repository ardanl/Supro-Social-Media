<?php 
if(isset($_GET["receiver_id"])){
    $MesajKisi = $db->Get("Kullanici")->Where("KullaniciID", $_GET["receiver_id"]);
    $MesajKisi = $MesajKisi[0];
    $db->Update("messages", [ "okundu" => "1" ], [ "sender_id" => "$_GET[receiver_id]", "receiver_id" => $KullaniciID]);
?>
<style>
    .chat-container {
        max-width: 600px;
        margin: 50px auto;
        background: transparent;
        border-radius: 20px;
        border:1px solid gray;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 80vh;
    }

    #chat {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .message {
        margin-bottom: 10px;
        padding: 10px 15px;
        border-radius: 15px;
        max-width: 75%;
        word-wrap: break-word;
    }

    .sent {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 0;
    }

    .received {
        background-color: #e4e6eb;
        color: black;
        margin-right: auto;
        border-bottom-left-radius: 0;
    }

    .chat-input {
        display: flex;
        padding: 15px;
        border-top: 1px solid #ddd;
    }

    .chat-input input {
        flex: 1;
        margin-right: 10px;
    }

    .chat-input button {
        white-space: nowrap;
    }
</style>
<title><?= $SiteAdi; ?> - Mesaj</title>
<div class="chat-container mt-0 col-11 m-auto">
    <a href="./u/<?= $MesajKisi["KullaniciAdi"]; ?>" class="px-3 pb-2 my-2 border-bottom">
        <?php 
        if($MesajKisi["ProfilResmi"] != ""){ 
            echo '<img class="me-2 rounded-circle" style="width: 50px;height: 50px;background-image: url(images/'.$MesajKisi["ProfilResmi"].');background-position: center;background-size: cover;">';
        }else{ 
            echo '<i class="fi fi-ss-user fs-1"></i>'; 
        }
        if($MesajKisi["Ad"] == ""){
            $MesajKisi["Ad"] = "İsimsiz Kullanıcı";
        }
        echo "<span class='text-white'>".$MesajKisi["Ad"]." ". $MesajKisi["Soyad"];
        if($MesajKisi["Onaylandi"] == true){
            if($MesajKisi["Color"]){
                $Color = $MesajKisi["Color"];
                echo '<i class="fi tick fi-ss-badge-check ' . $Color . '"></i>';
            }else{
                echo '<i class="fi fi-ss-badge-check text-primary"></i>';
            }
        }
        echo "<span class='text-muted'> ● @".$MesajKisi["KullaniciAdi"]."</span></span>"; 
        /*if($Yetki == 4){
        ?>
        <p><small class="text-danger">Uygulamayı kullanmaya başlamadan önce yetkilendirilmeniz gerekiyor. Lütfen kısaca kendinizi tanıtın 😊</small></p>
        <?php }*/ ?>
    </a>
    <div id="chat"></div>
    <div class="chat-input">
        <input class="form-control color" type="text" id="mesaj" placeholder="Mesajını yaz...">
        <button class="btn btn-primary" onclick="mesajGonder()">Gönder</button>
    </div>
</div>

<script>
    let sender_id = <?= $KullaniciID; ?>;
    let receiver_id = <?= $_GET["receiver_id"]; ?>;

    function mesajGonder() {
        let mesaj = document.getElementById("mesaj").value.trim();
        if (mesaj === "") return;

        fetch("mesaj_gonder.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `sender_id=${sender_id}&receiver_id=${receiver_id}&message=${encodeURIComponent(mesaj)}`
        }).then(() => {
            document.getElementById("mesaj").value = "";
            mesajlariGetir();
        });
    }

    document.getElementById("mesaj").addEventListener("keypress", function(e) {
        if (e.key === "Enter") mesajGonder();
    });

    function mesajlariGetir() {
        fetch(`mesajlari_getir.php?sender_id=${sender_id}&receiver_id=${receiver_id}`)
            .then(res => res.json())
            .then(veri => {
                let chat = document.getElementById("chat");
                
                // Mevcut scroll yüksekliğini kontrol et
                let atAlt = chat.scrollHeight - chat.scrollTop - chat.clientHeight < 50;

                chat.innerHTML = veri.map(msg => {
                    let sinif = msg.sender_id == sender_id ? "sent" : "received";
                    return `<div class="message ${sinif}">${msg.message}</div>`;
                }).join("");

                // Sadece kullanıcı en alttaysa scroll et
                if (atAlt) {
                    chat.scrollTop = chat.scrollHeight;
                }
            });
    }

    mesajlariGetir();
    setInterval(mesajlariGetir, 1000);
</script>
<?php 
}else{
    header("location: ./");
}
?>