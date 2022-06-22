-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql204.epizy.com
-- Generation Time: Jun 22, 2022 at 11:26 AM
-- Server version: 10.3.27-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epiz_32006416_sifregiris`
--

-- --------------------------------------------------------

--
-- Table structure for table `satin_alinan_urunler`
--

CREATE TABLE `satin_alinan_urunler` (
  `id` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `alinan_adet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `satin_alinan_urunler`
--

INSERT INTO `satin_alinan_urunler` (`id`, `email`, `urun_id`, `alinan_adet`) VALUES
(18, 't@t.com', 25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `urunler`
--

CREATE TABLE `urunler` (
  `urun_id` int(11) NOT NULL,
  `urun_adi` varchar(45) NOT NULL,
  `urun_fiyati` int(11) NOT NULL,
  `urun_description` varchar(100) NOT NULL,
  `urun_adeti` int(11) NOT NULL,
  `satan_email` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `urunler`
--

INSERT INTO `urunler` (`urun_id`, `urun_adi`, `urun_fiyati`, `urun_description`, `urun_adeti`, `satan_email`) VALUES
(25, 'AcÄ±lÄ±m Pul Biber', 33, '50 gr paketler halinde satÄ±maktadÄ±r. Dikkat edin Ã§ok acÄ±dÄ±r', 10, 'yusuf_2001@gmail.com'),
(26, 'NatureLive Masaj YaÄŸÄ± ', 54, 'Cildinizi ipeksi hale getirir. Bir kutu halinde satÄ±lÄ±r. DoÄŸanÄ±n mucizesi', 3, 'faruk@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(45) NOT NULL,
  `user_surname` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `bakiye` int(11) NOT NULL DEFAULT 0,
  `kayitTarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `user_surname`, `email`, `user_password`, `bakiye`, `kayitTarihi`) VALUES
('Erdal', 'Nayir', 'erdal@gmail.com', 'b1ab1e892617f210425f658cf1d361b5489028c8771b56d845fe1c62c1fbc8b0', 1920, '2022-06-19 13:12:23'),
('faruk', 'bilgin', 'faruk@gmail.com', '85d6385b945c0d602103db39b0b654b2af93b5127938e26a959c123f0789b948', 0, '2022-06-22 07:29:18'),
('Harun', 'Çiftçi', 'harun_72@gmail.com', '9a3b84136cae8f86009596df91507ee5f5c54708cf7b79e374c17c3eca2e519f', 7140, '2022-06-17 23:13:26'),
('Mustafa', 'KÃ¶ksal', 'mustafa_kok@gmail.com', 'f61494b991bf314593bce59459efdce63b2f9d924a2155fa3b62c2c41c9c10c8', 100, '2022-06-21 11:47:07'),
('qwe', 'asd', 'q@t.com', '4e07408562bedb8b60ce05c1decfe3ad16b72230967de01f640b7e4729b49fce', 0, '2022-06-22 07:59:47'),
('Yakup', 'A', 't@t.com', 'ef2d127de37b942baad06145e54b0c619a1f22327b2ebbcfbec78f5564afe39d', 34, '2022-06-21 14:45:24'),
('Taha', 'Ayzit', 'taha_21@gmail.com', 'b8dc2c143be8994682b08461f46487e05874e59dd9ab65cf973e3a3c67a763aa', 200, '2022-06-17 23:13:26'),
('Yakup', 'Abacı', 'yakup_2001@gmail.com', '0b7d75c068ed5165be960279e7dacd2e900d50cbae19b654d02efc5b0fa68ee8', 169, '2022-06-18 19:11:05'),
('Yusuf', 'hakim', 'yusuf_2001@gmail.com', '85d6385b945c0d602103db39b0b654b2af93b5127938e26a959c123f0789b948', 66, '2022-06-21 13:30:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `satin_alinan_urunler`
--
ALTER TABLE `satin_alinan_urunler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `satin_alinan_urunler_ibfk_1` (`email`),
  ADD KEY `urun_id` (`urun_id`);

--
-- Indexes for table `urunler`
--
ALTER TABLE `urunler`
  ADD PRIMARY KEY (`urun_id`),
  ADD KEY `urunler_ibfk_1` (`satan_email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `satin_alinan_urunler`
--
ALTER TABLE `satin_alinan_urunler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `urunler`
--
ALTER TABLE `urunler`
  MODIFY `urun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `satin_alinan_urunler`
--
ALTER TABLE `satin_alinan_urunler`
  ADD CONSTRAINT `satin_alinan_urunler_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `satin_alinan_urunler_ibfk_2` FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`urun_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `urunler`
--
ALTER TABLE `urunler`
  ADD CONSTRAINT `urunler_ibfk_1` FOREIGN KEY (`satan_email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
