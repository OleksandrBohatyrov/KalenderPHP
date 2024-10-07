-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 07 2024 г., 22:07
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kalender`
--

-- --------------------------------------------------------

--
-- Структура таблицы `kasutajad`
--

CREATE TABLE `kasutajad` (
  `kasutaja_id` int(11) NOT NULL,
  `kasutajanimi` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `salasona` varchar(255) NOT NULL,
  `loodud` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `meeldetuletused`
--

CREATE TABLE `meeldetuletused` (
  `meeldetuletus_id` int(11) NOT NULL,
  `sondmus_id` int(11) DEFAULT NULL,
  `meeldetuletuse_aeg` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sondmused`
--

CREATE TABLE `sondmused` (
  `sondmus_id` int(11) NOT NULL,
  `kasutaja_id` int(11) DEFAULT NULL,
  `pealkiri` varchar(255) NOT NULL,
  `kirjeldus` text NOT NULL,
  `algus_aeg` datetime NOT NULL,
  `lopp_aeg` datetime NOT NULL,
  `loodud` datetime DEFAULT current_timestamp(),
  `uuendatud` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `kasutajad`
--
ALTER TABLE `kasutajad`
  ADD PRIMARY KEY (`kasutaja_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `meeldetuletused`
--
ALTER TABLE `meeldetuletused`
  ADD PRIMARY KEY (`meeldetuletus_id`),
  ADD KEY `sondmus_id` (`sondmus_id`);

--
-- Индексы таблицы `sondmused`
--
ALTER TABLE `sondmused`
  ADD PRIMARY KEY (`sondmus_id`),
  ADD KEY `kasutaja_id` (`kasutaja_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `kasutajad`
--
ALTER TABLE `kasutajad`
  MODIFY `kasutaja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `meeldetuletused`
--
ALTER TABLE `meeldetuletused`
  MODIFY `meeldetuletus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `sondmused`
--
ALTER TABLE `sondmused`
  MODIFY `sondmus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `meeldetuletused`
--
ALTER TABLE `meeldetuletused`
  ADD CONSTRAINT `meeldetuletused_ibfk_1` FOREIGN KEY (`sondmus_id`) REFERENCES `sondmused` (`sondmus_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sondmused`
--
ALTER TABLE `sondmused`
  ADD CONSTRAINT `sondmused_ibfk_1` FOREIGN KEY (`kasutaja_id`) REFERENCES `kasutajad` (`kasutaja_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
