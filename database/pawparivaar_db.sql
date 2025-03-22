-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 05:43 AM
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
-- Database: `pawparivaar_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(30) NOT NULL,
  `client_id` int(30) NOT NULL,
  `inventory_id` int(30) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `category` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `description`, `status`, `date_created`) VALUES
(6, 'Adopt', '&lt;p&gt;Adopt the best pet suited for you.&lt;/p&gt;', 1, '2024-04-12 06:56:03'),
(7, 'Toys', '&lt;p&gt;Play and engage with your pets more often.&lt;/p&gt;', 1, '2024-05-09 07:43:21'),
(8, 'Food', '&lt;p&gt;Healthy and nutritious food for your pets.&lt;/p&gt;', 1, '2024-05-09 07:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(30) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `default_delivery_address` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `firstname`, `lastname`, `gender`, `contact`, `email`, `password`, `default_delivery_address`, `date_created`) VALUES
(27, 'Saddam', 'Khan', 'Male', '9812345678', 'saddam@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'ktm', '2024-12-29 15:46:16'),
(40, 'Sami', 'Gautam', 'Female', '9848833794', 'samigautam2004@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Gokarneshwor-04, Kathmandu', '2025-01-12 15:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `verification_token` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `default_delivery_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `email`, `verification_token`, `is_verified`, `created_at`, `firstname`, `lastname`, `password`, `contact`, `gender`, `default_delivery_address`) VALUES
(1, 'samigautam2004@gmail.com', '1144744b6e2d9bb5de6cd365b3dae5ace9e777cfda1a5fef4eccffb9f4c20d00', 0, '2024-12-29 05:24:43', '', '', '', '', '', ''),
(2, 'shreeyamshugautam@gmail.com', '67f07999e7c0cb5c3c1453cd96d6100f5acc4af6c136b60b8d3e1413c45483ba', 0, '2024-12-29 05:25:39', '', '', '', '', '', ''),
(3, 'shreeyamshugautam@gmail.com', '5f22ca4cf9a3e084c9936715cf4583c7011f710ce75d395636f9fce39d17e4dc', 0, '2024-12-29 05:29:53', '', '', '', '', '', ''),
(4, 'shreeyamshugautam@gmail.com', 'e94b27996396bd3b123b09d7d5246b8e1fcdbd87f5299e531053d15e7cc7842e', 0, '2024-12-29 05:30:10', '', '', '', '', '', ''),
(5, 'shreeyamshugautam@gmail.com', 'aca3f2fc44768f0f9b678d9845b679ab8851e9cb3628eb5753ae35906d87d810', 0, '2024-12-29 05:32:42', '', '', '', '', '', ''),
(6, 'samigautam2004@gmail.com', 'b878cf6cebf55334e7356d636b3a84542d847bfbf370b9fe4488d982845bf15f', 0, '2024-12-29 05:33:14', '', '', '', '', '', ''),
(7, 'samigautam2004@gmail.com', 'a510602b55bf61c0935ee911f30d18cbde4f1348dffe574594898db41be5870e', 0, '2024-12-29 05:39:17', '', '', '', '', '', ''),
(9, 'samigautam2004@gmail.com', '4a562c48124ed8bdcf56c7560ee1dc54200f38759a58edf864294f4afab98645', 1, '2024-12-29 05:58:24', '', '', '', '', '', ''),
(10, 'samigautam2004@gmail.com', '5e1573a945117328b57fe2af02c609d6fa95cbd997511abd5676405c8eaed08d', 1, '2024-12-29 06:03:29', '', '', '', '', '', ''),
(11, 'samigautam2004@gmail.com', '3daad630952b08c00f954516bb412a9c65b74d345c6f1b8a4ebcbfbb37fe4019', 1, '2025-01-03 04:09:27', '', '', '', '', '', ''),
(12, 'samigautam2004@gmail.com', '1e9a7a425ec8c523c6c70ca9670ffdc893280ec2203aec261f2af725b8f2a175', 0, '2025-01-03 04:26:17', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Female', 'goksrna'),
(13, 'samigautam2004@gmail.com', '24366a05c7aa95e865e0492a447a61eb2307e29306d6460ed24abd230aebfcdd', 1, '2025-01-03 04:26:51', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdfgf'),
(14, 'samigautam2004@gmail.com', '00927657c796e6a80d428058c58d5fa438e90d8092e75c64b1bf05c6d5ca61ab', 1, '2025-01-03 04:34:09', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Female', 'gokarna'),
(15, 'samigautam2004@gmail.com', '4f832a02b61e41fd20aff12e705623336a480208e2d1398d9df6e0038c408551', 0, '2025-01-03 04:35:50', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Female', 'gokarna'),
(16, 'samigautam2004@gmail.com', '8e44788bf77c7cb6d4422dbf7239541d7793e4220fa13325b2ddaa3072e40685', 0, '2025-01-03 04:39:56', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Female', 'gokarna'),
(17, 'samigautam2004@gmail.com', '742ac7b7f33e3a8e62c69ac8f27283ba9edecd9f2748f0830fb9e723d2e4144d', 0, '2025-01-03 04:40:36', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'dfg'),
(18, 'samigautam2004@gmail.com', '0720a111f2871d1b3d89aa8126a360ffb4799d0719fac931048e90f720ec2f11', 0, '2025-01-03 04:42:38', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'dfg'),
(19, 'samigautam2004@gmail.com', '17584cf1b3654da928f19f670a5127ea1f35979e9c175ca66e874533abdffa92', 0, '2025-01-03 04:43:14', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'dfg'),
(20, 'samigautam2004@gmail.com', 'f38c35a57b1b92a02a8d5ccbe990b2eb2a4dfe2fe673098bb916e38c85d0ffff', 0, '2025-01-03 04:43:38', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'dhgh'),
(21, 'samigautam2004@gmail.com', '28d4eae9f65532055fc0e66eb22203cf3dc25f5418633deb34e22530689495eb', 0, '2025-01-03 04:46:05', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdfg'),
(22, 'samigautam2004@gmail.com', '69eb2717830f36c4fd3ca8d2d9ea07b7ba6f9bf7ef1afca8addae4714477b364', 0, '2025-01-03 04:47:55', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdfgdg'),
(23, 'samigautam2004@gmail.com', '28724bebee8cf96b7546f2614336b5be3d6f165b4ed5f0648a4ada3939247410', 0, '2025-01-03 04:48:31', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdfgdg'),
(24, 'samigautam2004@gmail.com', 'c3a02f39347a644881bac0524ce3e5ecb3725b115ecbedfd2fa39b3edc42e478', 0, '2025-01-03 04:51:22', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdvf'),
(25, 'samigautam2004@gmail.com', '6b2b9497ea1f7c0ba7991f83f8d1e0ace8a35654d39313aebd4a8695639cdc3b', 0, '2025-01-03 04:53:08', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdvf'),
(26, 'samigautam2004@gmail.com', 'b28f38fc1f9c797f9fe2523f4048e092852b77b79eb98e1923d25bfc237405d2', 0, '2025-01-03 04:58:53', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdvf'),
(27, 'samigautam2004@gmail.com', '12398291360c5ada4294a1567ae1e32545aa6852b6297e6981eb1ac64ab21bb3', 0, '2025-01-03 05:04:14', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sdvf'),
(28, 'samigautam2004@gmail.com', 'f311d59f90aece48a2413ff34d721ac7c94931daf7a3f1fb4c99fd4729f75cdc', 0, '2025-01-05 02:08:23', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'gokarna'),
(29, 'samigautam2004@gmail.com', '90c9b6e2a73fcbe6d33d28aa572867424d03a717f85a37fd6dbccaa89373062a', 0, '2025-01-05 02:08:39', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'gokarna'),
(30, 'samigautam2004@gmail.com', '47a88d4abafdd8198c41fd8dec4f3d8248d02bfce8a3fd54c7ed0e64db06aa65', 1, '2025-01-05 02:15:48', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'axscdfg'),
(31, 'samigautam2004@gmail.com', '1655c0800c58c820e6f704b76c080ae030bdd10e60309f4389e765e42748bb5a', 0, '2025-01-05 02:21:19', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'wsdefgb'),
(32, 'samigautam2004@gmail.com', '1c8f314c0d1280967e3151d6eddd51dea242c76bf470fb5d07ce010c84fb8dc4', 0, '2025-01-05 02:47:22', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'der'),
(33, 'samigautam2004@gmail.com', '0e75471240848c3de13c37b894e785924e0a544d39ade1210affddf677e6cac4', 0, '2025-01-05 02:54:33', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sxdf'),
(34, 'samigautam2004@gmail.com', '4f71749db37008972a2fbab8644b5cd9598d28a5dcb89fd86c424446e7ae098e', 1, '2025-01-05 03:03:25', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'sadfb'),
(35, 'samigautam2004@gmail.com', '286d9a08cde908ad27bfd739214e009e21668554bbee2e822ccec410345f3a6d', 0, '2025-01-05 03:06:54', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'qwdefr'),
(36, 'samigautam2004@gmail.com', 'a3ed0431c41df35d17bb438483b0b4c46e33bd6f9538f3a72dc2e23325a655a1', 1, '2025-01-05 05:58:37', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Female', 'Gokarna'),
(37, 'samigautam2004@gmail.com', 'c37bfbd77d9bc165db2a5026da73ac044cc1078df45c5c2eb7fe981d0532c5a8', 1, '2025-01-05 06:02:05', 'Samir', 'acharya', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'KTM'),
(38, 'samigautam2004@gmail.com', '8216e26d8792ecd3e4e98e30825693814c7622c0b61553d05c13a6442981a8d0', 1, '2025-01-05 06:07:53', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Female', 'fgfbnbvc'),
(39, 'samigautam2004@gmail.com', '851787c8e9e06e1f56189ee1216ba1fe4392efc4cb51d7e86e7a09d789fe5da7', 1, '2025-01-05 06:09:28', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'xcdvfdbg'),
(40, 'shreeyamshugautam@gmail.com', '74325f9045b1ec2b23dbe8dbe4f8752b9c0dd858b9234a6a808588846957fe65', 1, '2025-01-05 09:53:41', 'shreeyamshu', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'Gokarna, KTM'),
(41, 'samigautam2004@gmail.com', '6fc3ce5b40707466385eae7cbe80a3070f688367bddc93648709c074654a4443', 1, '2025-01-05 10:04:08', 'binod', 'acharya', '81dc9bdb52d04dc20036dbd8313ed055', '9812345678', 'Male', 'ktm'),
(42, 'samigautam2004@gmail.com', '3355ee0a7065818246df294f279d2bd1d9d415bc8b0c68be590cfa67b520ed28', 1, '2025-01-12 09:38:31', 'Sami', 'Gautam', '81dc9bdb52d04dc20036dbd8313ed055', '9848833794', 'Female', 'Gokarneshwor-04, Kathmandu');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `size` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `unit`, `price`, `size`, `date_created`, `date_updated`) VALUES
(22, 18, 7, 'Pcs', 1500, 'M', '2025-01-05 12:00:36', '2025-03-09 06:44:05'),
(23, 19, 13, 'Pcs', 1650, 'L', '2025-01-05 12:03:49', '2025-03-09 06:15:34'),
(24, 19, 20, 'Pcs', 1200, 'M', '2025-01-05 12:04:13', '2025-03-09 06:17:40'),
(25, 20, 25, 'Pcs', 250, 'M', '2025-01-05 12:07:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(30) NOT NULL,
  `client_id` int(30) NOT NULL,
  `delivery_address` text NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `amount` double NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 0,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `delivery_address`, `payment_method`, `amount`, `status`, `paid`, `date_created`, `date_updated`) VALUES
(33, 40, 'Gokarneshwor-04, Kathmandu', 'cod', 1650, 2, 0, '2025-02-02 15:39:28', '2025-03-09 06:16:57'),
(34, 18, 'Gokarna,Ktm', 'Online Payment', 1500, 0, 1, '2025-03-09 06:12:58', NULL),
(35, 18, 'Gokarna,Ktm', 'cod', 1650, 0, 0, '2025-03-09 06:15:34', NULL),
(36, 18, 'Gokarna,Ktm', 'cod', 1200, 0, 0, '2025-03-09 06:21:32', NULL),
(37, 18, 'Gokarna,Ktm', 'cod', 0, 0, 0, '2025-03-09 06:31:20', NULL),
(38, 18, 'Gokarna,Ktm', 'cod', 250, 0, 0, '2025-03-09 06:31:37', NULL),
(39, 40, 'Gokarneshwor-04, Kathmandu', 'cod', 1500, 0, 0, '2025-03-09 06:42:40', NULL),
(40, 40, 'Gokarneshwor-04, Kathmandu', 'Online Payment', 1500, 0, 1, '2025-03-09 06:44:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `size` varchar(20) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `order_id`, `product_id`, `size`, `unit`, `quantity`, `price`, `total`) VALUES
(43, 33, 19, 'L', 'Pcs', 1, 1650, 1650),
(44, 34, 18, 'M', 'Pcs', 1, 1500, 1500),
(45, 35, 19, 'L', 'Pcs', 1, 1650, 1650),
(46, 36, 19, 'M', 'Pcs', 1, 1200, 1200),
(47, 38, 20, 'M', 'Pcs', 1, 250, 250),
(48, 39, 18, 'M', 'Pcs', 1, 1500, 1500),
(49, 40, 18, 'M', 'Pcs', 1, 1500, 1500);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'samigautam2004@gmail.com', 'GuosjAEmqKLRmSW9iCozSYggmGcnHLZ6aROn32KyorA', '2024-05-13 08:47:34'),
(2, 'samigautam2004@gmail.com', 'MCQwSPaqKrufS89rh84VDN3PAmlkf4efcd2aZbdd4', '2024-05-13 08:48:37'),
(3, 'sumitshah001122@gmail.com', 'aPFN1RT84mj7Ts3zpQKefZqTD0BLRDY0xuvdr91ZrGM', '2024-05-13 12:48:58'),
(4, 'samigautam2004@gmail.com', 'OJhU4lGOfOHIH1DrVoesao79xPYsIYJMpTlRM', '2024-05-14 09:52:21'),
(5, 'samigautam2004@gmail.com', 'kQhlZG8Q4IHAqtsiNJmMcI6g8Od12IVY4v6lfk4YKA', '2024-05-20 16:54:27'),
(6, 'samigautam2004@gmail.com', 'AdDIyPJ4XI5WjQPBHHlIwCtD400YlG8rBoxgODap2DU', '2024-05-20 16:59:29'),
(7, 'samigautam2004@gmail.com', '2C2OlmeNOb8KMQQEetWiMYtjTiMxC7YybrydRbeNrwA', '2024-05-21 13:44:34'),
(8, 'samigautam2004@gmail.com', 'ELhg86v62oL9WMf2IU01WHHDJuKnUXv0T7qBwRe2Ho', '2024-05-21 13:44:39'),
(9, 'samigautam2004@gmail.com', '8bkW8z2tJnD29IfdBgiPL8uU6quXs5OlZz6mZKPoM', '2024-05-21 13:44:55'),
(10, 'samigautam2004@gmail.com', 'QvDnHMsdrjGZhTeV3CRH51Zxtz8vfOXVc4SIQXg83Vs', '2024-12-22 13:46:37'),
(11, 'samigautam2004@gmail.com', '2QLeNgFBscoCjzd6hppVhUEA4oyCtenT1HTz79KecE', '2024-12-22 13:50:03'),
(12, 'samigautam2004@gmail.com', 'ilErJRJXG3yoxn79VYYeTB17ZREAfc8ueuScvVb8RI', '2024-12-22 13:50:30'),
(13, 'samigautam2004@gmail.com', 'bsZnDrmoQJiEAwjuvWqouYgxcTvZ6C7SXiJZ49oeFUQ', '2024-12-22 13:54:55'),
(14, 'samigautam2004@gmail.com', '3wzibcwAjj0o5ZaD96y6wbxwJEpGDNKxjcpxTSvUs', '2024-12-22 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `sub_category_id` int(30) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sub_category_id`, `product_name`, `description`, `status`, `date_created`) VALUES
(18, 8, 10, 'Drools Puppy Chicken And Egg Dog Food - 400gms', '&lt;p&gt;Drools Puppy Chicken and Egg Dog Food is a high-quality dry food suitable for all sizes of puppies. Made with real chicken and egg, this food provides essential nutrients for your puppy&#039;s growth and development. The 400gms pack is perfect for trying out the food before committing to a larger size.&amp;nbsp;&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Brand: Drools&lt;/li&gt;&lt;li&gt;Flavour: Chicken&lt;/li&gt;&lt;li&gt;Item weight: 400gm&lt;/li&gt;&lt;li&gt;Item form: Dry&lt;/li&gt;&lt;li&gt;Age Range: Puppy&lt;/li&gt;&lt;li&gt;Breed Recommendation: All Breeds&lt;/li&gt;&lt;li&gt;Self Life: 2 Years from the date of manufacture&lt;/li&gt;&lt;/ul&gt;', 1, '2025-01-05 11:59:29'),
(19, 8, 11, 'Purepet Ocean Fish Adult Dry Cat Food', '&lt;p&gt;Purepet Ocean Fish Adult Dry Cat Food is a high-quality, nutritious and delicious food for your feline friend. This dry cat food is specially formulated to meet the nutritional needs of adult cats. Made with real ocean fish, it provides a great source of protein and omega-3 fatty acids, which help maintain healthy skin and a shiny coat. The 7kg pack ensures that your cat is well-fed for a long time. This cat food is perfect for pet owners who want to provide their cats with a balanced and healthy diet.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Enriched with Vitamins, Minerals and other nutrients promote the health benefits of cat&lt;/li&gt;&lt;li&gt;Prebiotics and Probiotics improve the digestive system and keep the immunity strong&lt;/li&gt;&lt;li&gt;The blend of organic minerals along with the essential ingredients help to control urinary pH&lt;/li&gt;&lt;li&gt;All the ingredients get thoroughly checked to maintain the safety; Quality and the nutritional value of the food is absolutly great&lt;/li&gt;&lt;li&gt;Flavor Name: Seafood;&lt;/li&gt;&lt;li&gt;Breed Recommendation: All Breed Sizes;&lt;/li&gt;&lt;li&gt;Material Features: Non-Vegetarian&lt;/li&gt;&lt;li&gt;Weight: 7kg&lt;/li&gt;&lt;/ul&gt;', 1, '2025-01-05 12:03:23'),
(20, 7, 8, 'Braided Dog Chew Double Toy', '&lt;ul&gt;&lt;li&gt;Size: 15 Inches Length 2.5 inches circumference&lt;/li&gt;&lt;li&gt;Material: cotton&lt;/li&gt;&lt;li&gt;With double ball&lt;/li&gt;&lt;li&gt;Color: Random Color Sent. No color options available.&lt;/li&gt;&lt;li&gt;Application: Pet Training, Toy&lt;/li&gt;&lt;li&gt;Helpful tooth cleaner -- these cotton rope toys can easily keep your dog&#039;s teeth and gums clean and healthy.&lt;/li&gt;&lt;li&gt;Especially suitable for young dogs, when their tooth is growing, you also need to buy some things to they chew, it&#039;s helpful for them, improve dental health, reduce dental calculus and other diseases, the dog toys are the best choice.&lt;/li&gt;&lt;li&gt;100% safe cotton material ediable&lt;/li&gt;&lt;li&gt;Focus pet dog toy set used the safest material to ensure the longest use and chewing. Made of durable 100% cotton, safe and nontoxic.&lt;/li&gt;&lt;li&gt;Gentle on dog&#039;s mouth yet durable for powerful jaws, chewing or biting the rope is the best way to clean dog&#039;s teeth and improve dental health&lt;/li&gt;&lt;li&gt;Reduce disruptive behavior&lt;/li&gt;&lt;li&gt;Once pet dog have the chew toys will help reduce their disruptive behavior,and protect the shoes,socks,beds,sofa and other household supplies&lt;/li&gt;&lt;li&gt;Strengthen the relationship with host&lt;/li&gt;&lt;li&gt;Puppy Chew Rope Toys Help to Redirect Bad Biting Behavior, Increase Interaction, Closer and Better Relationship&lt;/li&gt;&lt;li&gt;Training and educational&lt;/li&gt;&lt;li&gt;With a strong rolling, make the dog lively and lovely, exercise dog&#039;s body, keep healthy, enhance intelligence.&lt;/li&gt;&lt;/ul&gt;', 1, '2025-01-05 12:06:51'),
(21, 8, 10, 'Test food', '&lt;p&gt;lorem ipsumgvfbhdnjkmcfvbskjfhnkmnlfvbjnkcmv vbdhjcas&lt;/p&gt;', 1, '2025-03-09 06:27:42');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `total_amount` double NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_id`, `total_amount`, `date_created`) VALUES
(33, 33, 1650, '2025-02-02 15:39:28'),
(34, 34, 1500, '2025-03-09 06:12:58'),
(35, 35, 1650, '2025-03-09 06:15:34'),
(36, 36, 1200, '2025-03-09 06:21:32'),
(37, 38, 250, '2025-03-09 06:31:37'),
(38, 39, 1500, '2025-03-09 06:42:40'),
(39, 40, 1500, '2025-03-09 06:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(30) NOT NULL,
  `size` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `size`) VALUES
(1, 'xs'),
(2, 's'),
(3, 'm'),
(4, 'l'),
(5, 'xl'),
(6, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(30) NOT NULL,
  `parent_id` int(30) NOT NULL,
  `sub_category` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `parent_id`, `sub_category`, `description`, `status`, `date_created`) VALUES
(6, 6, 'Dog', '&lt;p&gt;Best Dogs for you&lt;/p&gt;', 1, '2024-04-12 06:57:01'),
(7, 6, 'Cat', '&lt;p&gt;Best Cats for you&lt;/p&gt;', 1, '2024-04-12 06:57:21'),
(8, 7, 'Dog Toys', '&lt;p&gt;Play with your dog more often.&lt;/p&gt;', 1, '2024-05-09 07:44:18'),
(9, 7, 'Cat Toys', '&lt;p&gt;Play with your cats more often.&lt;/p&gt;', 1, '2024-05-09 07:45:27'),
(10, 8, 'Dog Food', '&lt;p&gt;Healthy and nutritious dog food for your pet.&lt;/p&gt;', 1, '2024-05-09 08:08:55'),
(11, 8, 'Cat Food', '&lt;p&gt;Healthy and nutritious food for your cat.&lt;/p&gt;', 1, '2024-05-09 08:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Animal Rescue and Pet Adoption Platform'),
(6, 'short_name', 'Paw Parivaar'),
(11, 'logo', 'uploads/1624240440_paw.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/1624240440_banner1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/1624240500_avatar.png', NULL, 1, '2021-01-20 14:02:37', '2021-06-21 09:55:07'),
(4, 'John', 'Smith', 'jsmith', '1254737c076cf867dc53d60a0364f38e', NULL, NULL, 0, '2021-06-19 08:36:09', '2021-06-19 10:53:12'),
(5, 'Claire', 'Blake', 'cblake', '4744ddea876b11dcb1d169fadf494418', NULL, NULL, 0, '2021-06-19 10:01:51', '2021-06-19 12:03:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
