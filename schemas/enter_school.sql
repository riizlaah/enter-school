-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Mar 2026 pada 13.30
-- Versi server: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- Versi PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enter_school`
--
CREATE DATABASE IF NOT EXISTS `enter_school` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `enter_school`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `alias_name` varchar(32) NOT NULL DEFAULT 'Anonim'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `devices`
--

INSERT INTO `devices` (`id`, `uuid`, `alias_name`) VALUES
(2, '6b8c25835f10d96bf34c844baaa48bbe', 'Anonim');

-- --------------------------------------------------------

--
-- Struktur dari tabel `phone_numbers`
--

CREATE TABLE `phone_numbers` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `phone_number` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `phone_numbers`
--

INSERT INTO `phone_numbers` (`id`, `device_id`, `phone_number`) VALUES
(1, 2, '087676875564');

-- --------------------------------------------------------

--
-- Struktur dari tabel `queues`
--

CREATE TABLE `queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `quota` int(10) UNSIGNED NOT NULL,
  `status` enum('running','stopped','completed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `queues`
--

INSERT INTO `queues` (`id`, `title`, `description`, `date`, `quota`, `status`) VALUES
(1, 'Pendaftaran SMP Okegas H1', NULL, '2026-04-09', 5, NULL),
(2, 'Pendaftaran SMP Okegas H2', NULL, '2026-04-10', 5, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_queues`
--

CREATE TABLE `user_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(16) NOT NULL,
  `phone_id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED DEFAULT NULL,
  `queue_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `called_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_queues`
--

INSERT INTO `user_queues` (`id`, `code`, `phone_id`, `device_id`, `queue_id`, `created_at`, `called_at`, `completed_at`) VALUES
(1, '4BCD-3FGH', 1, NULL, 1, '2026-03-09 10:04:18', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indeks untuk tabel `phone_numbers`
--
ALTER TABLE `phone_numbers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phoneNumber` (`phone_number`) USING BTREE,
  ADD KEY `device` (`device_id`);

--
-- Indeks untuk tabel `queues`
--
ALTER TABLE `queues`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_queues`
--
ALTER TABLE `user_queues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `queue` (`queue_id`),
  ADD KEY `phone` (`phone_id`),
  ADD KEY `device2` (`device_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `phone_numbers`
--
ALTER TABLE `phone_numbers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `queues`
--
ALTER TABLE `queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_queues`
--
ALTER TABLE `user_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `phone_numbers`
--
ALTER TABLE `phone_numbers`
  ADD CONSTRAINT `device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_queues`
--
ALTER TABLE `user_queues`
  ADD CONSTRAINT `device2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phone` FOREIGN KEY (`phone_id`) REFERENCES `phone_numbers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `queue` FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
