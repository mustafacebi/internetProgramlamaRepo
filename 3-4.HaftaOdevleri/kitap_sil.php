<?php
// kitap_sil.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu işlemi yapmaya yetkiniz yoktur. (Admin girişi gereklidir)");
}

$mesaj = "";

// 1. Gerekli ID Parametresinin Kontrolü
if (isset($_GET['id'])) {
    // ID'nin sayısal olduğundan emin olalım
    $kitap_id = (int)$_GET['id'];
    
    // ID 0'dan büyük olmalı
    if ($kitap_id <= 0) {
        $mesaj = "Hata: Geçersiz kitap ID'si gönderildi.";
    } else {
        // 2. Veritabanından Silme İşlemi
        try {
            $sorgu = $db->prepare("DELETE FROM kitaplar WHERE kitap_id = :id");
            $sorgu->bindParam(':id', $kitap_id, PDO::PARAM_INT); // ID'nin tam sayı olduğunu belirtelim

            if ($sorgu->execute()) {
                // Silinen satır sayısı 0'dan büyükse başarılıdır
                if ($sorgu->rowCount() > 0) {
                    $mesaj = "Kitap başarıyla silindi. 🗑️";
                } else {
                    $mesaj = "Hata: Belirtilen ID'ye sahip kitap bulunamadı veya daha önce silinmiş.";
                }

            } else {
                $mesaj = "Hata: Silme sorgusu çalıştırılamadı.";
            }

        } catch (PDOException $e) {
            $mesaj = "Veritabanı hatası: " . $e->getMessage();
        }
    }
} else {
    $mesaj = "Hata: Silme işlemi için kitap ID'si eksik.";
}

// Silme işlemi bittikten sonra kullanıcıyı Listeleme sayfasına yönlendirelim
// Yönlendirmeden önce mesajı URL ile gönderebilirsiniz.
// Ancak basit tutmak için, mesajı burada gösterip sonra yönlendirelim.

if (strpos($mesaj, 'Hata') === false) {
    // Başarılıysa, 3 saniye sonra listeleme sayfasına yönlendir
    header("refresh:3;url=kitap_listesi.php");
    $mesaj .= " 3 saniye içinde kitap listesi sayfasına yönlendirileceksiniz...";
} else {
    // Hata varsa yönlendirmeyelim, kullanıcı hatayı görsün
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitap Silme Sonucu</title>
</head>
<body>
    <h1>❌ Kitap Silme Sonucu</h1>
    <p><a href="kitap_listesi.php">← Kitap Listesine Geri Dön</a></p>

    <?php 
    // İşlem sonucunu göster
    $renk = (strpos($mesaj, 'Hata') !== false) ? 'red' : 'green';
    echo "<p style='color: {$renk}; border: 1px solid; padding: 10px;'>{$mesaj}</p>";
    ?>

</body>
</html>