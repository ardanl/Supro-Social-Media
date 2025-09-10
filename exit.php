<?php
    session_destroy();
    $kadi_zaman = time() - (60 * 60 * 24 * 365 * 10);
    setcookie("KullaniciID", "", $kadi_zaman, "/");
    header("location: ./");
?>