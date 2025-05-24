-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 08:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartize_store_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Power_Strip'),
(2, 'Security_Camera'),
(3, 'TWS_Earbuds'),
(4, 'Car_Accessories'),
(5, 'Charging'),
(6, 'Power_Bank');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `main_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `description`, `image`, `category`, `created_at`, `main_image`) VALUES
(25, 'Baseus PowerCombo Pro 7 Ports Power Strip 65W', 79.99, 'شريط طاقة ب7 منافذ بقوة 65W', NULL, 1, '2025-05-11 08:18:34', 'Power_Strip/Baseus PowerCombo Pro 7 Ports Power Strip 65W/main.webp'),
(26, 'Baseus N1 Outdoor Security Camera', 129.99, 'كاميرا مراقبة خارجية مع مجموعة من كاميرتين', NULL, 2, '2025-05-11 08:18:34', 'Security_Camera/Baseus N1 Outdoor Security Camera/main.webp'),
(27, 'Baseus AirNora 2 TWS Bluetooth Earbuds', 59.99, 'سماعات لاسلكية بتقنية البلوتوث مع صوت عالي الجودة', NULL, 3, '2025-05-11 08:18:34', 'TWS_Earbuds/Baseus AirNora 2 TWS Bluetooth Earbuds/main.webp'),
(28, 'Baseus CW01 Magsafe Wireless Car Mount 15W For Car Vent', 39.99, 'حامل لاسلكي للسيارة بتقنية Magsafe بقوة 15W', NULL, 4, '2025-05-11 08:18:34', 'images/Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/main.webp'),
(29, 'Baseus Fish Eye USB-C Spring Cable 2A 3.3 ft', 19.99, 'كابل USB-C مرن بطول 3.3 قدم مع تيار 2A', NULL, 5, '2025-05-11 08:18:34', 'images/Charging/Baseus_Fish_Eye_USB-C_Spring_Cable_2A_3.3_ft/main.webp'),
(30, 'Baseus Blade Laptop Power Bank 100W 20000mAh', 89.99, 'بنك طاقة بقوة 100W وسعة 20000mAh مناسب للحواسيب المحمولة', NULL, 6, '2025-05-11 08:18:34', 'images/Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/main.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `created_at`) VALUES
(84, 28, 'Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/main.webp', '2025-05-11 22:07:01'),
(85, 28, 'Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/angle1.webp', '2025-05-11 22:07:01'),
(86, 28, 'Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/angle1.webp', '2025-05-11 22:07:01'),
(87, 28, 'Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/angle3.webp', '2025-05-11 22:07:01'),
(91, 29, 'Charging/Baseus_Fish_Eye_USB-C_Spring_Cable_2A_3.3_ft/main.webp', '2025-05-11 22:07:01'),
(92, 29, 'Charging/Baseus_Fish_Eye_USB-C_Spring_Cable_2A_3.3_ft/angle1.webp', '2025-05-11 22:07:01'),
(93, 29, 'Charging/Baseus_Fish_Eye_USB-C_Spring_Cable_2A_3.3_ft/angle2.webp', '2025-05-11 22:07:01'),
(94, 29, 'Charging/Baseus_Fish_Eye_USB-C_Spring_Cable_2A_3.3_ft/angle3.webp', '2025-05-11 22:07:01'),
(98, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/main.jpg', '2025-05-11 22:07:01'),
(99, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/angle1.webp', '2025-05-11 22:07:01'),
(100, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/angle2.webp', '2025-05-11 22:07:01'),
(101, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/angle3.webp', '2025-05-11 22:07:01'),
(102, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/angle4.webp', '2025-05-11 22:07:01'),
(103, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/angle5.webp', '2025-05-11 22:07:01'),
(105, 25, 'Power_Strip/Baseus_PowerCombo_Pro_7_Ports_Power_Strip_65W/main.webp', '2025-05-11 22:07:01'),
(106, 25, 'Power_Strip/Baseus_PowerCombo_Pro_7_Ports_Power_Strip_65W/angle1.webp', '2025-05-11 22:07:01'),
(107, 25, 'Power_Strip/Baseus_PowerCombo_Pro_7_Ports_Power_Strip_65W/angle2.webp', '2025-05-11 22:07:01'),
(108, 25, 'Power_Strip/Baseus_PowerCombo_Pro_7_Ports_Power_Strip_65W/angle3.webp', '2025-05-11 22:07:01'),
(112, 26, 'Security_Camera/Baseus_N1_Outdoor_Security_Camera/main.webp', '2025-05-11 22:07:01'),
(113, 26, 'Security_Camera/Baseus_N1_Outdoor_Security_Camera/angle1.webp', '2025-05-11 22:07:01'),
(114, 26, 'Security_Camera/Baseus_N1_Outdoor_Security_Camera/angle2.webp', '2025-05-11 22:07:01'),
(115, 26, 'Security_Camera/Baseus_N1_Outdoor_Security_Camera/angle3.webp', '2025-05-11 22:07:01'),
(116, 26, 'Security_Camera/Baseus_N1_Outdoor_Security_Camera/angle4.webp', '2025-05-11 22:07:01'),
(119, 27, 'TWS_Earbuds/Baseus_AirNora_2_TWS_Bluetooth_Earbuds/main.webp', '2025-05-11 22:07:01'),
(120, 27, 'TWS_Earbuds/Baseus_AirNora_2_TWS_Bluetooth_Earbuds/angle1.webp', '2025-05-11 22:07:01'),
(121, 27, 'TWS_Earbuds/Baseus_AirNora_2_TWS_Bluetooth_Earbuds/angle2.webp', '2025-05-11 22:07:01'),
(122, 27, 'TWS_Earbuds/Baseus_AirNora_2_TWS_Bluetooth_Earbuds/angle3.webp', '2025-05-11 22:07:01'),
(123, 30, 'Power_Bank/Baseus_Blade_Laptop_Power_Bank_100W_20000mAh/main.webp', '2025-05-22 23:19:10'),
(124, 28, 'Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/angle2.webp', '2025-05-22 23:19:10'),
(125, 25, 'Power_Strip/Baseus_PowerCombo_Pro_7_Ports_Power_Strip_65W/angle4.webp', '2025-05-22 23:19:10'),
(126, 27, 'TWS_Earbuds/Baseus_AirNora_2_TWS_Bluetooth_Earbuds/angle4.webp', '2025-05-22 23:19:10'),
(127, 28, 'Car_Accessories/Baseus_CW01_Magsafe_Wireless_Car_Mount_15W_For_Car_Vent/angle4.webp', '2025-05-22 23:19:10'),
(128, 29, 'Charging/Baseus_Fish_Eye_USB-C_Spring_Cable_2A_3.3_ft/angle4.webp', '2025-05-22 23:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$baMuBtAT7cKrhOSYCbFGpeuRMKONJr.28CDfOebx1KcBG/npa4eLy', 'admin'),
(2, 'john_doe', 'john@example.com', '$2y$10$6Y6RlpD4HqFsVKKQ.GZST.x6cnCyzh/ha0Y4saSgafkqWSZwmLfDq', 'user'),
(3, 'jane_smith', 'jane@example.com', '$2y$10$nHH4AEEj01MCTJWg/LEccujLWOejHw.QxBfKZlUCFdZBnRAnsf7vO', 'user'),
(4, '', 'yara@gmail.com', '$2y$10$32pdVRj5cY9S7Zf79poVOOK8s3UmPA2hkwpojpFq8IXrK7CWbmk4a', 'user'),
(5, 'ahmad', 'ahmad@gmail.com', '$2y$10$ymQ5HH.mC8yKaocmXnFTceEmxUz2wrPW7ec3q1PKQVo//usWVCLpa', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category_new` (`category`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category_new` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
