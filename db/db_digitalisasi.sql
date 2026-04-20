-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2025 at 12:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_digitalisasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `alats`
--

CREATE TABLE `alats` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kondisi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `merek` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_pengadaan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alats`
--

INSERT INTO `alats` (`id`, `nama`, `images`, `volume`, `kondisi`, `stok`, `merek`, `tahun_pengadaan`, `created_at`, `updated_at`) VALUES
(3, 'wdadw', '[\"alat-images\\/01K2FE2X7PKXSVCBT738CE47W8.png\"]', 'fesffs', 'Rusak Ringan', 4, '-', '-', '2025-08-06 03:36:07', '2025-10-16 05:19:03'),
(4, 'AE 86 TUreno', '[\"alat-images\\/01K2FE5X71VEK55PDKQHKEVTXB.png\"]', '250ml', 'Baik', 4, 'TOYOTA', '1985', '2025-08-07 19:20:12', '2025-10-16 03:51:12'),
(5, 'blue eyes white dragon', '[\"alat-images\\/01K2FE6FGDHYQE7D7GSNKSY1ES.png\"]', '250ml', 'Rusak Berat', 5, 'Yu-Gi-Oh!', '1996', '2025-08-07 19:23:36', '2025-08-18 06:58:00');

-- --------------------------------------------------------

--
-- Table structure for table `bahan_cairan_lamas`
--

CREATE TABLE `bahan_cairan_lamas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rumus_kimia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sisa_bahan` decimal(8,2) DEFAULT NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_cas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letak` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemilik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_pengadaan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merek` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahan_cairan_lamas`
--

INSERT INTO `bahan_cairan_lamas` (`id`, `nama`, `rumus_kimia`, `sisa_bahan`, `unit`, `nomor_cas`, `letak`, `pemilik`, `tahun_pengadaan`, `expired`, `merek`, `created_at`, `updated_at`) VALUES
(79, 'Asam Sulfat', 'H2SO4', '1250.00', 'ml', '7664-93-9', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'Asam Klorida', 'HCL', '800.00', 'ml', '7647-01-0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 'Asam Nitrat', 'HNO3', '400.00', 'ml', '7697-37-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 'Asam Asetat', 'CH3COOH', '400.00', 'ml', '64-19-7', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 'Asam Formiat', 'CH2O2', '200.00', 'ml', '64-18-6', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-12 04:18:55'),
(84, 'Ammonia', 'NH3', '100.00', 'mL', '7664-41-7', '-', '-', '2020', '2025-08-30', '-', NULL, '2025-08-07 18:16:24'),
(85, 'Ammonium Hidroksida', 'NH4OH', '400.00', 'ml', '1336-21-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 'Aseton', 'C3H6O', '250.00', 'ml', '67-64-1', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'Butanol', 'C4H10O', '500.00', 'ml', '71-36-3', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 06:35:22'),
(88, 'Benzil Alkohol', 'C7H8O', '500.00', 'ml', '100-51-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 'Benedit', '', '0.00', 'ml', '63126-89-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 'Diklorometana', 'CH2CL2', '0.00', 'ml', '200-838-9', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(91, 'Etanol', 'C2H6O', '0.00', 'ml', '64-17-5', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(92, 'Etil Asetat', 'C4H8O2', '0.00', 'ml', '141-78-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(93, 'Fehling A', 'CUH2O4S', '0.00', 'ml', '7758-98-7', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(94, 'Fehling B', 'CUS04', '0.00', 'ml', '6381-59-5', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(95, 'Formaldehid', 'CH2O', '1000.00', 'ml', '50-00-0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(96, 'Gliserol', 'C3H8O3', '1000.00', 'ml', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(97, 'Hidrogen Peroxide', 'H2O2', '500.00', 'ml', '7722-84-1', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(98, 'Iodium', 'I2', '0.00', 'ml', '7553-56-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(99, 'Kloroform', 'CHCL2', '300.00', 'ml', '67-66-3', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(100, 'Metanol', 'CH3OH', '900.00', 'ml', '67-56-1', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(101, 'Natrium hipoklorida', 'NaCIO', '1000.00', 'ml', '7681-52-9', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 'N-heksana', 'C6H14', '500.00', 'ml', '110-54-3', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(103, 'p- xilena', 'C8H10', '500.00', 'ml', '95-47-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 'spiritus', 'CH3OH', '200.00', 'ml', '', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 06:48:11');

-- --------------------------------------------------------

--
-- Table structure for table `bahan_padats`
--

CREATE TABLE `bahan_padats` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rumus_kimia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sisa_bahan` decimal(8,2) DEFAULT NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_cas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letak` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemilik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_pengadaan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merek` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahan_padats`
--

INSERT INTO `bahan_padats` (`id`, `nama`, `rumus_kimia`, `sisa_bahan`, `unit`, `nomor_cas`, `letak`, `pemilik`, `tahun_pengadaan`, `expired`, `merek`, `created_at`, `updated_at`) VALUES
(165, 'Aluminium Klorida', 'AlCl3', '980.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(166, 'Amonium', 'NH3', '500.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(167, 'Amonium Asetat', 'C6H10O5', '250.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(168, 'Amonium Karbonat', '(NH4)2CO3', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(169, 'Amonium Klorida', 'NH4Cl', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(170, 'Amonium Nitrat', 'NH4NO3', '500.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(171, 'Ammonium Oksalat', '(NH4)2C2O4', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(172, 'Asam Benzoat', 'C6H5COOH', '450.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(173, 'Asam Oksalat', 'C2H2O4', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(174, 'Asam salisilat', 'C7H6O3', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(175, 'Asam Sitrat', 'C6H8O7', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(176, 'Barium Hidroksida', 'Ba(OH)2', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(177, 'Barium Klorida', 'BaCl2', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(178, 'Barium Nitrat', 'Ba(NO3)2', '300.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(179, 'Barium Sulfat', 'BaSO4', '500.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(180, 'Bentonit', 'Al2O3.4SiO2.H2O', '4000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(181, 'Besi (II) Klorida', 'FeCl2', '230.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(182, 'Besi (III) Klorida', 'FeCl3', '1400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(183, 'Besi (III) Nitrat', 'Fe(NO3)3', '230.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(184, 'Bismut (III) Nitrat', 'Bi (NO3)2', '8.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(185, 'Dinatrium Hydrogen Fosfat', 'Na2HPO4', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(186, 'Eriochrome Black T', 'C20H12N3O7SNa', '2.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(187, 'Ferro Ammonium Sulfat', '(NH4)2Fe(SO4)2', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(188, 'Glukosa', 'C6H12O6', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(189, 'Indikator PP', 'C20H14O4', '150.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(190, 'Iodium/Iodine', 'I2', '75.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(191, 'Kalium Bromat', 'K2SO4', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(192, 'Kalium Bromida', 'KBr', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(193, 'Kalium Dikromat', 'K2Cr2O7', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(194, 'Kalium Hidroksida', 'KOH', '20.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(195, 'Kalium Iodida', 'KI', '700.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(196, 'Kalium Heksasianoferat', 'K3Fe(CN)6', '25.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(197, 'Kalium Klorida', 'KCl', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(198, 'Kalium Kromat', 'K2CrO4', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(199, 'Kalium Natrium Tartarat/garam rochelle', 'KNaC4H4O6·4H2O', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(200, 'Kalium Permanganat', 'KMnO4', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(201, 'Kalium Tiosianat', 'KSCN', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(202, 'Kalsium karbonat', 'CaCO3', '250.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(203, 'Kalsium Klorida Anhidrat', 'CaCl2', '80.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(204, 'Kalsium Nitrat', 'Ca(NO3)2', '80.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(205, 'Kapur', 'CaO', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(206, 'kobalt (II) Nitrat', 'Co(NO3)2', '10.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(207, 'Kobalt (II) klorida', 'CoCl2', '30.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(208, 'Magnesium serbuk', 'Mg', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(209, 'Magnesium Nitrat', 'Mg(NO3)2', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(210, 'Magnesium Sulfat', 'MgSO4', '10.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(211, 'Mangan diKlorida', 'MnCl2', '10.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(212, 'Mangan Oksida', 'MnO2', '5.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(213, 'Mercury (II) Klorida', 'HgCl2', '30.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(214, 'Natrium Asetat', 'CH3COONa', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:40:20', '2025-08-16 16:40:20'),
(215, 'Aluminium Klorida', 'AlCl3', '980.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(216, 'Amonium', 'NH3', '500.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(217, 'Amonium Asetat', 'C6H10O5', '250.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(218, 'Amonium Karbonat', '(NH4)2CO3', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(219, 'Amonium Klorida', 'NH4Cl', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(220, 'Amonium Nitrat', 'NH4NO3', '500.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(221, 'Ammonium Oksalat', '(NH4)2C2O4', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(222, 'Arang Aktif', 'C', '860.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(223, 'Asam Benzoat', 'C6H5COOH', '450.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(224, 'Asam Oksalat', 'C2H2O4', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(225, 'Asam salisilat', 'C7H6O3', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(226, 'Asam Sitrat', 'C6H8O7', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(227, 'Barium Hidroksida', 'Ba(OH)2', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(228, 'Barium Klorida', 'BaCl2', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(229, 'Barium Nitrat', 'Ba(NO3)2', '300.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(230, 'Barium Sulfat', 'BaSO4', '500.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(231, 'Bentonit', 'Al2O3.4SiO2.H2O', '4000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(232, 'Besi (II) Klorida', 'FeCl2', '230.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(233, 'Besi (III) Klorida', 'FeCl3', '1400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(234, 'Besi (III) Nitrat', 'Fe(NO3)3', '230.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(235, 'Bismut (III) Nitrat', 'Bi (NO3)2', '8.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(236, 'Dinatrium Hydrogen Fosfat', 'Na2HPO4', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(237, 'Eriochrome Black T', 'C20H12N3O7SNa', '2.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(238, 'Ferro Ammonium Sulfat', '(NH4)2Fe(SO4)2', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(239, 'Glukosa', 'C6H12O6', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(240, 'Indikator PP', 'C20H14O4', '150.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(241, 'Iodium/Iodine', 'I2', '75.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(242, 'Kalium Bromat', 'K2SO4', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(243, 'Kalium Bromida', 'KBr', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(244, 'Kalium Dikromat', 'K2Cr2O7', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(245, 'Kalium Hidroksida', 'KOH', '20.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(246, 'Kalium Iodida', 'KI', '700.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(247, 'Kalium Heksasianoferat', 'K3Fe(CN)6', '25.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(248, 'Kalium Klorida', 'KCl', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(249, 'Kalium Kromat', 'K2CrO4', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(250, 'Kalium Natrium Tartarat/garam rochelle', 'KNaC4H4O6·4H2O', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(251, 'Kalium Permanganat', 'KMnO4', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(252, 'Kalium Tiosianat', 'KSCN', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(253, 'Kalsium karbonat', 'CaCO3', '250.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(254, 'Kalsium Klorida Anhidrat', 'CaCl2', '80.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(255, 'Kalsium Nitrat', 'Ca(NO3)2', '80.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(256, 'Kapur', 'CaO', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(257, 'kobalt (II) Nitrat', 'Co(NO3)2', '10.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(258, 'Kobalt (II) klorida', 'CoCl2', '30.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(259, 'Magnesium serbuk', 'Mg', '400.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(260, 'Magnesium Nitrat', 'Mg(NO3)2', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(261, 'Magnesium Sulfat', 'MgSO4', '10.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(262, 'Mangan diKlorida', 'MnCl2', '10.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(263, 'Mangan Oksida', 'MnO2', '5.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(264, 'Mercury (II) Klorida', 'HgCl2', '30.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(265, 'Natrium Asetat', 'CH3COONa', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(266, 'Natrium bikarbonat', 'NaHCO3', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(267, 'Natrium Hidroksida', 'NaOH', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(268, 'Natrium Karbonat', 'Na2CO3', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(269, 'Natrium Klorida', 'NaCl', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(270, 'Natrium Nitrat', 'NaNO3', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(271, 'Natrium Nitrit', 'NaNO2', '300.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(272, 'Natrium Oksalat', 'Na2C2O4', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(273, 'Natrium Sulfat', 'Na2SO4', '850.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(274, 'Natrium Sulfida', 'Na2S', '230.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(275, 'Natrium Thiosulfat', 'Na2S2O3', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(276, 'Nikel (II) Sulfat Heksahidrat', 'NiSO4. 6H2O', '20.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(277, 'Nutrient Agar', '', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(278, 'Nutrient Broth', '', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(279, 'Peptone Water', '', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(280, 'Resorsinol', 'C6H4(OH)2', '25.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(281, 'serbuk Alumunium', 'Al', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(282, 'Silver Nitrat (perak Nitrat)', 'AgNO3', '0.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(283, 'Spirosa/ Kalium Dihidrogen Phosphat', 'NaH2PO4', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(284, 'Sukrosa', 'C12H22O11', '1000.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(285, 'Tembaga (II) Oksida', 'CuO', '70.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(286, 'Tembaga (II) sulfat', 'CuSO4', '425.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(287, 'Tembaga Nitrat', 'Cu(NO3)2', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(288, 'Tembaga serbuk', 'Cu', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(289, 'Timah Klorida', 'SnCl4', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(290, 'Timbal (II) Asetat', 'Pb(CH3COO)2', '100.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(291, 'Timbal (II) Nitrat', 'Pb(NO3)2', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(292, 'Titriplex (EDTA)', '10H14N2Na2O8.2H2O', '25.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(293, 'Zink Nitrat', 'Zn(NO3)2', '50.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(294, 'Zink serbuk', 'Zn', '125.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49'),
(295, 'Zink Sulfat', 'ZSO4', '200.00', 'g', NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-16 16:41:49', '2025-08-16 16:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1760611904),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1760611904;', 1760611904),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1755271932),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1755271932;', 1755271932),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1760617190),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1760617190;', 1760617190),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1760616088),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1760616088;', 1760616088);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_26_012157_create_bahan_cairan_lamas_table', 1),
(5, '2025_06_01_111551_create_bahan_padats_table', 1),
(6, '2025_06_11_020345_create_alats_table', 1),
(7, '2025_07_01_072816_add_images_column_to_alats_table', 1),
(8, '2025_07_08_160049_create_peminjamen_table', 1),
(9, '2025_07_13_124325_add_stok_column_to_alats_table', 1),
(10, '2025_07_13_132452_add_unit_column_to_bahan_padats_table', 1),
(11, '2025_07_13_140554_add_unit_column_to_bahan_cairs_table', 1),
(13, '2025_07_13_161439_reorder_columns_in_bahan_padats_table', 2),
(14, '2025_07_13_161612_reorder_columns_in_bahan_cairan_lamas_table', 2),
(15, '2025_07_14_054714_add_no_hp_to_peminjamans_table', 2),
(16, '2025_07_14_082546_remove_jumlah_column_from_alats_table', 2),
(17, '2025_08_12_143029_create_surat_bebas_labs_table', 3),
(18, '2025_10_16_113005_add_google_id_to_users_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjamans`
--

CREATE TABLE `peminjamans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_peminjam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim_peminjam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peminjamable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `peminjamable_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Menunggu Persetujuan','Disetujui','Ditolak','Dikembalikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Persetujuan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- Dumping data for table `peminjamans`
--

INSERT INTO `peminjamans` (`id`, `nama_peminjam`, `nim_peminjam`, `no_hp`, `peminjamable_type`, `peminjamable_id`, `jumlah`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `created_at`, `updated_at`) VALUES
(38, 'egi disa habibie', '12312321', '+6285371808389', 'App\\Models\\Alat', 3, 2, NULL, NULL, 'Ditolak', '2025-10-16 03:18:50', '2025-10-16 03:26:27'),
(39, 'egi disa habibie', '22078032', '+628123456789', 'App\\Models\\Alat', 4, 2, '2025-10-16', '2025-10-16', 'Dikembalikan', '2025-10-16 03:27:15', '2025-10-16 03:27:47'),
(40, 'egi disa habibie', '1235415', '+628921231021', 'App\\Models\\Alat', 4, 1, '2025-10-16', '2025-10-16', 'Dikembalikan', '2025-10-16 03:50:44', '2025-10-16 03:51:12'),
(41, 'egiawdas', '213123', '+62812345567', 'App\\Models\\Alat', 3, 1, NULL, NULL, 'Ditolak', '2025-10-16 05:00:01', '2025-10-16 05:00:50'),
(42, 'egi disa habibie', '22078032', '+628921231021', 'App\\Models\\Alat', 3, 1, NULL, NULL, 'Ditolak', '2025-10-16 05:18:50', '2025-10-16 05:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('S1PDrzwqgr8sNOJBW3FaTl849GEUf0Z1MRAMq2NS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieWxCVDlCY0liOEQyWERsTHVnc3FHTmdFRlI3QktPbjU2Y05qT3Q5bSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2dvb2dsZS9yZWRpcmVjdCI7fXM6NToic3RhdGUiO3M6NDA6InAwaXRYZUxzVjdMcWE3YU8zS1ZDUDdXTkh0U2MwZkpzZ0tDVWk3UG0iO30=', 1760617200),
('yTvR1FtuenXILCjqAg416VZy1YqfmQaTbTV6N5Ce', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUTdBSEtDUW5tZGFVSTZZWklJSG16RmJwOUlibElXUnc5M25iTDdKNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1760617029);

-- --------------------------------------------------------

--
-- Table structure for table `surat_bebas_labs`
--

CREATE TABLE `surat_bebas_labs` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_peminjam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

 

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alats`
--
ALTER TABLE `alats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bahan_cairan_lamas`
--
ALTER TABLE `bahan_cairan_lamas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bahan_padats`
--
ALTER TABLE `bahan_padats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjamans_peminjamable_type_peminjamable_id_index` (`peminjamable_type`,`peminjamable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `surat_bebas_labs`
--
ALTER TABLE `surat_bebas_labs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alats`
--
ALTER TABLE `alats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bahan_cairan_lamas`
--
ALTER TABLE `bahan_cairan_lamas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `bahan_padats`
--
ALTER TABLE `bahan_padats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `peminjamans`
--
ALTER TABLE `peminjamans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `surat_bebas_labs`
--
ALTER TABLE `surat_bebas_labs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
