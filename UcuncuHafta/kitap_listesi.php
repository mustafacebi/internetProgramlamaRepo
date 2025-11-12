<?php
// kitap_listesi.php
session_start();
require 'db.php';

// Güvenlik Kontrolü: Sadece Admin rolüne izin ver (veya üye listeleme yetkisi olan rollere)
if (!isset($_SESSION['giris_basarili']) || $_SESSION['rol'] !== 'admin') {
    die("Bu sayfayı görüntülemeye yetkiniz yoktur.");
}

// Veritabanından tüm kitapları çek
try {
    $sorgu = $db->query("SELECT * FROM kitaplar ORDER BY kitap_id DESC");
    $kitaplar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı sorgu hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitap Listesi</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>📚 Tüm Kitaplar (Yönetim)</h1>
    <p><a href="admin_panel.php">← Admin Paneline Dön</a> | <a href="kitap_ekle.php">Yeni Kitap Ekle</a></p>

    <?php if (empty($kitaplar)): ?>
        <p style="color: red;">Veritabanında henüz kayıtlı bir kitap bulunmamaktadır.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kitap Adı</th>
                    <th>Yazar</th>
                    <th>Stok</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kitaplar as $kitap): ?>
                <tr>
                    <td><?php echo htmlspecialchars($kitap['kitap_id']); ?></td>
                    <td><?php echo htmlspecialchars($kitap['ad']); ?></td>
                    <td><?php echo htmlspecialchars($kitap['yazar']); ?></td>
                    <td><?php echo htmlspecialchars($kitap['stok']); ?></td>
                    <td>
                        <a href="kitap_guncelle.php?id=<?php echo $kitap['kitap_id']; ?>">Düzenle</a> | 
                        <a href="kitap_sil.php?id=<?php echo $kitap['kitap_id']; ?>" 
                           onclick="return confirm('Kitabı silmek istediğinizden emin misiniz?');">Sil</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>