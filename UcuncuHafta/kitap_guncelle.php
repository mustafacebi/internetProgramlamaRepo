// kitap_guncelle.php
require 'db.php';

if (isset($_POST['kitap_guncelle'])) {
    $kitap_id = $_POST['kitap_id'];
    $ad = $_POST['ad'];
    $yazar = $_POST['yazar'];
    $stok = $_POST['stok'];

    $sorgu = $db->prepare("UPDATE kitaplar SET ad = :ad, yazar = :yazar, stok = :stok WHERE kitap_id = :id");
    
    $sorgu->bindParam(':id', $kitap_id);
    $sorgu->bindParam(':ad', $ad);
    $sorgu->bindParam(':yazar', $yazar);
    $sorgu->bindParam(':stok', $stok);

    if ($sorgu->execute()) {
        echo "Kitap başarıyla güncellendi.";
    }
    // header("Location: kitap_listesi.php");
}
// Form öncesinde, mevcut veriyi göstermek için ID ile kitabı çekmeniz gerekir.