-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3305
-- Generation Time: May 28, 2024 at 06:38 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet_shop_db`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `client_id`, `inventory_id`, `price`, `quantity`, `date_created`) VALUES
(11, 1, 9, 1000, 1, '2024-04-12 07:46:45'),
(22, 4, 9, 1000, 1, '2024-04-23 10:33:02'),
(25, 5, 1, 250, 1, '2024-05-03 10:09:58'),
(26, 5, 4, 150, 1, '2024-05-03 10:10:16'),
(28, 7, 1, 250, 1, '2024-05-07 09:50:10'),
(29, 7, 9, 1500, 1, '2024-05-07 09:50:18'),
(58, 18, 17, 200, 1, '2024-05-21 13:41:14'),
(59, 18, 18, 1500, 1, '2024-05-21 13:45:58');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `firstname`, `lastname`, `gender`, `contact`, `email`, `password`, `default_delivery_address`, `date_created`) VALUES
(1, 'John', 'Smith', 'Male', '09123456789', 'jsmith@sample.com', '1254737c076cf867dc53d60a0364f38e', 'Sample Address', '2021-06-21 16:00:23'),
(3, 'Prabesh', 'Khanal', 'Male', '9812345678', 'prabesh@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Gokarna, ktm', '2024-04-14 11:45:41'),
(4, 'ashish', 'acharya', 'Male', '984226533', 'aacharya@mydvls.com', '25f9e794323b453885f5181f1b624d0b', 'kathmandu', '2024-04-23 10:32:53'),
(5, 'binod', 'pathak', 'Male', '9812345678', 'binod@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Kirtipur, ktm', '2024-05-03 10:04:19'),
(6, '12', '42', 'Male', 'dsgah', 'fcdgsvabn', 'aa20a790ccccb56be771487b879b95bc', '', '2024-05-03 10:20:03'),
(7, 'Ram', 'Thapa', 'Male', '9812345678', 'ram@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Naxal, KTM', '2024-05-07 09:49:59'),
(8, 'Akriti', 'Dev', 'Female', '98103', 'akriti@', '202cb962ac59075b964b07152d234b70', '', '2024-05-09 08:15:48'),
(12, 'Sita', 'Bhandari', 'Female', '9812345678', 'sita@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Kathmandu', '2024-05-13 12:20:55'),
(13, 'Sitaram', 'Gautam', 'Male', '9812345678', 'sitaram@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'kathmandu', '2024-05-13 12:33:36'),
(14, 'Ramhari', 'Khan', 'Male', '+9779812345678', 'ramhari@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Lalitpur', '2024-05-13 12:37:10'),
(15, 'sumit', 'shah', 'Male', '9818257300', 'sumitshah001122@gmail.com', 'dcf3dca772752b51dbcd715d39da03bb', 'Maharajgunj, Kathmandu, Nepal', '2024-05-13 12:46:43'),
(16, 'Himamshu', 'Bhattarai', 'Male', '9861879799', 'himambhatt@gmail.com', 'a3d757550bd914b2207536ea740a76d4', 'Patan,Lalitpur', '2024-05-13 12:53:58'),
(17, 'Ak', 'Pd', 'Female', '9812345678', 'akritikumaridev@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Sundhara, Ktm', '2024-05-13 13:28:53'),
(18, 'Sami', 'Gautam', 'Female', '9848833794', 'samigautam2004@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Gokarna,Ktm', '2024-05-14 07:04:55'),
(19, 'Ashish', 'Acharya', 'Male', '+9779848833795', 'ashish@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Kathmandu', '2024-05-21 13:48:08');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `unit`, `price`, `size`, `date_created`, `date_updated`) VALUES
(1, 1, 50, 'pcs', 250, 'M', '2021-06-21 13:01:30', '2021-06-21 13:05:23'),
(2, 1, 20, 'Sample', 300, 'L', '2021-06-21 13:07:00', NULL),
(3, 4, 150, 'pcs', 500, 'M', '2021-06-21 16:50:37', NULL),
(4, 3, 50, 'pack', 150, 'M', '2021-06-21 16:51:12', NULL),
(5, 5, 30, 'pcs', 50, 'M', '2021-06-21 16:51:35', NULL),
(6, 4, 10, 'pcs', 550, 'L', '2021-06-21 16:51:54', NULL),
(7, 6, 100, 'pcs', 150, 'S', '2021-06-22 15:50:47', NULL),
(8, 6, 150, 'pcs', 180, 'M', '2021-06-22 15:51:13', NULL),
(9, 7, 1, 'None', 1500, 'NONE', '2024-04-12 07:14:09', '2024-05-21 11:52:02'),
(10, 8, 1, 'None', 800, 'XS', '2024-04-12 07:26:05', '2024-05-21 11:51:18'),
(11, 9, 15, 'Packs', 1500, 'S', '2024-05-09 08:36:41', '2024-05-09 08:39:23'),
(12, 9, 10, 'Packs', 2000, 'M', '2024-05-09 08:39:05', NULL),
(13, 10, 20, 'Pack', 1300, 'S', '2024-05-09 10:18:42', NULL),
(14, 10, 50, 'Pack', 1700, 'M', '2024-05-09 10:19:15', NULL),
(15, 11, 19, 'pack', 1800, 'S', '2024-05-09 10:37:09', '2024-05-21 13:48:51'),
(16, 12, 20, 'pack', 1800, 'S', '2024-05-09 10:37:30', NULL),
(17, 13, 47, 'pcs', 200, 'S', '2024-05-09 10:45:26', '2024-05-21 12:54:10'),
(18, 14, 18, 'pcs', 1500, 'M', '2024-05-09 10:45:56', '2024-05-21 13:53:03'),
(19, 15, 1, 'None', 10000, 'NONE', '2024-05-09 11:05:07', NULL),
(20, 16, 1, 'None', 12000, 'NONE', '2024-05-09 11:12:54', '2024-05-21 11:33:55'),
(21, 17, 1, 'None', 1200, 'NONE', '2024-05-21 11:01:11', '2024-05-21 12:40:31');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `delivery_address`, `payment_method`, `amount`, `status`, `paid`, `date_created`, `date_updated`) VALUES
(4, 2, 'Gokarna, Kathmandu', 'cod', 600, 0, 0, '2024-04-11 14:08:53', NULL),
(5, 1, 'Maiti nepal chowk, Gokarna', 'cod', 1100, 0, 0, '2024-04-12 07:44:24', NULL),
(6, 2, 'Gokarna, Kathmandu', 'cod', 1000, 0, 1, '2024-04-12 09:07:04', '2024-04-14 12:16:25'),
(7, 2, 'Gokarna, Kathmandu', 'cod', 150, 0, 1, '2024-04-12 13:32:03', '2024-04-12 13:33:49'),
(8, 3, 'Gokarna, ktm', 'cod', 1150, 0, 1, '2024-04-14 11:46:37', '2024-05-20 17:05:33'),
(9, 2, 'Gokarna, Kathmandu', 'cod', 1250, 0, 0, '2024-04-16 09:38:26', NULL),
(10, 2, 'Gokarna, Kathmandu', 'cod', 50, 0, 0, '2024-05-09 07:37:29', NULL),
(11, 2, 'Gokarna, Kathmandu', 'cod', 150, 0, 0, '2024-05-09 07:47:00', NULL),
(12, 2, 'Gokarna, Kathmandu', 'cod', 550, 0, 0, '2024-05-09 08:13:40', NULL),
(13, 8, 'ktm', 'cod', 4500, 3, 1, '2024-05-09 08:16:33', '2024-05-21 11:02:45'),
(14, 8, 'ktm', 'cod', 3200, 2, 1, '2024-05-09 08:17:58', '2024-05-21 11:02:17'),
(15, 2, 'Gokarna, Kathmandu', 'cod', 13300, 0, 1, '2024-05-09 16:26:21', '2024-05-10 09:57:23'),
(16, 2, 'Gokarna, Kathmandu', 'cod', 3500, 1, 1, '2024-05-10 10:10:43', '2024-05-10 10:12:13'),
(17, 15, 'Maharajgunj, Kathmandu, Nepal', 'cod', 10000, 1, 0, '2024-05-13 12:47:39', '2024-05-21 11:02:02'),
(22, 18, 'Gokarna,Ktm', 'cod', 3400, 4, 1, '2024-05-14 10:17:50', '2024-05-20 16:56:28'),
(23, 18, 'Gokarna,Ktm', 'cod', 800, 4, 0, '2024-05-20 16:57:07', '2024-05-21 12:54:30'),
(24, 18, 'Gokarna,Ktm', 'cod', 400, 0, 0, '2024-05-21 11:23:11', NULL),
(30, 18, 'Gokarna,Ktm', 'cod', 200, 0, 0, '2024-05-21 12:54:10', NULL),
(31, 19, 'Kathmandu', 'cod', 3300, 0, 0, '2024-05-21 13:48:51', NULL),
(32, 19, 'Kathmandu', 'cod', 3000, 1, 1, '2024-05-21 13:53:03', '2024-05-21 13:53:31');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `order_id`, `product_id`, `size`, `unit`, `quantity`, `price`, `total`) VALUES
(5, 4, 1, 'L', 'Sample', 1, 300, 300),
(6, 4, 3, 'M', 'pack', 2, 150, 300),
(7, 5, 1, 'M', 'pcs', 2, 250, 500),
(8, 5, 3, 'M', 'pack', 2, 150, 300),
(9, 5, 6, 'S', 'pcs', 2, 150, 300),
(10, 6, 7, 'NONE', 'None', 1, 1000, 1000),
(11, 7, 6, 'S', 'pcs', 1, 150, 150),
(12, 8, 1, 'M', 'pcs', 1, 250, 250),
(13, 8, 5, 'M', 'pcs', 2, 50, 100),
(14, 8, 8, 'XS', 'None', 1, 800, 800),
(15, 9, 7, 'NONE', 'None', 1, 1000, 1000),
(16, 9, 1, 'M', 'pcs', 1, 250, 250),
(17, 10, 5, 'M', 'pcs', 1, 50, 50),
(18, 11, 3, 'M', 'pack', 1, 150, 150),
(19, 12, 4, 'L', 'pcs', 1, 550, 550),
(20, 13, 7, 'NONE', 'None', 3, 1500, 4500),
(21, 14, 8, 'XS', 'None', 4, 800, 3200),
(22, 15, 16, 'NONE', 'None', 1, 12000, 12000),
(23, 15, 10, 'S', 'Pack', 1, 1300, 1300),
(24, 16, 7, 'NONE', 'None', 1, 1500, 1500),
(25, 16, 9, 'M', 'Packs', 1, 2000, 2000),
(26, 17, 15, 'NONE', 'None', 1, 10000, 10000),
(31, 22, 10, 'M', 'Pack', 2, 1700, 3400),
(32, 23, 8, 'XS', 'None', 1, 800, 800),
(33, 24, 13, 'S', 'pcs', 2, 200, 400),
(39, 30, 13, 'S', 'pcs', 1, 200, 200),
(40, 31, 11, 'S', 'pack', 1, 1800, 1800),
(41, 31, 14, 'M', 'pcs', 1, 1500, 1500),
(42, 32, 14, 'M', 'pcs', 2, 1500, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(9, 'samigautam2004@gmail.com', '8bkW8z2tJnD29IfdBgiPL8uU6quXs5OlZz6mZKPoM', '2024-05-21 13:44:55');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sub_category_id`, `product_name`, `description`, `status`, `date_created`) VALUES
(7, 6, 6, 'Charlie', '&lt;p&gt;A mixed breed dog of age 3 and weight 20kgs. Dog identification card is available. Color of the Dog is Black.&lt;/p&gt;', 1, '2024-04-12 07:04:19'),
(8, 6, 7, 'coco', '&lt;p&gt;A white cat of age 2 years. Weight is of 5 kgs. Examination/Vaccination done on time.&lt;/p&gt;', 1, '2024-04-12 07:25:22'),
(9, 8, 10, 'Drools', '&lt;p&gt;Drools Chicken and Egg Puppy Food offers the right amount of vitamins and minerals for your pups to enhance their growth and development. We use real chicken with no by -products which is our No 1. ingredient. Our recipes are built to boost digestion and strengthen the immune system to keep them active and healthy all day. Our raw materials go through stringent processes to ensure the quality and safety of the food.&lt;br&gt;&lt;/p&gt;', 1, '2024-05-09 08:23:07'),
(10, 8, 10, 'Pedigree for Big Dogs', '&lt;p&gt;PEDIGREE For Big Dogs Roasted Chicken, Rice &amp;amp; Vegetable Dry Dog Food is formulated to give large dogs all of the energy and nourishment they need to continue living life to the fullest. Our chicken-flavor dog food features naturally sourced glucosamine and chondroitin to support healthy bones and joints in canines. This dry dog food also contains leading levels of the antioxidant vitamin E to help keep adult dogs&#039; immune systems strong and omega-6 fatty acids to nourish skin and a shiny, healthy coat. Plus, our balanced dog food featuring our special fiber blend helps promote healthy digestion to keep yard patrol in control. No high fructose corn syrup, no artificial flavors, and no added sugar. Feed your dog this dry kibble to help keep teeth clean and give them a great taste.&lt;br&gt;&lt;/p&gt;', 1, '2024-05-09 08:27:44'),
(11, 8, 11, 'Reflex Adult Cat Food', '&lt;p&gt;Reflex Adult Cat Food with Chicken is a complete and balanced premium cat food containing chicken protein, carefully formulated by cat / dog nutritionists to meet the daily nutritional needs of all adult breeds.&lt;br&gt;&lt;/p&gt;', 1, '2024-05-09 10:34:02'),
(12, 8, 11, 'Simba Cat Kibbles with Chicken', '&lt;p&gt;SIMBA CAT KIBBLES WITH CHICKEN is a complete pet food for adult cats.The formulation has been developed with a dedicated combination of vitamins A-D3-E to meet the nutritional needs of cats.A balanced nutrition formulated with quality ingredients like chicken,source of proteins and minerals,chosen for its natural ability to fulfill animals&rsquo; daily needs. No added dyes and artificial preservatives.&lt;/p&gt;&lt;p&gt;Nutritional Information:-&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Composition:-Cereals, meat and animal derivatives (chicken 5%), oils and fats, derivatives of vegetable origin, yeasts, minerals.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Analytical Constituents:-Crude protein: 26%, Crude fibre: 2,5%, Crude fat: 11%, Crude Ash: 8,5%. Metabolized energy: 3835 kcal/kg.&lt;/p&gt;', 1, '2024-05-09 10:36:32'),
(13, 7, 8, 'Tortoise Dog Toy', '&lt;p&gt;Our Green Tortoise DogToy is needle-felted using pure New Zealand wool. Benefits include enhancing your dog&rsquo;s dental health and encouraging more social play. Made from eco-friendly, high-quality materials.&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Crafted by our Nepali women artisans. You can even customize it to your liking!!&lt;/p&gt;', 1, '2024-05-09 10:41:56'),
(14, 7, 9, 'Catch the mouse', '&lt;p&gt;About this item&lt;/p&gt;&lt;p&gt;Playing out their hunting instinct: Entertainment toy for cats of every age&lt;/p&gt;&lt;p&gt;Marble run: The round track has 8 openings for curious paws - Cat carousel with ball&lt;/p&gt;&lt;p&gt;Loud: The ball contains a playful bell - Its sound encourages playtime&lt;/p&gt;&lt;p&gt;With mouse: Stick the white soft mouse with feather in the toy &ndash; Swings to and fro&lt;/p&gt;&lt;p&gt;Color may vary&lt;/p&gt;', 1, '2024-05-09 10:44:14'),
(15, 6, 6, 'Dune', '&lt;p&gt;Dune is a vibrant Siberian Husky weighing around 55 pounds. His thick double coat is a mix of white and gray, forming striking patterns, and his eyes are an icy blue. He&#039;s known for his energetic and adventurous spirit, always ready to explore. Dune&#039;s pointed ears and curled bushy tail are hallmarks of his breed. He&#039;s playful and social, often communicating with a range of howls and barks, but also has a gentle, affectionate side, leaning in for ear scratches or head nuzzles. Overall, he&#039;s a lively and loyal companion, bringing joy wherever he goes.&lt;br&gt;&lt;/p&gt;', 1, '2024-05-09 11:03:49'),
(16, 6, 6, 'Maya', '&lt;p&gt;Maya is an adorable 4-month-old Golden Retriever with a playful and inquisitive personality. At this age, she&#039;s growing quickly, weighing about 30 pounds and standing approximately 15 inches tall at the shoulder. Her soft fur is a rich golden color, typical of her breed, and has a slight wave to it. Maya&#039;s eyes are a warm, dark brown, radiating curiosity and warmth.&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size: 1rem;&quot;&gt;She&#039;s energetic and loves to explore her surroundings, often pouncing on leaves or chasing after toys. Maya&#039;s floppy ears perk up when she&#039;s excited, and her wagging tail is a constant reminder of her enthusiasm. She&rsquo;s known for her friendly nature, always eager to meet new people and play with other dogs.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Despite her playful energy, Maya is also a snuggle bug, often curling up beside you for a nap or resting her head on your lap for comfort. She&#039;s smart and quick to learn, already picking up basic commands and responding well to treats and praise. Overall, Maya is a bundle of joy, bringing a sense of fun and love to everyone she meets.&lt;/p&gt;', 1, '2024-05-09 11:08:12'),
(17, 6, 6, 'Julia', '&lt;p&gt;A friendly dog.&lt;/p&gt;', 1, '2024-05-20 17:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `total_amount` double NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_id`, `total_amount`, `date_created`) VALUES
(4, 4, 600, '2024-04-11 14:08:53'),
(5, 5, 1100, '2024-04-12 07:44:24'),
(6, 6, 1000, '2024-04-12 09:07:04'),
(7, 7, 150, '2024-04-12 13:32:03'),
(8, 8, 1150, '2024-04-14 11:46:37'),
(9, 9, 1250, '2024-04-16 09:38:27'),
(10, 10, 50, '2024-05-09 07:37:29'),
(11, 11, 150, '2024-05-09 07:47:00'),
(12, 12, 550, '2024-05-09 08:13:40'),
(13, 13, 4500, '2024-05-09 08:16:33'),
(14, 14, 3200, '2024-05-09 08:17:58'),
(15, 15, 13300, '2024-05-09 16:26:21'),
(16, 16, 3500, '2024-05-10 10:10:43'),
(17, 17, 10000, '2024-05-13 12:47:39'),
(22, 22, 3400, '2024-05-14 10:17:50'),
(23, 23, 800, '2024-05-20 16:57:07'),
(24, 24, 400, '2024-05-21 11:23:11'),
(30, 30, 200, '2024-05-21 12:54:10'),
(31, 31, 3300, '2024-05-21 13:48:51'),
(32, 32, 3000, '2024-05-21 13:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(30) NOT NULL,
  `size` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Pet Shop and Adoption Platform'),
(6, 'short_name', 'Sano Sansaar'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
