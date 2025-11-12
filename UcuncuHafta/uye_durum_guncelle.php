// uye_durum_guncelle.php
require 'db.php';

if (isset($_GET['id']) && isset($_GET['durum'])) {
    $uye_id = $_GET['id'];
    $yeni_durum = $_GET['durum']; // 'pasif' veya 'engelli'

    // Güvenlik: Durum değerinin sadece izin verilen değerler olduğundan emin olun
    if (!in_array($yeni_durum, ['aktif', 'pasif', 'engelli'])) {
        die("Geçersiz durum değeri.");
    }

    $sorgu = $db->prepare("UPDATE kullanicilar SET durum = :durum WHERE id = :id");
    
    $sorgu->bindParam(':id', $uye_id);
    $sorgu->bindParam(':durum', $yeni_durum);

    if ($sorgu->execute()) {
        echo "Üye durumu başarıyla güncellendi. Yeni durum: " . htmlspecialchars($yeni_durum);
    }
    // header("Location: uye_listesi.php");
}