-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 11 Şub 2025, 22:00:42
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `beydemhirdavat`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `kategori_adi` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `kategori_adi`, `parent_id`) VALUES
(1, 'Hırdavat Ürünleri', NULL),
(2, 'Bahçe & Peyzaj Ürünleri', NULL),
(3, 'Banyo & Tesisat Ürünleri', NULL),
(4, 'Ev & Ofis Dekorasyon Ürünleri', NULL),
(5, 'Havalandırma Ürünleri', NULL),
(6, 'Hobi Aletleri', NULL),
(7, 'İnşaat Malzemeleri', NULL),
(8, 'İş Güvenliği Ekipmanları', NULL),
(9, 'Kapı & Kilit Ürünleri', NULL),
(10, 'Aksesuarlar', 1),
(11, 'Akülü El Aletleri', 1),
(12, 'Hobi Aksesuarları', 1),
(13, 'El Aletleri', 1),
(14, 'Elektrikli Aletler', 1),
(15, 'Havalı El Aletleri', 1),
(16, 'Jeneratörler', 1),
(17, 'Kaldırma Ekipmanları', 1),
(18, 'Kaynak Makineleri & Aksesuarları', 1),
(19, 'Kompresörler', 1),
(20, 'Merdivenler & İskeleler', 1),
(21, 'Ölçüm Aletleri', 1),
(22, 'Pompalar', 1),
(23, 'Takım Çantaları & Servis Dolapları', 1),
(24, 'Diğer', 1),
(25, 'Halatlar & Halat Aksesuarlar', 1),
(26, 'Kablo Makaraları', 1),
(27, 'Oto Aksesuarları', 1),
(28, 'Paketleme Ürünleri', 1),
(29, 'Sarf Malzemeleri', 1),
(30, 'Yedek Parçalar', 1),
(31, 'Yeni', 1),
(32, 'Ağaç Kesme Motorları', 2),
(33, 'Çim Biçme Makineleri', 2),
(34, 'Çit Kenar & Dal Kesme Makineleri', 2),
(35, 'Hortumlar & Hortum Bağlantıları', 2),
(36, 'Tırmık & Çapalar', 2),
(37, 'Bahçe Aletleri', 2),
(38, 'Brandalar', 2),
(39, 'Havuz Malzemeleri', 2),
(40, 'Kamp Malzemeleri', 2),
(41, 'Su Depoları', 2),
(42, 'Zirai Malzemeler', 2),
(43, 'Aspiratörler', 5),
(44, 'Fanlar', 5),
(45, 'Isıtma Cihazları', 5),
(46, 'Menfezler', 5),
(47, 'Müdahale Kapakları', 5),
(48, 'Soğutma Cihazları', 5),
(49, 'Panjurlar', 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `resimler`
--

CREATE TABLE `resimler` (
  `resim_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `resim1` mediumblob DEFAULT NULL,
  `resim2` mediumblob DEFAULT NULL,
  `resim3` mediumblob DEFAULT NULL,
  `resim4` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

CREATE TABLE `urunler` (
  `urun_id` int(11) NOT NULL,
  `urun_adi` varchar(255) NOT NULL,
  `marka_adi` varchar(20) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`urun_id`, `urun_adi`, `marka_adi`, `kategori_id`, `eklenme_tarihi`) VALUES
(1, 'Elektrikli Matkap', '', 1, '2025-02-11 19:15:09'),
(2, 'Tornavida Seti', '', 2, '2025-02-11 19:15:09'),
(3, 'Çekiç', '', 3, '2025-02-11 19:15:09');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urun_ozellik`
--

CREATE TABLE `urun_ozellik` (
  `ozellik_id` int(11) NOT NULL,
  `urun_id` int(11) DEFAULT NULL,
  `ozellik_adi` varchar(100) DEFAULT NULL,
  `ozellik_deger` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `urun_ozellik`
--

INSERT INTO `urun_ozellik` (`ozellik_id`, `urun_id`, `ozellik_adi`, `ozellik_deger`) VALUES
(1, 1, 'Güç', '500W'),
(2, 1, 'Voltaj', '220V'),
(3, 2, 'Adet', '6 Parça'),
(4, 3, 'Ağırlık', '1.5 kg');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Tablo için indeksler `resimler`
--
ALTER TABLE `resimler`
  ADD PRIMARY KEY (`resim_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Tablo için indeksler `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`urun_id`);

--
-- Tablo için indeksler `urun_ozellik`
--
ALTER TABLE `urun_ozellik`
  ADD PRIMARY KEY (`ozellik_id`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Tablo için AUTO_INCREMENT değeri `resimler`
--
ALTER TABLE `resimler`
  MODIFY `resim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `urunler`
--
ALTER TABLE `urunler`
  MODIFY `urun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `urun_ozellik`
--
ALTER TABLE `urun_ozellik`
  MODIFY `ozellik_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `kategori`
--
ALTER TABLE `kategori`
  ADD CONSTRAINT `kategori_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `resimler`
--
ALTER TABLE `resimler`
  ADD CONSTRAINT `resimler_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `urun_ozellik`
--
ALTER TABLE `urun_ozellik`
  ADD CONSTRAINT `urun_ozellik_ibfk_1` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
