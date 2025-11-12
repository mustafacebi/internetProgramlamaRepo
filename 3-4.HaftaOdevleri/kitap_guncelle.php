<?php
// kitap_guncelle.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu işlemi yapmaya yetkiniz yoktur. (Admin girişi gereklidir)");
}

$mesaj = "";
$kitap = null;
$kitap_id = 0;

// --- 1. POST İşlemi: Form Güncelleme Verisi Geldi mi? ---
if (isset($_POST['kitap_guncelle'])) {
    $kitap_id = $_POST['kitap_id'];
    $ad = trim($_POST['ad']);
    $yazar = trim($_POST['yazar']);
    $stok = (int)$_POST['stok'];
    
    // Veritabanı güncelleme işlemi
    try {
        $sorgu = $db->prepare("UPDATE kitaplar SET ad = :ad, yazar = :yazar, stok = :stok WHERE kitap_id = :id");
        
        $sorgu->bindParam(':id', $kitap_id);
        $sorgu->bindParam(':ad', $ad);
        $sorgu->bindParam(':yazar', $yazar);
        $sorgu->bindParam(':stok', $stok);

        if ($sorgu->execute()) {
            $mesaj = "Kitap başarıyla güncellendi! ✅";
            
            // Güncelleme sonrası güncel veriyi tekrar çekelim ki formda görünsün
            header("Location: kitap_guncelle.php?id=" . $kitap_id . "&status=success");
            exit;
        }

    } catch (PDOException $e) {
        $mesaj = "Hata: Güncelleme sırasında bir sorun oluştu: " . $e->getMessage();
    }
} 

// --- 2. GET İşlemi: Düzenlenecek Kitabı Çekme ---
// POST yapılmadıysa VEYA POST işlemi bittiyse (header ile yönlendirmeden önce)
// Kitap ID'si ya POST'tan gelir ya da URL'den (GET) gelir.
if (isset($_GET['id'])) {
    $kitap_id = $_GET['id'];
} else if (isset($_POST['kitap_id'])) {
    $kitap_id = $_POST['kitap_id']; // POST başarısız olursa formu yeniden doldurmak için
}

// Kitap ID'si varsa veriyi veritabanından çek
if ($kitap_id > 0) {
    $sorgu = $db->prepare("SELECT * FROM kitaplar WHERE kitap_id = :id");
    $sorgu->bindParam(':id', $kitap_id);
    $sorgu->execute();
    $kitap = $sorgu->fetch(PDO::FETCH_ASSOC);

    if (!$kitap) {
        die("Hata: Düzenlenecek kitap bulunamadı.");
    }
} else {
    die("Hata: Düzenlemek için kitap ID'si belirtilmemiş.");
}

// Başarılı yönlendirmeden sonra gelen mesajı göster
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $mesaj = "Kitap başarıyla güncellendi! ✅";
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitap Düzenle: <?php echo htmlspecialchars($kitap['ad']); ?></title>
</head>
<body>
    
    <h1>✏️ Kitap Düzenle (ID: <?php echo htmlspecialchars($kitap['kitap_id']); ?>)</h1>
    <p><a href="kitap_listesi.php">← Kitap Listesine Geri Dön</a></p>

    <?php 
    if (!empty($mesaj)) {
        echo "<p style='color: " . (strpos($mesaj, 'Hata') !== false ? 'red' : 'green') . "; border: 1px solid; padding: 10px;'>" . htmlspecialchars($mesaj) . "</p>";
    }
    ?>

    <form action="" method="POST">
        
        <input type="hidden" name="kitap_id" value="<?php echo htmlspecialchars($kitap['kitap_id']); ?>">
        
        <label for="ad">Kitap Adı:</label><br>
        <input type="text" id="ad" name="ad" required 
               value="<?php echo htmlspecialchars($kitap['ad']); ?>"><br><br>

        <label for="yazar">Yazar:</label><br>
        <input type="text" id="yazar" name="yazar" required 
               value="<?php echo htmlspecialchars($kitap['yazar']); ?>"><br><br>
        
        <label for="stok">Stok Adedi:</label><br>
        <input type="number" id="stok" name="stok" min="0" required 
               value="<?php echo htmlspecialchars($kitap['stok']); ?>"><br><br>

        <button type="submit" name="kitap_guncelle">Kitabı Güncelle</button>
    </form>

</body>
</html>