<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anlık Mesajlaşma Uygulaması</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts - Quicksand -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
        }

        .message-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            height: 500px;
            border: 1px solid #ddd;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        .message-box {
            display: flex;
            flex-direction: column;
        }

        .message {
            display: flex;
            margin: 10px 0;
        }

        .message .sender {
            font-weight: bold;
            margin-right: 10px;
        }

        .message .text {
            max-width: 80%;
        }

        #messageInput {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="message-container" id="messageContainer">
                    <!-- Mesajlar burada görüntülenecek -->
                </div>
                <textarea id="messageInput" class="form-control" rows="2" placeholder="Mesajınızı yazın..."></textarea>
                <button id="sendMessage" class="btn btn-primary mt-2 w-100">Mesaj Gönder</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS ve jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Yeni mesaj gönderme
            $('#sendMessage').click(function() {
                var message = $('#messageInput').val();
                if (message.trim() != "") {
                    sendMessage(message);
                    $('#messageInput').val(""); // Mesaj kutusunu temizle
                }
            });

            // Mesajı server'a gönder
            function sendMessage(message) {
                $.ajax({
                    url: 'send_message.php', // Mesaj gönderim PHP dosyası
                    method: 'POST',
                    data: {
                        message: message
                    },
                    success: function(response) {
                        loadMessages(); // Yeni mesaj yüklendikten sonra mesajları güncelle
                    }
                });
            }

            // Mesajları al
            function loadMessages() {
                $.ajax({
                    url: 'get_messages.php', // Mesajları getiren PHP dosyası
                    method: 'GET',
                    success: function(response) {
                        // JSON verisini parse et
                        var messages = JSON.parse(response);

                        // Mesajları eklemek için container'ı temizle
                        $('#messageContainer').html('');

                        // Mesajları döngüye al
                        messages.forEach(function(item) {
                            // Sadece message kısmını alıp yazdırıyoruz
                            var messageDiv = $('<div>User: </div>').text(item.message);
                            $('#messageContainer').append(messageDiv);
                        });
                    }
                });
            }

            // Sayfa yüklendiğinde mesajları al
            loadMessages();

            // 3 saniyede bir mesajları yenile
            setInterval(loadMessages, 1000);
        });
    </script>
</body>

</html>