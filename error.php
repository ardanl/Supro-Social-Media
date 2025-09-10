<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $SiteAdi; ?> - Sayfa Bulunamadı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .error-container {
            position: relative;
            animation: float 3s ease-in-out infinite;
            text-align: center;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .error-text {
            font-size: 100px;
            font-weight: bold;
            color: #dc3545;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .ghost {
            width: 120px;
            animation: ghost-move 2s infinite alternate;
        }

        @keyframes ghost-move {
            0% {
                transform: translateY(-5px);
            }

            100% {
                transform: translateY(5px);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="error-container">
                    <img src="https://cdn-icons-png.flaticon.com/128/620/620689.png" class="ghost">
                    <h1 class="error-text">404</h1>
                    <h3 class="">Oops! Sayfa Bulunamadı</h3>
                    <p class="">Görünüşe göre uzaylılar bu sayfayı kaçırmış gibi...</p>
                    <a href="<?= $Link; ?>" class="btn btn-danger mt-3">Ana Sayfaya Dön</a><br>
                    <a href="<?= $Link; ?>exit" class="btn btn-danger mt-3">Çıkış</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>