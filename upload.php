<?php
if (isset($_FILES['audio'])) {
    $file = $_FILES['audio'];
    $uploadDir = 'voices/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $tempWebmPath = $uploadDir . uniqid('temp_', true) . '.webm';
    $mp3Name = uniqid('audio_', true) . '.mp3';
    $mp3Path = $uploadDir . $mp3Name;

    if (move_uploaded_file($file['tmp_name'], $tempWebmPath)) {
        // ffmpeg ile dönüştür
        $cmd = "ffmpeg -i $tempWebmPath -vn -ar 44100 -ac 2 -b:a 192k $mp3Path";
        exec($cmd, $output, $resultCode);

        // webm dosyasını sil
        //unlink($tempWebmPath);

        if ($resultCode === 0) {
            echo "MP3 başarıyla kaydedildi: $mp3Name";
        } else {
            echo "Dönüştürme başarısız!";
        }
    } else {
        echo "Dosya yüklenemedi!";
    }
} else {
    echo "Ses verisi yok!";
}
?>
