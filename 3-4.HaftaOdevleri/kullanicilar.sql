CREATE TABLE kullanicilar (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    kulad VARCHAR(50) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL, -- Şifreler HASHlenmiş olmalı!
    rol VARCHAR(20) NOT NULL -- 'admin' veya 'uye' gibi
);

CREATE TABLE kitaplar (
    kitap_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(255) NOT NULL,
    yazar VARCHAR(255),
    isbn VARCHAR(50) UNIQUE,
    stok INT(11) NOT NULL DEFAULT 0
);

-- Mevcut kullanicilar tablosuna yeni bir sütun ekleme
ALTER TABLE kullanicilar ADD COLUMN durum VARCHAR(20) NOT NULL DEFAULT 'aktif';

-- 'durum' sütununda 'aktif', 'pasif' (sil), veya 'engelli' değerleri tutulabilir.

-- Örnek (gerçek projede bu şekilde değil, PHP ile hashlenmiş şifreler kullanın!)
INSERT INTO kullanicilar (kulad, sifre, rol) VALUES ('adminuser', '$2y$10$XxfiUW7nm8O01jLSfnRBluPWaMU57deyhbtLA/kKn/U3//NOHn2Ry', 'admin');
INSERT INTO kullanicilar (kulad, sifre, rol) VALUES ('uyekullanici', '$2y$10$XxfiUW7nm8O01jLSfnRBluPWaMU57deyhbtLA/kKn/U3//NOHn2Ry', 'uye');