<?php
// kitap_ekle.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu işlemi yapmaya yetkiniz yoktur. (Admin girişi gereklidir)");
}

$mesaj = ""; // Kullanıcıya gösterilecek mesaj

// --- 1. POST İşlemi: Formdan Veri Geldi mi? ---
if (isset($_POST['kitap_ekle'])) {
    // Güvenlik için trim yapalım
    $ad = trim($_POST['ad']);
    $yazar = trim($_POST['yazar']);
    // Stok'un sayı olduğundan emin olalım
    $stok = (int)$_POST['stok']; 

    if (empty($ad) || empty($yazar)) {
        $mesaj = "Hata: Kitap adı ve yazar boş bırakılamaz.";
    } else {
        try {
            $sorgu = $db->prepare("INSERT INTO kitaplar (ad, yazar, stok) VALUES (:ad, :yazar, :stok)");
            
            // Güvenlik için bindParam kullanın
            $sorgu->bindParam(':ad', $ad);
            $sorgu->bindParam(':yazar', $yazar);
            $sorgu->bindParam(':stok', $stok);
            
            $sorgu->execute();
            $mesaj = "Kitap başarıyla eklendi! 🎉";
            // Başarılı eklemeden sonra formu temizlemek için değişkenleri sıfırlayabiliriz:
            $ad = $yazar = $stok = ''; 

        } catch (PDOException $e) {
            // Hata yakalama (Örn: ISBN unique hatası vb.)
            $mesaj = "Ekleme sırasında bir hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Kitap Ekle</title>
</head>
<body>
    <h1>📚 Yeni Kitap Ekle</h1>
    <p><a href="admin_panel.php">← Admin Paneline Dön</a></p>

    <?php 
    // İşlem sonucunu göster
    if (!empty($mesaj)) {
        echo "<p style='color: " . (strpos($mesaj, 'Hata') !== false ? 'red' : 'green') . "; border: 1px solid; padding: 10px;'>" . htmlspecialchars($mesaj) . "</p>";
    }
    ?>

    <form action="" method="POST">
        
        <label for="ad">Kitap Adı:</label><br>
        <input type="text" id="ad" name="ad" required value="<?php echo isset($ad) ? htmlspecialchars($ad) : ''; ?>"><br><br>

        <label for="yazar">Yazar:</label><br>
        <input type="text" id="yazar" name="yazar" required value="<?php echo isset($yazar) ? htmlspecialchars($yazar) : ''; ?>"><br><br>
        
        <label for="stok">Stok Adedi:</label><br>
        <input type="number" id="stok" name="stok" min="0" required value="<?php echo isset($stok) ? htmlspecialchars($stok) : '1'; ?>"><br><br>

        <button type="submit" name="kitap_ekle">Kitabı Veritabanına Ekle</button>
    </form>

</body>
</html>