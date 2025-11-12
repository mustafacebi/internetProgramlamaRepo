<?php
// uye_listesi.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu sayfayı görüntülemeye yetkiniz yoktur. (Admin girişi gereklidir)");
}

// Veritabanından tüm kullanıcıları çek
try {
    // Sadece admin yetkisi ile ID'yi Session'a kaydettiyseniz, buradaki ID'yi kullanmalısınız.
    $mevcut_admin_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; 
    
    // Güvenlik amaçlı kendinizi listede göstermeyebilirsiniz (veya işlem linklerini kaldırabilirsiniz)
    $sorgu = $db->query("SELECT id, kulad, rol, durum FROM kullanicilar ORDER BY id ASC");
    $uyeler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı sorgu hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Üye Durumu Yönetimi</title>
    <style>
        .aktif { color: green; font-weight: bold; }
        .pasif { color: orange; }
        .engelli { color: red; font-weight: bold; }
    </style>
</head>
<body>
    
    <h1>👤 Üye Durumu Yönetimi (Pasif/Engelle/Sil)</h1>
    <p><a href="admin_panel.php">← Admin Paneline Dön</a> | <a href="uye_ekle_formu.php">➕ Yeni Üye Ekle</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>Rol</th>
                <th>Mevcut Durum</th>
                <th>İşlemler (Durum Değiştirme)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($uyeler as $uye): ?>
            <tr>
                <td><?php echo htmlspecialchars($uye['id']); ?></td>
                <td><?php echo htmlspecialchars($uye['kulad']); ?></td>
                <td><?php echo htmlspecialchars($uye['rol']); ?></td>
                <td class="<?php echo htmlspecialchars($uye['durum']); ?>">
                    <?php echo htmlspecialchars(ucfirst($uye['durum'])); ?>
                </td>
                <td>
                    <?php 
                    // Yöneticinin kendi hesabını değiştirmesini engelle (isteğe bağlı)
                    $disable_self = ($uye['kulad'] === $_SESSION['kulad']) ? 'disabled' : ''; 
                    ?>

                    <?php if ($uye['durum'] !== 'aktif'): ?>
                        <a href="uye_durum_guncelle.php?id=<?php echo $uye['id']; ?>&durum=aktif" class="<?= $disable_self ?>">Aktif Yap</a> |
                    <?php endif; ?>
                    
                    <?php if ($uye['durum'] !== 'pasif'): ?>
                        <a href="uye_durum_guncelle.php?id=<?php echo $uye['id']; ?>&durum=pasif" class="<?= $disable_self ?>">Pasif Yap</a> |
                    <?php endif; ?>
                    
                    <?php if ($uye['durum'] !== 'engelli'): ?>
                        <a href="uye_durum_guncelle.php?id=<?php echo $uye['id']; ?>&durum=engelli" class="<?= $disable_self ?>" style="color: red;">Engelle</a> |
                    <?php endif; ?>
                    
                    <a href="uye_durum_guncelle.php?id=<?php echo $uye['id']; ?>&durum=sil" 
                       onclick="return confirm('UYARI: Kullanıcıyı veritabanından TAMAMEN silmek istediğinizden emin misiniz?');"
                       style="color: darkred;" class="<?= $disable_self ?>">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>