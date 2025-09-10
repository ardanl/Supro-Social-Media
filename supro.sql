-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 10 Eyl 2025, 15:45:17
-- Sunucu sürümü: 10.4.28-MariaDB
-- PHP Sürümü: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `suprocom_supro`
--
CREATE DATABASE IF NOT EXISTS `suprocom_supro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `suprocom_supro`;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Alisveris`
--

DROP TABLE IF EXISTS `Alisveris`;
CREATE TABLE IF NOT EXISTS `Alisveris` (
  `AlisverisID` int(11) NOT NULL AUTO_INCREMENT,
  `UrunID` int(11) NOT NULL,
  `KullaniciID` int(11) NOT NULL,
  `Tarih` varchar(50) NOT NULL,
  PRIMARY KEY (`AlisverisID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `AnketCevaplari`
--

DROP TABLE IF EXISTS `AnketCevaplari`;
CREATE TABLE IF NOT EXISTS `AnketCevaplari` (
  `CevapID` int(11) NOT NULL AUTO_INCREMENT,
  `AnketID` int(11) NOT NULL,
  `CevapMetni` varchar(255) NOT NULL,
  `CevapKullanici` int(11) NOT NULL,
  PRIMARY KEY (`CevapID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Anketler`
--

DROP TABLE IF EXISTS `Anketler`;
CREATE TABLE IF NOT EXISTS `Anketler` (
  `AnketID` int(11) NOT NULL AUTO_INCREMENT,
  `AnketBaslik` varchar(255) NOT NULL,
  `AnketCevap` varchar(500) NOT NULL,
  `AnketTarihi` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`AnketID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Begeni`
--

DROP TABLE IF EXISTS `Begeni`;
CREATE TABLE IF NOT EXISTS `Begeni` (
  `BegeniID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) NOT NULL,
  `IcerikID` int(11) NOT NULL,
  `Tarih` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`BegeniID`),
  KEY `KullaniciID` (`KullaniciID`),
  KEY `IcerikID` (`IcerikID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `BildirimKategori`
--

DROP TABLE IF EXISTS `BildirimKategori`;
CREATE TABLE IF NOT EXISTS `BildirimKategori` (
  `BildirimKategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `BildirimKategoriAdi` varchar(200) NOT NULL,
  `Aktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`BildirimKategoriID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Bildirimler`
--

DROP TABLE IF EXISTS `Bildirimler`;
CREATE TABLE IF NOT EXISTS `Bildirimler` (
  `BildirimID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) DEFAULT 0,
  `AliciID` int(11) NOT NULL,
  `BildirimKategoriID` varchar(100) DEFAULT NULL,
  `BildirimIcerikID` varchar(100) NOT NULL DEFAULT '#',
  `BildirimIcerik` text DEFAULT NULL,
  `BildirimTarihi` varchar(200) DEFAULT NULL,
  `Okundu` int(1) DEFAULT 0,
  `Aktif` int(11) NOT NULL DEFAULT 1,
  `YorumCevapID` int(11) NOT NULL,
  `YorumCevaplaID` int(11) NOT NULL,
  `BildirimIcerikCevapID` int(11) NOT NULL,
  PRIMARY KEY (`BildirimID`),
  KEY `KullaniciID` (`KullaniciID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Cuzdan`
--

DROP TABLE IF EXISTS `Cuzdan`;
CREATE TABLE IF NOT EXISTS `Cuzdan` (
  `CuzdanID` int(11) NOT NULL AUTO_INCREMENT,
  `CuzdanKullaniciID` int(11) NOT NULL,
  `CuzdanNo` varchar(100) NOT NULL,
  `CuzdanTarih` varchar(50) NOT NULL,
  `CuzdanAktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`CuzdanID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Etkinlikler`
--

DROP TABLE IF EXISTS `Etkinlikler`;
CREATE TABLE IF NOT EXISTS `Etkinlikler` (
  `EtkinlikID` int(11) NOT NULL AUTO_INCREMENT,
  `EtkinlikAdi` varchar(255) NOT NULL,
  `EtkinlikTarihi` varchar(200) DEFAULT NULL,
  `EtkinlikAciklama` text DEFAULT NULL,
  `EtkinlikYer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`EtkinlikID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Guncelleme`
--

DROP TABLE IF EXISTS `Guncelleme`;
CREATE TABLE IF NOT EXISTS `Guncelleme` (
  `GuncellemeID` int(11) NOT NULL AUTO_INCREMENT,
  `GuncellemeMetin` text NOT NULL,
  `GuncellemeTarih` varchar(120) NOT NULL,
  `GuncellemeKodu` varchar(100) NOT NULL,
  `Aktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`GuncellemeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Icerikler`
--

DROP TABLE IF EXISTS `Icerikler`;
CREATE TABLE IF NOT EXISTS `Icerikler` (
  `IcerikID` int(11) NOT NULL AUTO_INCREMENT,
  `IcerikMetni` text DEFAULT NULL,
  `IcerikResim` varchar(500) NOT NULL,
  `YazarID` int(11) DEFAULT NULL,
  `YayimTarihi` varchar(200) DEFAULT NULL,
  `KategoriID` int(11) DEFAULT 22,
  `Durum` varchar(50) DEFAULT '1',
  `Yasak` int(11) NOT NULL,
  `Sabit` int(11) NOT NULL,
  PRIMARY KEY (`IcerikID`),
  KEY `KategoriID` (`KategoriID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `IcerikSikayet`
--

DROP TABLE IF EXISTS `IcerikSikayet`;
CREATE TABLE IF NOT EXISTS `IcerikSikayet` (
  `SikayetID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) DEFAULT NULL,
  `IcerikID` int(11) DEFAULT NULL,
  `SikayetBaslik` varchar(255) NOT NULL,
  `SikayetAciklama` text NOT NULL,
  `Tarih` varchar(200) DEFAULT NULL,
  `Durum` varchar(50) DEFAULT '1',
  `GeriDonus` varchar(500) DEFAULT NULL,
  `GeriDonusTarihi` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`SikayetID`),
  KEY `KullaniciID` (`KullaniciID`),
  KEY `IcerikID` (`IcerikID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Kategori`
--

DROP TABLE IF EXISTS `Kategori`;
CREATE TABLE IF NOT EXISTS `Kategori` (
  `KategoriId` int(11) NOT NULL AUTO_INCREMENT,
  `KategoriAciklama` varchar(200) NOT NULL,
  `KategoriLink` varchar(200) NOT NULL,
  PRIMARY KEY (`KategoriId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Kategoriler`
--

DROP TABLE IF EXISTS `Kategoriler`;
CREATE TABLE IF NOT EXISTS `Kategoriler` (
  `KategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `KategoriAdi` varchar(100) NOT NULL,
  `Aciklama` text DEFAULT NULL,
  `Icon` varchar(300) NOT NULL,
  `Color` varchar(50) NOT NULL,
  `Aktif` bit(1) DEFAULT b'1',
  PRIMARY KEY (`KategoriID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `KayitKod`
--

DROP TABLE IF EXISTS `KayitKod`;
CREATE TABLE IF NOT EXISTS `KayitKod` (
  `KayitKodID` int(11) NOT NULL AUTO_INCREMENT,
  `KayitKodKullaniciID` int(11) NOT NULL,
  `KayitKodNo` varchar(20) NOT NULL,
  `KayitKodTarih` varchar(200) NOT NULL,
  PRIMARY KEY (`KayitKodID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Kullanici`
--

DROP TABLE IF EXISTS `Kullanici`;
CREATE TABLE IF NOT EXISTS `Kullanici` (
  `KullaniciID` int(11) NOT NULL AUTO_INCREMENT,
  `Ad` varchar(100) NOT NULL DEFAULT 'İsimsiz',
  `Soyad` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Sifre` varchar(255) NOT NULL,
  `KullaniciAdi` varchar(50) NOT NULL,
  `DogumTarihi` varchar(200) DEFAULT NULL,
  `Cinsiyet` varchar(10) DEFAULT NULL,
  `TelefonNumarasi` varchar(15) DEFAULT NULL,
  `ProfilResmi` varchar(255) DEFAULT 'img0.png',
  `Instagram` varchar(150) NOT NULL,
  `Linkedin` varchar(100) NOT NULL,
  `Youtube` text NOT NULL,
  `Spotify` varchar(500) NOT NULL,
  `Titkok` varchar(150) NOT NULL,
  `Snapchat` varchar(150) NOT NULL,
  `Tiktok` varchar(200) NOT NULL,
  `Puan` int(11) NOT NULL DEFAULT 25,
  `Onaylandi` int(11) DEFAULT 0,
  `Color` text NOT NULL DEFAULT 'g-okyanus',
  `Sticker` text NOT NULL,
  `Banner` varchar(500) NOT NULL DEFAULT 'banner-classic.jpeg',
  `ProfilImageDesign` text NOT NULL,
  `Biyografi` mediumtext DEFAULT NULL,
  `Yetki` int(11) DEFAULT 3,
  `EpostaDogrulandi` int(11) DEFAULT 0,
  `KayitKod` varchar(20) NOT NULL,
  `Aktif` int(11) DEFAULT 1,
  `SonGiris` varchar(200) DEFAULT NULL,
  `SonGorunme` varchar(200) DEFAULT '0',
  `KayitTarihi` varchar(200) DEFAULT NULL,
  `IpAdresi` text NOT NULL,
  `CihazTur` text NOT NULL,
  `Tarayici` text NOT NULL,
  `IsletimSistemi` text NOT NULL,
  `UserAgent` text NOT NULL,
  `Silindi` int(11) DEFAULT 0,
  `IkiFaktorKimlikDogru` int(11) DEFAULT 0,
  `BasarisizGirisDenemesi` int(11) DEFAULT 0,
  `KilitlenmeSonu` varchar(200) DEFAULT NULL,
  `SosyalMedyaLinki` varchar(255) DEFAULT NULL,
  `BultenAboneligi` int(11) DEFAULT 0,
  `GuncelleyenKullaniciID` int(11) DEFAULT NULL,
  `GuncellenmeTarihi` varchar(200) DEFAULT NULL,
  `SilinmeTarihi` varchar(200) DEFAULT NULL,
  `Seri` int(11) DEFAULT NULL,
  `SeriTarih` text DEFAULT NULL,
  PRIMARY KEY (`KullaniciID`),
  UNIQUE KEY `KullaniciAdi` (`KullaniciAdi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `KullaniciLoglari`
--

DROP TABLE IF EXISTS `KullaniciLoglari`;
CREATE TABLE IF NOT EXISTS `KullaniciLoglari` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) NOT NULL,
  `Islem` text NOT NULL,
  `Tarih` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`LogID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `time` varchar(100) NOT NULL,
  `okundu` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `paytr`
--

DROP TABLE IF EXISTS `paytr`;
CREATE TABLE IF NOT EXISTS `paytr` (
  `paytrID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) NOT NULL,
  `PaketID` int(11) NOT NULL,
  `PaketAciklama` text NOT NULL,
  `PaketFiyat` varchar(10) NOT NULL,
  `Tarih` varchar(200) NOT NULL,
  PRIMARY KEY (`paytrID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Sikayet`
--

DROP TABLE IF EXISTS `Sikayet`;
CREATE TABLE IF NOT EXISTS `Sikayet` (
  `SikayetID` int(11) NOT NULL AUTO_INCREMENT,
  `SikayetYer` int(11) DEFAULT NULL,
  `KullaniciID` int(11) DEFAULT NULL,
  `KategoriID` int(11) DEFAULT NULL,
  `SikayetKim` varchar(255) NOT NULL,
  `SikayetAciklama` text NOT NULL,
  `Tarih` varchar(200) DEFAULT NULL,
  `Durum` varchar(50) DEFAULT '1',
  `GeriDonus` varchar(500) DEFAULT NULL,
  `GeriDonusTarihi` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`SikayetID`),
  KEY `KullaniciID` (`KullaniciID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `SikayetKategorileri`
--

DROP TABLE IF EXISTS `SikayetKategorileri`;
CREATE TABLE IF NOT EXISTS `SikayetKategorileri` (
  `KategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `KategoriAdi` varchar(255) NOT NULL,
  `Aciklama` text DEFAULT NULL,
  `Aktif` int(11) DEFAULT 1,
  PRIMARY KEY (`KategoriID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `SistemAyarlar`
--

DROP TABLE IF EXISTS `SistemAyarlar`;
CREATE TABLE IF NOT EXISTS `SistemAyarlar` (
  `AyarID` int(11) NOT NULL AUTO_INCREMENT,
  `AyarAdi` varchar(100) NOT NULL,
  `AyarDegeri` text DEFAULT NULL,
  `Aciklama` text DEFAULT NULL,
  PRIMARY KEY (`AyarID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Takipler`
--

DROP TABLE IF EXISTS `Takipler`;
CREATE TABLE IF NOT EXISTS `Takipler` (
  `TakipID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) NOT NULL,
  `TakipEdilenID` int(11) NOT NULL,
  `Tarih` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`TakipID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Transfer`
--

DROP TABLE IF EXISTS `Transfer`;
CREATE TABLE IF NOT EXISTS `Transfer` (
  `TransferID` int(11) NOT NULL AUTO_INCREMENT,
  `TransferAlici` text NOT NULL,
  `TransferGonderen` text NOT NULL,
  `TransferTarih` int(11) NOT NULL,
  `TransferCoin` int(11) NOT NULL,
  `TransferNo` text NOT NULL,
  PRIMARY KEY (`TransferID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `UrunKategori`
--

DROP TABLE IF EXISTS `UrunKategori`;
CREATE TABLE IF NOT EXISTS `UrunKategori` (
  `UrunKategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `UrunKategoriAciklama` text NOT NULL,
  `UrunKategoriDetay` text NOT NULL,
  `UrunYeri` int(11) NOT NULL,
  `UrunKategoriTarih` text NOT NULL,
  `Aktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`UrunKategoriID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Urunler`
--

DROP TABLE IF EXISTS `Urunler`;
CREATE TABLE IF NOT EXISTS `Urunler` (
  `UrunID` int(11) NOT NULL AUTO_INCREMENT,
  `UrunTack` int(11) NOT NULL,
  `UrunKategori` int(11) NOT NULL,
  `UrunYeri` int(11) DEFAULT NULL,
  `UrunAdi` varchar(250) NOT NULL,
  `UrunSeviye` int(11) NOT NULL DEFAULT 1,
  `UrunTarih` varchar(100) NOT NULL,
  `UrunAdet` int(11) NOT NULL,
  `UrunLink` varchar(500) NOT NULL,
  `Aktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`UrunID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `UrunSeviye`
--

DROP TABLE IF EXISTS `UrunSeviye`;
CREATE TABLE IF NOT EXISTS `UrunSeviye` (
  `UrunSeviyeID` int(11) NOT NULL AUTO_INCREMENT,
  `UrunSeviyeAdi` text NOT NULL,
  `UrunSeviyeAciklama` text NOT NULL,
  `UrunSeviyeIcon` text NOT NULL,
  `UrunSeviyeRenk` varchar(50) NOT NULL,
  `Aktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`UrunSeviyeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Yetkiler`
--

DROP TABLE IF EXISTS `Yetkiler`;
CREATE TABLE IF NOT EXISTS `Yetkiler` (
  `YetkiID` int(11) NOT NULL AUTO_INCREMENT,
  `YetkiAdi` varchar(100) NOT NULL,
  `Aciklama` text DEFAULT NULL,
  `Icon` varchar(500) NOT NULL,
  `Color` varchar(500) NOT NULL,
  `Aktif` bit(1) DEFAULT b'1',
  PRIMARY KEY (`YetkiID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `YorumCevap`
--

DROP TABLE IF EXISTS `YorumCevap`;
CREATE TABLE IF NOT EXISTS `YorumCevap` (
  `YorumCevapID` int(11) NOT NULL AUTO_INCREMENT,
  `YorumCevapKullaniciID` int(11) NOT NULL,
  `YorumCevapYorumID` int(11) NOT NULL,
  `YorumCevapTarih` varchar(100) NOT NULL,
  `YorumCevapMetin` text NOT NULL,
  PRIMARY KEY (`YorumCevapID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `YorumCevapla`
--

DROP TABLE IF EXISTS `YorumCevapla`;
CREATE TABLE IF NOT EXISTS `YorumCevapla` (
  `YorumCevaplaID` int(11) NOT NULL AUTO_INCREMENT,
  `YorumCevaplaKullaniciID` int(11) NOT NULL,
  `YorumCevaplaYorumID` int(11) NOT NULL,
  `YorumCevaplaYorum` text NOT NULL,
  `YorumCevaplaTarih` int(11) NOT NULL,
  `YorumCevaplaAktif` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`YorumCevaplaID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `Yorumlar`
--

DROP TABLE IF EXISTS `Yorumlar`;
CREATE TABLE IF NOT EXISTS `Yorumlar` (
  `YorumID` int(11) NOT NULL AUTO_INCREMENT,
  `KullaniciID` int(11) DEFAULT NULL,
  `IcerikID` int(11) DEFAULT NULL,
  `YorumIcerigi` text DEFAULT NULL,
  `YorumTarihi` varchar(200) DEFAULT NULL,
  `YorumOnay` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`YorumID`),
  KEY `KullaniciID` (`KullaniciID`),
  KEY `IcerikID` (`IcerikID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
