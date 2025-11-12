<?php
// admin_panel.php
session_start();

// GÜVENLİK KONTROLÜ:
// Giriş yapılmamışsa VEYA rolü 'admin' değilse login sayfasına yönlendir
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    // Eğer üye ise uyarı verebilir, veya direkt login'e atabiliriz.
    // Şimdilik direkt login'e atalım.
    header("Location: login.php");
    exit;
}

$username = $_SESSION['kulad'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli Yönetim Sayfası</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; }
        .menu-item { display: block; margin: 10px 0; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9; text-decoration: none; color: #333; }
        .menu-item:hover { background-color: #eee; }
    </style>
</head>
<body>
    
    <h1>Hoşgeldiniz, <?php echo htmlspecialchars($username); ?>! (Yönetici)</h1>
    <p>Bu alan, sadece yönetici yetkisine sahip kullanıcıların erişimine açıktır.</p>
    
    <hr>

    <h2>⚙️ Yönetim Menüsü (CRUD İşlemleri)</h2>
    
    <h3>📚 Kitap Yönetimi</h3>
    
    <a href="kitap_ekle.php" class="menu-item">Yeni Kitap Ekle (Create)</a>
    <a href="kitap_listesi.php" class="menu-item">Mevcut Kitapları Listele/Düzenle/Sil (Read, Update, Delete)</a>
    
    <hr>
    
    <h3>👤 Üye Yönetimi</h3>
    
    <a href="uye_ekle_formu.php" class="menu-item">Yeni Üye Kaydı (Örn: Elle Kayıt)</a>
    <a href="uye_listesi.php" class="menu-item">Üye Durumlarını Yönet (Pasif/Engelle)</a>
    
    <hr>

    <p>
        <a href="cikis.php">Güvenli Çıkış Yap</a>
    </p>

</body>
</html>