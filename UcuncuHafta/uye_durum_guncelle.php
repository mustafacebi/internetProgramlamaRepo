<?php
// uye_durum_guncelle.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu işlemi yapmaya yetkiniz yoktur. (Admin girişi gereklidir)");
}

$mesaj = "";
$izinli_durumlar = ['aktif', 'pasif', 'engelli', 'sil']; // 'sil' de eklendi

// Gerekli Parametrelerin Kontrolü (ID ve Durum)
if (isset($_GET['id']) && isset($_GET['durum'])) {
    $uye_id = (int)$_GET['id'];
    $yeni_durum = strtolower($_GET['durum']);

    // Güvenlik Kontrolü: Gelen durum değeri geçerli mi?
    if (!in_array($yeni_durum, $izinli_durumlar)) {
        $mesaj = "Hata: Geçersiz durum değeri gönderildi.";
    } 
    // Ek Kontrol: Kendi hesabınızı silmenizi/engellemenizi önleyebiliriz (isteğe bağlı)
    /* else if ($uye_id == $_SESSION['id']) { 
        $mesaj = "Hata: Kendi hesabınızın durumunu değiştiremezsiniz.";
    } */
    else {
        // Tamamen Silme (DELETE) İşlemi
        if ($yeni_durum === 'sil') {
            try {
                $sorgu = $db->prepare("DELETE FROM kullanicilar WHERE id = :id");
                $sorgu->bindParam(':id', $uye_id);
                $sorgu->execute();

                if ($sorgu->rowCount() > 0) {
                    $mesaj = "Üye (" . $uye_id . ") veritabanından başarıyla **silindi**. ❌";
                } else {
                    $mesaj = "Hata: Silinecek üye bulunamadı.";
                }
            } catch (PDOException $e) {
                $mesaj = "Silme hatası: " . $e->getMessage();
            }
        } 
        // Durum Güncelleme (UPDATE) İşlemi
        else {
            try {
                $sorgu = $db->prepare("UPDATE kullanicilar SET durum = :durum WHERE id = :id");
                $sorgu->bindParam(':id', $uye_id);
                $sorgu->bindParam(':durum', $yeni_durum);
                $sorgu->execute();

                if ($sorgu->rowCount() > 0) {
                    $mesaj = "Üye durumu başarıyla güncellendi. Yeni durum: **" . htmlspecialchars($yeni_durum) . "** ✅";
                } else {
                     $mesaj = "Hata: Üye bulunamadı veya durum zaten '" . htmlspecialchars($yeni_durum) . "'.";
                }
            } catch (PDOException $e) {
                $mesaj = "Güncelleme hatası: " . $e->getMessage();
            }
        }
    }
} else {
    $mesaj = "Hata: İşlem için gerekli ID veya Durum parametresi eksik.";
}

// Sonuçtan sonra Listeleme sayfasına yönlendirelim
$yonlendirme_url = "uye_listesi.php";

header("refresh:3;url=" . $yonlendirme_url); 
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Üye Durum Güncelleme Sonucu</title>
</head>
<body>
    <h1>👤 Üye Durum Güncelleme Sonucu</h1>
    <?php 
    $renk = (strpos($mesaj, 'Hata') !== false) ? 'red' : 'green';
    echo "<p style='color: {$renk}; border: 1px solid; padding: 10px;'>{$mesaj}</p>";
    echo "<p>3 saniye içinde Listeleme sayfasına yönlendiriliyorsunuz...</p>";
    ?>
</body>
</html>