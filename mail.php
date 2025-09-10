<?php 
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

try {
    // Sunucu ayarları
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->SMTPDebug = 2; // Hata ayıklama için aç
    $mail->Host       = 'mail.supro.com.tr'; // SMTP
    $mail->SMTPAuth   = true;$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
    $mail->Username   = 'merhaba@supro.com.tr'; // Kendi mail adresin
    $mail->Password   = '077b9029-1e5';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465; // 465 için SMTPS, 587 için STARTTLS olmalı

    // Gönderen ve alan kişi bilgileri
    $mail->setFrom('merhaba@supro.com.tr', "Supro");
    $mail->addReplyTo('merhaba@supro.com.tr', "Supro");

    $bugun = date("d.m.Y H:i:s");
    
    // GET İLE KESİN GELMESİ GEREKEN
    $alici = clean($_GET["alici"]);
    $konu = clean($_GET["konu"]);
    $mesaj = $_GET["mesaj"];
 
    if (filter_var($alici, FILTER_VALIDATE_EMAIL)) {
        // İçerik
        $mail->addAddress("$alici");
        $mail->isHTML(true);
        $mail->Subject = "$konu"; // Konu
        $mail->Body    =  '<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="center" style="padding: 40px 0;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
          
          <!-- Üst Başlık -->
          <tr>
            <td align="center" bgcolor="#0d6efd" style="padding: 15px 0; color: #ffffff; font-size: 28px; font-weight: bold;">
              <img src="http://supro.com.tr/images/SuperIcon.png" width="80px" style="border-radius: 25px 5px;">
            </td>
          </tr>

          <!-- İçerik -->
          <tr>
            <td style="padding: 40px 30px; color: #333333; font-size: 18px; line-height: 1.6;">
              <p>Merhaba,</p>
              '.$mesaj.'
            </td>
          </tr>

          <!-- Buton -->
          <tr>
            <td align="center" style="padding: 20px;">
              <a href="http://supro.com.tr/" target="_blank" style="background-color: #0d6efd; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 18px;">
                Detayları Görüntüle
              </a>
            </td>
          </tr>

          <!-- Alt Bilgi -->
          <tr>
            <td style="padding: 30px; font-size: 14px; color: #999999; text-align: center;">
              Supro | © 2025 Tüm Hakları Saklıdır.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
'; // İçerik
    }
        
    $mail->send();
    //$geri = $_SERVER['HTTP_REFERER'];
    header("Location: " . $_SESSION['last_page']);
    echo 'MAIL BAŞARIYLA GÖNDERİLDİ!';
    exit;
} catch (Exception $e) {
    echo "MAIL GÖNDERME BAŞARISIZ. HATA: {$mail->ErrorInfo}";
}
?>