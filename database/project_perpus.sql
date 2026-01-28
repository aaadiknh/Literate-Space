-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Okt 2024 pada 16.45
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_perpus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(4, 'admin', '$2y$10$mRtjhSUS1mAzpY7sv9o3W.oxBvHi5S04YQg3ZA1iKQ//mRmv/4Rfa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `published_year` year(4) NOT NULL,
  `shelf_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `published_year`, `shelf_id`) VALUES
(21, 'Laskar Pelangi', 'Andrea Hirata', '2005', 1),
(22, 'Joko Kendil', 'Bening', '2010', 3),
(23, 'Kamus Umum Bahasa Indonesia', 'W.J.S Poerwadarminta', '1953', 5),
(24, 'Honesty', 'Park Kim Xiong', '2015', 7),
(25, 'Ekonomi Untuk SMA dan MA Kelas X', 'Ismawanto', '2005', 4),
(27, 'Bawang Merah Bawang Putih cerita rakyat', 'Tria Ayu K', '2017', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `book_shelf`
--

CREATE TABLE `book_shelf` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `book_shelf`
--

INSERT INTO `book_shelf` (`id`, `location`, `name`) VALUES
(1, '1', 'novel'),
(3, '2', 'cerita rakyat'),
(4, '3', 'mata pelajaran'),
(5, '4', 'Kamus'),
(7, '5', 'Komik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrowed_at` date NOT NULL,
  `returned_at` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `user_id`, `book_id`, `borrowed_at`, `returned_at`, `location`, `author`) VALUES
(4, 8, 2, '2024-10-09', '2024-10-17', 'novel', 'Andrea Hirata'),
(6, 10, 13, '2024-10-09', '2024-10-16', 'Komik', ''),
(8, 8, 2, '2024-10-09', '2024-10-10', 'novel', 'Andrea Hirata'),
(10, 10, 2, '2024-10-10', '2024-10-17', 'novel', ''),
(11, 29, 15, '2024-10-09', '2024-10-16', 'mata pelajaran', ''),
(12, 10, 27, '2024-10-17', '2024-10-24', 'cerita rakyat', 'Bening'),
(13, 29, 23, '2024-10-25', '2024-11-01', 'Kamus', 'W.J.S Poerwadarminta'),
(15, 10, 21, '2024-10-18', '2024-10-10', 'novel', 'Andrea Hirata');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(8, 'seli1', '$2y$10$GjNXPglLjPvV7B1YLwR79ujd7OOJvGfMXYSCtC.AZ9is8/Yyi72ia', 'seli@gmail.com', 'member', '2024-10-07 15:31:39'),
(10, 'risna', '$2y$10$CF2FgAF2xaYwdjSJ51oo5uHH6zcpnCqGukEM3Er7pFR2zSdVXE2ke', 'risna@gmail.com', 'member', '2024-10-07 15:46:10'),
(29, 'ali', '$2y$10$y.mCExn7kRw5JVe1Fu7MiuAHECdR/pt3FfgioTaix5g6nCEpCVY5q', 'ali@gmail.com', 'member', '2024-10-09 14:22:35'),
(30, 'ara', '$2y$10$ifu3XOA//ikOHEzUl6n0puKJYsQKqG5dfxZzb6nTg3fRtALqYAPiu', 'ara@gmail', 'member', '2024-10-09 14:25:11'),
(31, 'erna', '$2y$10$55Y5dImkGzGsl3bAqetHueI/Z1Z1cwLi00nUIIAoZZ/IbETiaDIPe', 'erna@gmail.com', 'member', '2024-10-09 14:41:20');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shelf_id` (`shelf_id`);

--
-- Indeks untuk tabel `book_shelf`
--
ALTER TABLE `book_shelf`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `book_shelf`
--
ALTER TABLE `book_shelf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`shelf_id`) REFERENCES `book_shelf` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
