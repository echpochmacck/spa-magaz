-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 21 2024 г., 23:49
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `spa-magaz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `basket`
--

CREATE TABLE `basket` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `basket`
--

INSERT INTO `basket` (`id`, `user_id`) VALUES
(2, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `basketSostav`
--

CREATE TABLE `basketSostav` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `basket_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sum` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `order_date`, `sum`) VALUES
(15, 7, 'выаываы', '2024-09-23 18:10:53', 12),
(16, 7, 'В ожидании', '2024-10-07 19:13:38', 119.88),
(17, 7, 'В ожидании', '2024-10-07 19:13:55', 119.88),
(18, 7, 'В ожидании', '2024-10-07 19:14:11', 119.88),
(19, 7, 'В ожидании', '2024-10-07 19:15:21', 119.88),
(20, 7, 'В ожидании', '2024-10-21 13:14:03', 1466.52),
(21, 7, 'В ожидании', '2024-10-21 13:15:52', 2786.52),
(22, 7, 'В ожидании', '2024-10-21 13:16:24', 3182.52);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`) VALUES
(7, '3232', 'вывы', 132),
(8, 'выфвфыв', 'вфв', 13.32),
(9, 'кружка', 'вфв', 13.32);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int UNSIGNED NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'avtor'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `sostav`
--

CREATE TABLE `sostav` (
  `id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `sostav`
--

INSERT INTO `sostav` (`id`, `product_id`, `order_id`, `quantity`) VALUES
(20, 7, 15, 12),
(21, 8, 18, 1),
(22, 9, 18, 8),
(23, 8, 19, 1),
(24, 9, 19, 8),
(25, 7, 20, 10),
(26, 8, 20, 3),
(27, 9, 20, 8),
(28, 7, 21, 20),
(29, 8, 21, 3),
(30, 9, 21, 8),
(31, 7, 22, 23),
(32, 8, 22, 3),
(33, 9, 22, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `patronymic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `cash` float NOT NULL,
  `register_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `patronymic`, `login`, `email`, `password`, `token`, `role_id`, `cash`, `register_at`) VALUES
(7, 'sddasd', 'adsad', NULL, 'max', 'asdadsd@ds.com', '$2y$13$h.wNce8hUp740cHrLIWQSOAFzoZAm.evW.MqBDJfdwyKGhM5XfW.6', 'F9TZqYS81aSPLGFtP6eePsx0jWMxNMKO', 1, 994032, '2024-09-22'),
(9, 'sddasd', 'adsaddsd', NULL, 'admin', 'asdadsd@ds.comsdsd', '$2y$13$h.wNce8hUp740cHrLIWQSOAFzoZAm.evW.MqBDJfdwyKGhM5XfW.6', 'DQE-m6Ys0KEutZRtL3dZw42G9F5DRiXO1', 2, 1520.48, '2024-09-22'),
(12, 'ывавыа', 'авыаыва', NULL, 'teste2', 'dsa@sadd.com', '$2y$13$i3WSsTN2P6hrnMgK6oAu5uUeT2g8Jo4hnJXxMDEN0VFShZ0Ryh8Jy', 'vCvRpdLfZ-mo-9BaAHfLNwsQVgdBRayw', 1, 2000, '2024-10-11'),
(13, 'dasdsd', 'adsad', NULL, 'test112', 'dsa@dsad.com', '$2y$13$JFFMV9g79lFc1i4Ni4tUsO1JQItze.X/QYKOT/6fFzJtRwqMVvscG', 'icKfOtU_40k77trjJZDsClITD0rX9lx2', 1, 2000, '2024-10-11'),
(14, 'gdsfd', 'fdsfdsf', NULL, 'asdasd', 'dsa@dsads.com', '$2y$13$FUs5fMNkoOVk.414qrAE5eLVC.926slQ3hDtF06ZdcljVqpcFZBVC', 'YavTYwPPECExWT8Fd8gw-oESZkiebATn', 1, 2000, '2024-10-11'),
(15, 'dasdasd', 'asdasd', NULL, 'dadsadsd', 'dsaDSas@asdasd.com', '$2y$13$G2mzQ2x06KNpSDoXlejKq.NuF7K0aEcNu1wYMyD3rtHLrcGYs152m', 't3ShyYJh7nLColagGa8HFeiISmVvUri2', 1, 2000, '2024-10-11'),
(16, 'adsad', 'dasdas', NULL, 'sdasd', 'dsa2dDSASD@dsad.com', '$2y$13$9nSe9hKuCRJAw/byxxDyle0RA5zu7dFNSIDQK.WHkBKUbJqGeuzTO', 'kmUTTidZNldOsxFrPxYKkHdvmyO3M7kT', 1, 2000, '2024-10-11'),
(17, 'dasd', 'sadas', NULL, 'maxdsadasd', 'dsa@dasd.com', '$2y$13$Vceod2ulQLcuAZSGoIwAiu9jVOOeLryeEJZLGu7AlixZU7lF8ThS2', 'VfkcDHFpJ5cVBnE0fN_axsIFNrx0wIGW', 1, 2000, '2024-10-11');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `basketSostav`
--
ALTER TABLE `basketSostav`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `basketsostav_ibfk_2` (`basket_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sostav`
--
ALTER TABLE `sostav`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sostav_ibfk_1` (`order_id`),
  ADD KEY `sostav_ibfk_2` (`product_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `basketSostav`
--
ALTER TABLE `basketSostav`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `sostav`
--
ALTER TABLE `sostav`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `basketSostav`
--
ALTER TABLE `basketSostav`
  ADD CONSTRAINT `basketsostav_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `basketsostav_ibfk_2` FOREIGN KEY (`basket_id`) REFERENCES `basket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sostav`
--
ALTER TABLE `sostav`
  ADD CONSTRAINT `sostav_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sostav_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
