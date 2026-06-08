-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Чрв 08 2026 р., 12:45
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
-- База даних: `yurii_animeclicker`
--

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
(4, 9, 175.10, 3, 1, 0, 0, 0, 0, 0, 0),
(5, 10, 369.30, 2, 1, 0, 0, 0, 0, 0, 0);

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
(9, 'konto', 'yuriyuhman@gmail.com', '$2y$10$FmxE.uTgzDEtQAqbdHooj.eYhucFBjrOtR/UigSPgtDH63XwUZSLe', 1),
(10, 'konto1', 'ukhman.yuriy@gmail.com', '$2y$10$l26Eh1ck7NPAb4tVvDGOBerxgCj/AAdVHiUZuKo3FH742q1ZjZtRu', 1);

--
-- Індекси збережених таблиць
--

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
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `email_verification_tokens`
--
ALTER TABLE `email_verification_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблиці `highscores`
--
ALTER TABLE `highscores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
