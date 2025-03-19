-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2024 at 05:50 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `organic`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Beans'),
(2, 'Fruits'),
(3, 'Vegetables'),
(4, 'Meats'),
(5, 'Dairy');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Madhusudan Subedi', 'madhusudansubedi77@gmail.com', 'About the product', 'This is awesome.', '2024-12-09 22:24:48'),
(2, 'Madhusudan Subedi', 'madhusudansubedi77@gmail.com', 'About the product', 'This is awesome.', '2024-12-09 22:26:39'),
(3, 'Sey weio', 'me@gmail.com', 'mad', 'HIUdan', '2024-12-09 22:26:59'),
(4, 'Madhusudan Subedi', 'madhusudansubedi77@gmail.com', 'About the product', 'This is awesome.', '2024-12-09 22:28:38'),
(5, 'Su', 'Suman@gmail.com', 'sadhf@jiow.com', 'jodfhouenfiwo', '2024-12-09 22:29:16'),
(6, 'Suman Subedi', 'suman@gmail.com', 'isdfjo', 'This is the message.', '2024-12-09 22:31:06'),
(7, 'Western Sydney', 'wsu@gmail.com', 'Test email', 'This is the test email.', '2024-12-10 22:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `product_id`, `quantity`) VALUES
(4, 5, 17.00, 'Pending', 27, 1),
(5, 5, 11.00, 'Pending', 28, 1),
(6, 3, 51.00, 'Pending', 27, 3),
(7, 3, 165.00, 'Pending', 28, 15),
(8, 3, 10.00, 'Pending', 29, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock_quantity`, `image`, `category_id`) VALUES
(27, 'Almonds', 'Fresh Almonds direct from farm.', 17.00, 8, '../uploads/almonds.jpeg', 2),
(28, 'Apple', 'Fresh red apple from the farms, Best before 30 days', 11.00, 99, '../uploads/apple.jpeg', 2),
(29, 'Banana', 'Banana\'s direct from the farms.', 5.00, 33, '../uploads/banana.jpeg', 2),
(30, 'Brocoli', 'Fresh Brocoli, seasonal product from the farms,it is grown without using any pesticides.', 7.00, 100, '../uploads/brocoli.jpeg', 3),
(32, 'Tomatoes', 'Big tomatoes, pick from the farm, ready to serve in your kitchen. Best before 1 months.', 9.00, 1001, '../uploads/TOMATO.JPEG', 3),
(33, 'Macro tomatoes', 'Small sized tomatoes, withe each package of 10 pieces.', 11.00, 77, '../uploads/Macro tomatoes.jpeg', 3),
(34, 'Red kidney beans', 'Beans for daily use.', 16.00, 33, '../uploads/Red kidney bean.jpeg', 3),
(35, 'Strawberry', 'Sweet and tasty strawberry picked by farmers, washed and packed and ready to serve.', 9.00, 32, '../uploads/Strawberries.jpeg', 2),
(36, 'Chicken', 'Fresh chicken', 14.00, 200, '../uploads/chicken.jpeg', 4),
(37, 'Baby Goat', 'Baby goat, with regular size pieces.', 18.00, 187, '../uploads/mutton.jpeg', 4),
(38, 'Egg', 'Duck eggs, with 1*12 pieces', 5.00, 123, '../uploads/egg.jpeg', 4),
(39, 'Milk', 'Packed milk, with no raw ingredients.', 9.00, 122, '../uploads/milk.jpeg', 5),
(40, 'Curd', 'Curd, best before 20 days without fermentation', 11.00, 98, '../uploads/curd.jpeg', 5),
(41, 'Corrainder', 'Fresh coriander, picked from farms ready to serve in your kitchen.', 2.00, 98, '../uploads/corrainder.jpeg', 3),
(42, 'Mango', 'Sweet and Tasty mango which meets your taste.', 6.00, 103, '../uploads/mango.jpeg', 2),
(43, 'Litchi', 'Off seasonal litchi produced in control environment, so that your paletes meet the organic taste from farm.', 22.00, 105, '../uploads/litchi.jpeg', 2),
(44, 'Orange', 'Juicy oranges with natural taste of himalayas.', 12.00, 103, '../uploads/orange.jpeg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `product_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `rating`, `comment`, `created_at`, `product_id`) VALUES
(11, 5, 5, 'This is awesome and tasty, you won\'t regret buying it.', '2024-12-10 18:30:11', 27),
(12, 3, 4, 'This is good and tasty.', '2024-12-10 18:42:14', 28),
(13, 3, 2, 'Cannot meet my expectations.', '2024-12-10 18:42:31', 33),
(14, 7, 4, 'This product is good and it was fresh and easy to cook.', '2024-12-10 18:44:33', 32),
(15, 7, 2, 'It wasn\'t that good.', '2024-12-10 18:44:44', 35),
(16, 8, 5, 'Fresh and tasty, you can try this.', '2024-12-10 18:45:18', 30),
(17, 8, 3, 'Takes alot of time to cook.', '2024-12-10 18:45:34', 34),
(18, 9, 5, 'Awesome product, i will buy from here from now on.', '2024-12-10 18:46:28', 33),
(19, 9, 5, 'It was so tasty.', '2024-12-10 18:46:39', 42),
(20, 9, 4, 'Tasty and juicy.', '2024-12-10 18:46:52', 37),
(21, 10, 5, 'Fresh and tasty.', '2024-12-10 18:47:25', 39),
(22, 11, 5, 'Tasty orange', '2024-12-10 18:47:52', 44),
(23, 11, 5, 'Nice product, just loved it😊', '2024-12-10 18:48:12', 34),
(24, 12, 5, 'This product i just loved it.', '2024-12-10 18:48:39', 30),
(25, 12, 5, 'Whole chicken was awesome.', '2024-12-10 18:49:35', 36),
(26, 13, 5, 'Can i order all of this, it was so great and tasty.', '2024-12-10 18:50:12', 35);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `phone`, `password`, `role`) VALUES
(3, 'Suman', 'subedi', 'suman', 'suman@gmail.com', '0987654321', '$2y$10$sQjacPujDwx/YuW9H1KFD.SMPRsFMy6oX.J.3lu80HCJvfKyOU5qC', 'user'),
(5, 'Admin', 'User', 'admin', 'admin@example.com', '1234567890', '$2y$10$AtI40YhDjVO6FOzrFzU3ju4fLFaE/GtQk3LR2OXXvHuj3fagKA1zG', 'admin'),
(7, 'Sandip', 'Thapa', 'sandip', 'sandip@example.com', '9817465432', '$2y$10$TU5WKVG5LiQWJ2EDqj8qD.z/jqadzB.VsMFTE0yHTHng8OBwjDtha', 'user'),
(8, 'Rahul', 'Shah', 'Rahul', 'rahul@gmail.com', '8374298375', '$2y$10$catDfhtc92Rk3gGuFx7TYe2kRyBgliUeCv1ox0m5FrNTOtYFVyCDm', 'user'),
(9, 'Alex', 'Smith', 'alex', 'alex@gmail.com', '8298203942', '$2y$10$Y9S.jurEPr/o/BpIokyKce9RqxygDuYdpGAZgVxFyVWUg6NcRo1gK', 'user'),
(10, 'Western', 'Sydney', 'wsu', 'wsu@gmail.com', '9898989898', '$2y$10$Z93l04H/YG4dY2PfsdcLNuyWM/r.TNmWq97vbnqI.a7/6M2xevMze', 'user'),
(11, 'Saujan', 'Bindukar', 'saujan', 'saujan@gmail.com', '3892389238', '$2y$10$DGS9F7I7zxcrvyDLQArJIOc3yo9dZkJjFiSR.2yOC5kTZ1zCM6FVC', 'user'),
(12, 'Ed', 'sheran', 'sheraned', 'ed@gmail.com', '9090909090', '$2y$10$RpkFNCQqR18LA3O9mj9vK.mcIdokf/u3.xbjq1/Sor2jnFzS1P3Ve', 'user'),
(13, 'Charlie', 'puth', 'charlie', 'charlie@gmail.com', '3737373737', '$2y$10$uGt1RbY97WpO4uNZBX/Ua.2dlVAVPHx25s9u90//e6ahVwTAZRBa.', 'user');

