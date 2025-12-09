-- --------------------------------------------------------
-- Database: latihan_oop
-- Praktikum 11 - PHP OOP Lanjutan
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS latihan_oop;
USE latihan_oop;

-- --------------------------------------------------------
-- Table structure for table `artikel`
-- --------------------------------------------------------

CREATE TABLE `artikel` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(255) NOT NULL,
  `isi` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample Data (Optional)
-- --------------------------------------------------------

INSERT INTO `artikel` (`judul`, `isi`) VALUES
('Belajar PHP OOP', 'Artikel contoh untuk menguji CRUD.'),
('Routing PHP', 'Implementasi routing menggunakan .htaccess dan PATH_INFO.'),
('Framework Modular', 'Modularisasi aplikasi menggunakan folder module.');
