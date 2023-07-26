<?php
// Oturumu başlatın
session_start();

// Eğer kullanıcı zaten oturum açmışsa, oturumu sonlandırın
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Tüm oturum değişkenlerini temizleyin
    $_SESSION = array();

    // Oturumu sonlandırın
    session_destroy();

    // Kullanıcıyı ana sayfaya yönlendirin
    header("location: index.html");
    exit;
} else {
    // Eğer oturum açmamışsa, zaten çıkış yapılmış kabul edin ve ana sayfaya yönlendirin
    header("location: index.html");
    exit;
}
?>
