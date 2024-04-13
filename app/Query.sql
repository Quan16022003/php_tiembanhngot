-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2024 at 02:18 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cuahangbanbanh`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `name`, `created_at`, `modified_at`) VALUES
(1, 'admin', '$2a$10$97KYs6LKLHRYitBQ9yOr8.BI0LdChcs8Kl6nPdDrcsbyEfaZolGNS', 'administration', '2024-03-26 14:08:00', '2024-03-26 14:08:00'),
(2, 'quan1602', '$2a$10$97KYs6LKLHRYitBQ9yOr8.BI0LdChcs8Kl6nPdDrcsbyEfaZolGNS', 'Nguyễn Hoàng Quân', '2024-03-29 15:44:20', '2024-03-29 15:44:20'),
(4, 'quan123', '$2y$10$gp6rP.FcI/HxNdflYvu7buXrf/p6lJnRt9SI3iLeKde7PkbVdL.be', 'abc', '2024-04-01 14:59:11', '2024-04-01 14:59:11'),
(5, 'quan1', '$2y$10$hU8VQkef/e5vpTz1SYpbvOFR.UmB3FNJHpLAdrDur7qm3PXzI8qFm', 'Quân', '2024-04-01 15:02:02', '2024-04-01 15:02:02'),
(6, 'nam', '$2y$10$EHrsBuSuHYHc431NR.VCeOKSFW6OjAXLUjMS8ua2scmQEy2Za5pJm', 'Đặng Trần Nam', '2024-04-03 14:39:01', '2024-04-03 14:39:01');

-- --------------------------------------------------------

--
-- Stand-in structure for view `admin_list_view`
-- (See below for the actual view)
--
CREATE TABLE `admin_list_view` (
`created_at` datetime
,`id` int
,`modified_at` datetime
,`name` varchar(128)
,`username` varchar(32)
);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `parent_id`) VALUES
(1, 'Bánh mì', NULL),
(2, 'Bánh ngọt', NULL),
(3, 'Bánh kem nhỏ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `address` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `customer_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `customer_address` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `customer_phone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(20) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_price` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `address1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `per_detail`
--

CREATE TABLE `per_detail` (
  `id` int NOT NULL,
  `per_id` int DEFAULT NULL,
  `action_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `action_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `check_action` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `price` int DEFAULT NULL,
  `discount` int DEFAULT '0',
  `image_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `view` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `content`, `price`, `discount`, `image_link`, `stock`, `created_at`, `view`) VALUES
('BK001', 1, 'Bánh kem trái cây', 'Bánh kem trái cây tươi ngon và mát lạnh.', 35, 0, '', 50, '2024-03-26 16:42:25', 0),
('BK002', 3, 'Bánh kem sữa dừa', 'Bánh kem sữa dừa ngọt ngào và béo ngậy.', 25, 0, 'banh_kem_sua_dua.jpg', 40, '2024-03-26 16:42:25', 0),
('BK003', 2, 'Bánh kem trái cây', 'Bánh kem với lớp kem và trái cây tươi', 25, 0, NULL, 60, '2024-04-03 07:41:55', 0),
('BK004', 2, 'Bánh kem sô cô la', 'Bánh kem với lớp kem và sô cô la', 30, 0, NULL, 50, '2024-04-03 07:41:55', 0),
('BK005', 2, 'Bánh kem dâu', 'Bánh kem với lớp kem và dâu tươi', 28, 0, NULL, 55, '2024-04-03 07:41:55', 0),
('BM001', 1, 'Bánh mì hành tỏi', 'Bánh mì hành tỏi ngon và thơm.', 10, 0, 'banh_mi_hanh_toi.jpg', 130, '2024-03-26 16:42:25', 0),
('BM002', 1, 'Bánh mì sandwich gà', 'Bánh mì sandwich gà thơm ngon và bổ dưỡng.', 15, 0, 'banh_mi_sandwich_ga.jpg', 80, '2024-03-26 16:42:25', 0),
('BM003', 1, 'Bánh mì thịt nguội', 'Bánh mì với thịt nguội và rau sống', 12, 0, NULL, 80, '2024-04-03 07:41:55', 0),
('BM004', 1, 'Bánh mì gà cay', 'Bánh mì với gà chiên và sốt cay', 15, 0, NULL, 70, '2024-04-03 07:41:55', 0),
('BM005', 1, 'Bánh mì trứng', 'Bánh mì với trứng ốp-la', 11, 0, NULL, 90, '2024-04-03 07:41:55', 0),
('BN001', 2, 'Bánh bông lan trứng muối', 'Bánh bông lan trứng muối siêu mềm và thơm ngon.', 20, 0, 'banh_bong_lan.jpg', 50, '2024-03-26 16:42:25', 0),
('BN002', 2, 'Bánh cookie socola', 'Bánh cookie socola giòn tan và thơm ngon.', 8, 0, 'banh_cookie_socola.jpg', 120, '2024-03-26 16:42:25', 0),
('BN003', 3, 'Bánh sừng bò', 'Bánh ngọt có hình dáng giống sừng bò', 20, 0, NULL, 80, '2024-04-03 07:31:01', 0),
('BN004', 3, 'Bánh muffin', 'Bánh nhỏ tròn với nhiều hương vị khác nhau', 20, 0, NULL, 120, '2024-04-03 07:31:01', 0),
('BN005', 3, 'Bánh donut', 'Bánh hình vòng có nhân và phủ đường', 15, 0, NULL, 100, '2024-04-03 07:31:01', 0),
('BN006', 3, 'Bánh bơ', 'Bánh nhỏ với vị bơ thơm ngon', 10, 0, NULL, 80, '2024-04-03 07:41:55', 0),
('BN007', 3, 'Bánh bí ngô', 'Bánh ngọt vị bí ngô thơm ngon', 12, 0, NULL, 75, '2024-04-03 07:41:55', 0),
('BN008', 3, 'Bánh bông lan socola', 'Bánh nhỏ với vị socola đậm đà', 15, 0, NULL, 70, '2024-04-03 07:41:55', 0);

-- --------------------------------------------------------

--
-- Structure for view `admin_list_view`
--
DROP TABLE IF EXISTS `admin_list_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_list_view`  AS SELECT `admin`.`id` AS `id`, `admin`.`username` AS `username`, `admin`.`name` AS `name`, `admin`.`created_at` AS `created_at`, `admin`.`modified_at` AS `modified_at` FROM `admin` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `per_detail`
--
ALTER TABLE `per_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `per_id` (`per_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `per_detail`
--
ALTER TABLE `per_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);

--
-- Constraints for table `per_detail`
--
ALTER TABLE `per_detail`
  ADD CONSTRAINT `per_detail_ibfk_1` FOREIGN KEY (`per_id`) REFERENCES `permission` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
