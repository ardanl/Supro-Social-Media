<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bye</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            color: #fff;
            background: #000;
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
    <div class="container pt-5" data-bs-theme="dark" style="position: fixed;z-index: 1000000050;inset: 0;margin: auto;background: #000;">
        <div class="row pt-5">
            <div class="col-md-8 offset-md-2 pt-5">
                <div class="error-container">
                    <img src="https://supro.com.tr/images/SuperIcon.png" class="ghost rounded mb-3">
                    <h3 class="">Kısa bir ara</h3>
                    <p class="">Uygulamamız, daha iyi bir deneyim sunabilmek için şu anda bakıma aldık.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>