-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 09, 2024 at 11:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `admin_id`, `name`, `image`) VALUES
(1, 3, 'Friends', '/public/images/chats/contact.png'),
(2, 3, 'BootCamp', '/public/images/chats/2.png');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `message_id` bigint UNSIGNED DEFAULT NULL,
  `body` varchar(100) DEFAULT NULL,
  `image` text,
  `seen` tinyint NOT NULL DEFAULT '0' COMMENT '0 => not seen, 1 => seen',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `user_id`, `message_id`, `body`, `image`, `seen`, `created_at`, `updated_at`) VALUES
(5, 1, 4, NULL, 'Hello Friends. (edited)', '', 1, '2024-02-07 17:28:59', '2024-02-08 09:21:05'),
(6, 2, 3, NULL, 'Hello Friends. (edited)', '', 1, '2024-02-07 17:28:59', '2024-02-08 20:11:24'),
(11, 2, 4, NULL, 'How is the project going??', NULL, 1, '2024-02-08 09:16:25', '2024-02-08 20:11:24'),
(12, 1, 3, NULL, 'I am now logged in', NULL, 1, '2024-02-08 09:16:54', '2024-02-08 19:23:04'),
(13, 1, 4, NULL, 'check seen event', NULL, 1, '2024-02-08 09:17:31', '2024-02-08 09:21:05'),
(14, 1, 3, NULL, 'gh', '', 1, '2024-02-08 09:56:04', '2024-02-08 19:23:04'),
(17, 1, 3, NULL, 'dsfsf', '', 1, '2024-02-08 22:30:50', '2024-02-09 10:44:50'),
(18, 1, 3, NULL, '', '', 1, '2024-02-08 22:35:01', '2024-02-09 10:46:31'),
(19, 1, 3, NULL, '', '', 1, '2024-02-08 23:08:55', '2024-02-09 10:46:31'),
(20, 1, 3, NULL, '', '', 1, '2024-02-08 23:12:00', '2024-02-09 10:46:31'),
(21, 1, 3, NULL, '', '', 1, '2024-02-08 23:14:19', '2024-02-09 10:46:31'),
(22, 1, 3, NULL, 'fff', '', 1, '2024-02-08 23:22:08', '2024-02-09 10:46:31'),
(23, 1, 3, NULL, '', '/public/images/1707434740-flower1.png', 1, '2024-02-08 23:25:40', '2024-02-09 10:46:31'),
(24, 1, 3, NULL, 'ggggg', '', 1, '2024-02-08 23:37:44', '2024-02-09 10:46:31'),
(25, 1, 3, NULL, 'dddd', '', 1, '2024-02-08 23:41:19', '2024-02-09 10:46:31'),
(26, 1, 3, NULL, 'how are you doing?', '', 1, '2024-02-09 10:44:20', '2024-02-09 10:46:31'),
(27, 1, 6, NULL, 'I AM AHMAD', '', 1, '2024-02-09 10:49:28', '2024-02-09 10:49:38'),
(28, 1, 3, NULL, 'TFD', '', 1, '2024-02-09 10:50:04', '2024-02-09 10:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint DEFAULT '1' COMMENT '0 => inactive, 1 => active',
  `user_type` tinyint DEFAULT '0' COMMENT '0 => user, 1 => admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `name`, `password`, `status`, `user_type`) VALUES
(3, 'fateme', 'f@gmail.com', 'fateme', '123456', 1, 1),
(4, 'nasrin', 'n@yahoo.com', 'nasrin', '123456', 0, 0),
(6, 'ahmad', 'a@gmail.com', 'ahmad', '123456', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_chats_users` (`admin_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_messages_messages` (`message_id`),
  ADD KEY `FK_messages_chats` (`chat_id`),
  ADD KEY `FK_messages_users` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `FK_chats_users` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `FK_messages_chats` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_messages_messages` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_messages_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
