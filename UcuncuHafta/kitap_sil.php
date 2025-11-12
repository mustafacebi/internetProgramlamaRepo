// kitap_sil.php
require 'db.php';
// URL'den (GET metodu) kitap ID'sini alın
if (isset($_GET['id'])) {
    $kitap_id = $_GET['id'];

    $sorgu = $db->prepare("DELETE FROM kitaplar WHERE kitap_id = :id");
    $sorgu->bindParam(':id', $kitap_id);

    if ($sorgu->execute()) {
        echo "Kitap başarıyla silindi.";
    } else {
        echo "Silme işlemi başarısız oldu.";
    }
    // header("Location: kitap_listesi.php"); // Liste sayfasına yönlendir
}