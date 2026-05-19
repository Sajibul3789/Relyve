-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 04:50 PM
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
-- Database: `relyve_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(38, 1, 4, 1, '2026-05-14 03:10:27'),
(39, 1, 1, 1, '2026-05-14 03:10:27'),
(40, 1, 8, 2, '2026-05-14 03:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `hero_section`
--

CREATE TABLE `hero_section` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT 'Shop Now',
  `button_link` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `tag_text` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_section`
--

INSERT INTO `hero_section` (`id`, `title`, `subtitle`, `button_text`, `button_link`, `image_url`, `tag_text`, `is_active`, `display_order`, `created_at`) VALUES
(1, 'Samsung Galaxy S25 Ultra', 'Titanium design • AI Camera • 5000mAh Battery', 'Buy Now', 'product_details.php?id=1', 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=1920', 'NEW ARRIVALS 2026', 1, 1, '2026-05-10 13:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cod','card','bkash') DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(20) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `shipping_address`, `shipping_city`, `shipping_zip`, `shipping_phone`, `notes`, `created_at`) VALUES
(1, 'RELYVE1778467758598', 2, 2539982.00, 'cod', 'pending', 'pending', 'adas, sdfs, 24', 'sdfs', '24', '01627870424', '', '2026-05-11 02:49:18'),
(2, 'RELYVE1778727088766', 1, 249999.00, 'cod', 'pending', 'pending', 'fsfs, 234, 3242', '234', '3242', '01700000000', '', '2026-05-14 02:51:28'),
(3, 'RELYVE1778727257272', 1, 249999.00, 'cod', 'pending', 'delivered', 'sfs, wwfwe, 232', 'wwfwe', '232', '01700000000', '', '2026-05-14 02:54:17'),
(4, 'RELYVE1778814095803', 2, 89999.00, 'bkash', 'pending', 'pending', '0, Nolua, Dariapur, Shahjadpur, SIrajganj, Sirajganj, 6770', 'Sirajganj', '6770', '01627870424', '', '2026-05-15 03:01:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 4, 'MacBook Pro M3', 4, 249999.00),
(2, 1, 2, 'iPhone 15 Pro Max', 5, 159999.00),
(3, 1, 5, 'Dell XPS 15', 2, 189999.00),
(4, 1, 8, 'Sony WH-1000XM5', 6, 44999.00),
(5, 1, 11, 'Apple Watch Ultra 2', 1, 89999.00),
(6, 2, 4, 'MacBook Pro M3', 1, 249999.00),
(7, 3, 4, 'MacBook Pro M3', 1, 249999.00),
(8, 4, 11, 'Apple Watch Ultra 2', 1, 89999.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 10,
  `rating` decimal(2,1) DEFAULT 4.5,
  `image_url` varchar(500) DEFAULT NULL,
  `is_hot_deal` tinyint(1) DEFAULT 0,
  `deal_price` decimal(10,2) DEFAULT NULL,
  `deal_start_date` datetime DEFAULT NULL,
  `deal_end_date` datetime DEFAULT NULL,
  `deal_quantity` int(11) DEFAULT NULL,
  `deal_sold` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `old_price`, `category`, `stock`, `rating`, `image_url`, `is_hot_deal`, `deal_price`, `deal_start_date`, `deal_end_date`, `deal_quantity`, `deal_sold`, `created_at`) VALUES
(1, 'Samsung Galaxy S25 Ultra', 'Latest Samsung flagship with AI camera and titanium design', 189999.00, 199999.00, 'smartphones', 25, 4.9, 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(2, 'iPhone 15 Pro Max', 'Apple\'s most powerful iPhone with A17 Pro chip', 159999.00, 169999.00, 'smartphones', 15, 4.8, 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(3, 'Xiaomi Redmi Note 14 Pro', 'Mid-range powerhouse with 200MP camera', 32999.00, 37999.00, 'smartphones', 50, 4.6, 'https://images.unsplash.com/photo-1592899677977-9e10cb588f7f?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(4, 'MacBook Pro M3', '14-inch with M3 Pro chip, 18GB RAM', 249999.00, 269999.00, 'laptops', 9, 4.9, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(5, 'Dell XPS 15', 'Premium Windows laptop with OLED display', 189999.00, 199999.00, 'laptops', 10, 4.7, 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(6, 'iPad Pro 12.9\"', 'M2 chip, Liquid Retina XDR display', 139999.00, 149999.00, 'tablets', 30, 4.8, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(7, 'Samsung Tab S9 Ultra', '14.6\" Dynamic AMOLED 2X display', 129999.00, 139999.00, 'tablets', 18, 4.7, 'https://images.unsplash.com/photo-1589739900243-4b52cd9dd104?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(8, 'Sony WH-1000XM5', 'Industry-leading noise cancellation headphones', 44999.00, 49999.00, 'accessories', 34, 4.9, 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(9, 'Apple AirPods Pro 2', 'Active Noise Cancellation, Adaptive Audio', 24999.00, 27999.00, 'accessories', 60, 4.8, 'https://images.unsplash.com/photo-1600294037681-c80b4f3e9f39?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(10, 'Samsung QLED 4K TV', '65\" Neo QLED 4K Smart TV', 199999.00, 219999.00, 'tv_audio', 8, 4.7, 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(11, 'Apple Watch Ultra 2', '49mm titanium case, dual-frequency GPS', 89999.00, 94999.00, 'watches', 23, 4.9, 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=300', 0, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23'),
(12, 'Logitech MX Master 3S', 'Advanced wireless mouse', 9999.00, 12999.00, 'accessories', 75, 4.6, 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=300', 1, NULL, NULL, NULL, NULL, 0, '2026-05-10 13:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `role`, `reset_token`, `reset_expires`, `created_at`) VALUES
(1, 'Admin', 'User', 'admin@relyve.com', '01700000000', '$2y$10$cXskZXbln/PYNepsa5Wfdu2hXt/o3vxiv65hIo1RFXNCYl3/LmOfW', 'admin', NULL, NULL, '2026-05-10 13:31:23'),
(2, 'Sajibul', 'Haque', 'sajibulhaque93@gmail.com', '01627870424', '$2y$10$PPzLZnXF8voDkwSIqYs1yuNtfTtqE7DNhvM.KVqnt0B7Cme97Gn7C', 'user', 'c3e9faaf8e40212f94cf1e52210daf1745bb11df3e152278f4da1d617f741cfd', '2026-05-14 16:56:35', '2026-05-10 14:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(9, 1, 4, '2026-05-13 12:04:23'),
(14, 1, 8, '2026-05-13 12:19:49'),
(45, 2, 4, '2026-05-15 02:59:10'),
(46, 2, 1, '2026-05-15 02:59:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`);

--
-- Indexes for table `hero_section`
--
ALTER TABLE `hero_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `hero_section`
--
ALTER TABLE `hero_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
