<?php
// uye_ekle_formu.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu işlemi yapmaya yetkiniz yoktur. (Admin girişi gereklidir)");
}

$mesaj = "";

// --- 1. POST İşlemi: Formdan Veri Geldi mi? ---
if (isset($_POST['uye_ekle'])) {
    $kulad = trim($_POST['kulad']);
    $sifre = $_POST['sifre'];
    $rol = $_POST['rol'];
    $durum = 'aktif'; // Yeni üyeler varsayılan olarak aktif başlar

    if (empty($kulad) || empty($sifre)) {
        $mesaj = "Hata: Kullanıcı adı ve şifre boş bırakılamaz.";
    } elseif (!in_array($rol, ['admin', 'uye'])) {
        $mesaj = "Hata: Geçersiz rol seçimi.";
    } else {
        // Güvenlik: Şifreyi kaydetmeden önce HASH'le
        $hashed_sifre = password_hash($sifre, PASSWORD_DEFAULT);

        try {
            $sorgu = $db->prepare("INSERT INTO kullanicilar (kulad, sifre, rol, durum) VALUES (:kulad, :sifre, :rol, :durum)");
            
            $sorgu->bindParam(':kulad', $kulad);
            $sorgu->bindParam(':sifre', $hashed_sifre); // Hashlenmiş şifreyi kaydet
            $sorgu->bindParam(':rol', $rol);
            $sorgu->bindParam(':durum', $durum);
            
            $sorgu->execute();
            $mesaj = "Yeni kullanıcı ('" . htmlspecialchars($kulad) . "') başarıyla eklendi! 🎉";
            
            // Başarılı eklemeden sonra form alanlarını temizle
            $kulad = ''; 
            $sifre = '';

        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // 23000 = SQLSTATE code for Integrity constraint violation (Örn: kulad UNIQUE hatası)
                $mesaj = "Hata: Kullanıcı adı ('" . htmlspecialchars($kulad) . "') zaten mevcut. Lütfen başka bir ad deneyin.";
            } else {
                $mesaj = "Ekleme sırasında bir hata oluştu: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Üye Kaydı Ekleme</title>
</head>
<body>
    <h1>👤 Yeni Üye Kaydı Ekleme</h1>
    <p><a href="admin_panel.php">← Admin Paneline Dön</a></p>

    <?php 
    // İşlem sonucunu göster
    if (!empty($mesaj)) {
        echo "<p style='color: " . (strpos($mesaj, 'Hata') !== false ? 'red' : 'green') . "; border: 1px solid; padding: 10px;'>" . htmlspecialchars($mesaj) . "</p>";
    }
    ?>

    <form action="" method="POST">
        
        <label for="kulad">Kullanıcı Adı:</label><br>
        <input type="text" id="kulad" name="kulad" required 
               value="<?php echo isset($kulad) ? htmlspecialchars($kulad) : ''; ?>"><br><br>

        <label for="sifre">Şifre:</label><br>
        <input type="password" id="sifre" name="sifre" required><br><br>
        
        <label for="rol">Rol Seçimi:</label><br>
        <select id="rol" name="rol" required>
            <option value="uye">Üye</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit" name="uye_ekle">Kullanıcıyı Kaydet</button>
    </form>

</body>
</html>