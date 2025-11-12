<?php
// cikis.php
session_start(); // Session'ı sonlandırmak için önce başlatmak gerekir.

// Tüm session değişkenlerini sil
$_SESSION = array();

// Eğer session cookielerini de temizlemek isterseniz:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session'ı tamamen yok et
session_destroy();

// Kullanıcıyı login sayfasına yönlendir
header("Location: index.php");
exit;
?>