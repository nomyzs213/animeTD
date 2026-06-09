-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Чрв 09 2026 р., 23:29
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `clicker`
--

-- --------------------------------------------------------

--
-- Структура таблиці `anti_autoclicker`
--

CREATE TABLE `anti_autoclicker` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `warning_count` int(11) NOT NULL DEFAULT 0,
  `ban_stage` int(11) NOT NULL DEFAULT 0,
  `ban_expires_at` datetime DEFAULT NULL,
  `last_warning_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `anti_autoclicker`
--

INSERT INTO `anti_autoclicker` (`id`, `user_id`, `warning_count`, `ban_stage`, `ban_expires_at`, `last_warning_at`) VALUES
(3, 14, 0, 1, NULL, NULL),
(4, 11, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблиці `email_verification_tokens`
--

CREATE TABLE `email_verification_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `highscores`
--

CREATE TABLE `highscores` (
  `score_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `yens` decimal(24,2) NOT NULL,
  `upgrade_level_1` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_2` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_3` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_4` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_5` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_6` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_7` int(11) NOT NULL DEFAULT 0,
  `upgrade_level_8` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `highscores`
--

INSERT INTO `highscores` (`score_id`, `user_id`, `yens`, `upgrade_level_1`, `upgrade_level_2`, `upgrade_level_3`, `upgrade_level_4`, `upgrade_level_5`, `upgrade_level_6`, `upgrade_level_7`, `upgrade_level_8`) VALUES
(6, 11, 1630.80, 1, 1, 0, 0, 0, 0, 0, 0),
(7, 14, 209397.60, 6, 3, 0, 4, 0, 2, 3, 3);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(160) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `verified`) VALUES
(11, 'Kolob', 'ukhman.yuriy@gmail.com', '$2y$10$VGV8zUPf2vn2Y3lIwzcVS.5dfnf8YlAnVCM6Qf2fhZODbdinG2Aq2', 1),
(12, 'piotrn', 'piotrnzsti@gmail.com', '$2y$10$oiW.aTD.v5oLxa5tVfZ8A.KeABhizoghYqbgghYkjBAGZ3nEFCKAS', 1),
(13, 'nomyzs213', 'zarembaszymon377@gmail.com', '$2y$10$Eay/PSRFExaGoHxPfPGC4OULzFvO1zxEL4cuFEGel9Ue45y.egwSC', 1),
(14, 'test', 'yuriyuhman@gmail.com', '$2y$10$XguBVi4Q22jJ0Vr1/vuzDu6W5935ftIl1VOAl7I..T3HxvUgg5/NW', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `user_images`
--

CREATE TABLE `user_images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `imagePath` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `user_images`
--

INSERT INTO `user_images` (`id`, `user_id`, `imagePath`) VALUES
(1, 11, 'click.png'),
(2, 14, 'click.png');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `anti_autoclicker`
--
ALTER TABLE `anti_autoclicker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Індекси таблиці `email_verification_tokens`
--
ALTER TABLE `email_verification_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `highscores`
--
ALTER TABLE `highscores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Індекси таблиці `user_images`
--
ALTER TABLE `user_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `anti_autoclicker`
--
ALTER TABLE `anti_autoclicker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблиці `email_verification_tokens`
--
ALTER TABLE `email_verification_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблиці `highscores`
--
ALTER TABLE `highscores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблиці `user_images`
--
ALTER TABLE `user_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `anti_autoclicker`
--
ALTER TABLE `anti_autoclicker`
  ADD CONSTRAINT `anti_autoclicker_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `email_verification_tokens`
--
ALTER TABLE `email_verification_tokens`
  ADD CONSTRAINT `email_verification_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `highscores`
--
ALTER TABLE `highscores`
  ADD CONSTRAINT `highscores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `user_images`
--
ALTER TABLE `user_images`
  ADD CONSTRAINT `user_images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
