SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `first_name`, `second_name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Mustafa', NULL, 'Mustafa', 'mostafaamr4869@gmail.com', '$2y$10$.DCmz34qzcgp4NkGFPn.W.RzUt/Ey3u2dPh2vv/OGKH.RYpUe9RJ6', '2025-04-30 14:34:47'),
(2, 'Mon3m', NULL, 'Mon3m', 'mon3m5566@gmail.com', '$2y$10$HeR7RFPB5HXES0l2OIdyi.ThVrVKqzSfw.A0ocEHGYUrnxiVA5.AC', '2025-04-30 14:40:56'),
(3, 'Abduallah', NULL, 'abduallah', 'boda55@123', '$2y$10$xYPi6e71CbAWDCRniccYRO/Xm7hX2OI5i8e.D4bKRSyakMEcyKnXe', '2025-04-30 16:33:54'),
(4, '3lewa', NULL, '3lewa', '3lewa4@gmail.com', '$2y$10$jSWD/6OintU8VzRzOsjv8OH2X7485H3g7usEK938pJMuAyvUNqSSC', '2025-04-30 17:52:24');

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `grade` varchar(1) NOT NULL,
  `status` varchar(20) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `grades` (`id`, `student_id`, `subject`, `score`, `grade`, `status`, `uploaded_at`) VALUES
(1, 1, 'php', 90, 'A', 'Excellent', '2025-04-30 14:59:04'),
(2, 1, 'database', 95, 'A', 'Excellent', '2025-04-30 15:39:23'),
(3, 3, 'ccna', 90, 'A', 'Excellent', '2025-04-30 16:34:19'),
(4, 4, 'iot', 95, 'A', 'Excellent', '2025-04-30 17:53:05');

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

COMMIT;