-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 17, 2025 lúc 05:14 PM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `frostbite`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_at` datetime DEFAULT NULL,
  `remember_token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_login`
--

INSERT INTO `admin_login` (`id`, `username`, `password`, `failed_attempts`, `last_failed_at`, `remember_token`) VALUES
(1, 'admin', '$2y$10$yz3V7n9wgRwSbOzGldiLA.6QLZMjCO5xcFBDW/sPKK17V9GD77m0m', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `replied` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `username`, `email`, `subject`, `message`, `created_at`, `replied`) VALUES
(1, 'test', 'tan@gmail.com', 'hahaha', 'dcbfghejk', '2025-04-27 05:58:38', 0),
(2, 'test', 'tan@gmail.com', 'hahaha', 'dcbfghejk', '2025-04-27 05:59:13', 0),
(3, 'test', 'tan@gmail.com', 'hahaha', 'dcbfghejk', '2025-04-27 06:00:38', 0),
(4, 'test', 'tan@gmail.com', 'bcd', 'anh tan dep trai vai', '2025-04-27 15:04:44', 1),
(5, 'tan', 'yuukisatoh222@gmail.com', 'test', 'tánieu cap dep trai', '2025-04-27 15:36:22', 1),
(6, 'tan', 'ndtan2101102@student.ctuet.edu.vn', 'abc', 'xyz', '2025-05-10 12:54:59', 1),
(7, 'tan', 'ndtan2101102@student.ctuet.edu.vn', 'abc', 'xyz', '2025-05-15 04:42:07', 0),
(8, 'admin', 'tan@gmail.com', 'hahaha', 'test', '2025-05-15 16:02:02', 0),
(9, 'admin', 'tan@gmail.com', 'hahaha', 'test', '2025-05-15 18:22:29', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `food`
--

CREATE TABLE `food` (
  `id` int(5) NOT NULL,
  `food_name` varchar(50) NOT NULL,
  `food_category` varchar(50) NOT NULL,
  `food_description` varchar(500) NOT NULL,
  `food_original_price` varchar(5) NOT NULL,
  `food_discount_price` varchar(5) NOT NULL,
  `food_availability` varchar(50) NOT NULL,
  `food_veg_nonveg` varchar(10) NOT NULL,
  `food_ingredients` varchar(1000) NOT NULL,
  `food_image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `food`
--

INSERT INTO `food` (`id`, `food_name`, `food_category`, `food_description`, `food_original_price`, `food_discount_price`, `food_availability`, `food_veg_nonveg`, `food_ingredients`, `food_image`) VALUES
(5, 'Chicken Burger', 'Hamburger', 'A juicy, tender chicken patty - grilled or crispy fried - served on a toasted bun with fresh lettuce, ripe tomato, crunchy pickles, and a creamy mayo or special sauce. Perfectly balanced for a flavorful and satisfying bite every time.', '15', '2.49', 'Yes', 'Veg', 'cheese,chicken fried,Lettuce,tomato', 'images/cfb893f1d604e0271b2a6a54c923a9ab.png'),
(6, 'Double Cheese Burger', 'Hamburger', 'Two juicy grilled beef patties stacked with two layers of melted cheese, tucked inside a soft, toasted bun. Finished with fresh lettuce, ripe tomato, pickles, onions, and a signature sauce. Double the meat, double the cheese - made for true burger lovers!', '17', '5.99', 'Yes', 'Veg', 'Beyond Meat,cheese,Lettuce', 'images/a08090ea4134e08defc905336d62a471.png'),
(7, 'Cheese Burger', 'Hamburger', 'A classic favorite - featuring a perfectly grilled beef patty topped with a slice of melted cheese, nestled in a soft toasted bun. Served with fresh lettuce, tomato, pickles, onions, and your choice of sauce. Simple, tasty, and always satisfying.', '13', '1.99', 'Yes', 'Veg', 'Bacon,cheese,Lettuce,Sautéed mushrooms,tomato', 'images/81b2ccd9349062166663dd837cbca52c.png'),
(8, 'Margherita Pizza', 'Pizza', 'A timeless classic with tomato sauce, fresh mozzarella, and basil leaves. Simple, fresh, and full of flavor.', '17', '10.99', 'Yes', 'Veg', 'black beans,cheese,tomato', 'images/d98791b7b4211e8364076839a8c92f98.png'),
(9, 'Four Cheese Pizza', 'Pizza', 'A rich, cheesy blend of mozzarella, parmesan, gorgonzola, and fontina (or other cheeses). Creamy and indulgent.', '19', '12.79', 'Yes', 'Veg', 'Beyond Meat,black beans,cheese,tomato', 'images/8b3cebff2d30c04479ea60b911585a6e.png'),
(10, 'Mushrooms Pizza', 'Pizza', 'Packed with fresh vegetables like bell peppers, onions, mushrooms, olives, and tomatoes. A colorful, healthy option.', '15', '13.99', 'Yes', 'Veg', 'cheese,Fried egg,mushrooms,Sautéed mushrooms,tomato', 'images/8db5b8f9fd1b598eb120e2b16892336c.png'),
(11, 'Meat Lovers Pizza', 'Pizza', 'Loaded with pepperoni, sausage, ham, bacon, and beef. A dream for carnivores.', '17', '16.49', 'Yes', 'Veg', 'Bacon,Beyond Meat,black beans,cheese,Jalapeños ,Onion ,pepper,tomato', 'images/3446458e3dd824037147d72020db3286.png'),
(12, 'Coca - Cola', 'Drink', 'just drink it. Dude', '3', '1.49', 'Yes', 'Veg', '', 'images/c40a365aefd84c24e55bfddb0d6318ee.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `food_categories`
--

CREATE TABLE `food_categories` (
  `id` int(5) NOT NULL,
  `food_categories` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `food_categories`
--

INSERT INTO `food_categories` (`id`, `food_categories`) VALUES
(2, 'Pizza'),
(3, 'Hamburger'),
(4, 'Drink'),
(5, 'Pho');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `food_ingredients`
--

CREATE TABLE `food_ingredients` (
  `id` int(5) NOT NULL,
  `food_ingredients` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `food_ingredients`
--

INSERT INTO `food_ingredients` (`id`, `food_ingredients`) VALUES
(3, 'tomato'),
(4, 'pepper'),
(5, 'cheese'),
(6, 'chicken fried'),
(7, 'salmon'),
(8, 'fried fish'),
(9, 'tofu'),
(10, 'mushrooms'),
(11, 'black beans'),
(12, 'Beyond Meat'),
(13, 'Lettuce'),
(14, 'Pickles '),
(15, 'Onion '),
(16, 'Fried egg'),
(17, 'Bacon'),
(18, 'Jalapeños '),
(19, 'Sautéed mushrooms');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gallery`
--

CREATE TABLE `gallery` (
  `id` int(5) NOT NULL,
  `image` varchar(200) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gallery`
--

INSERT INTO `gallery` (`id`, `image`, `title`) VALUES
(1, 'images/5df7f9c2b5e87c66ac7a25b610904f97.png', 'hahahahaha'),
(2, 'images/561857d202230c4b7b802cd23393f6a3.png', 'friend with hamburger'),
(3, 'images/a52e52f4f91cd2dc3e5ef73810c9c925.png', 'truck and boyyyyy');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `food_category` varchar(100) NOT NULL,
  `food_description` varchar(500) NOT NULL,
  `food_ingredients` varchar(500) NOT NULL,
  `food_original_price` varchar(10) NOT NULL,
  `food_discount_price` varchar(10) NOT NULL,
  `food_veg_nonveg` varchar(100) NOT NULL,
  `food_image` varchar(500) NOT NULL,
  `food_qty` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `food_name`, `food_category`, `food_description`, `food_ingredients`, `food_original_price`, `food_discount_price`, `food_veg_nonveg`, `food_image`, `food_qty`) VALUES
(1, '1', 'ádsfasjf', 'hamburger', 'asdjn', 'pepper', '13', '12', 'Veg', 'images/0ff2e76c6da585c47355caa38c87b1c1.png', '2'),
(2, '2', 'abc', 'hamburger', 'dâsdsdannnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'pepper,salach', '10', '8', 'Veg', 'images/bea02275917e7ed7c74d3dce1e25c329.png', '2'),
(3, '3', 'ádsfasjf', 'hamburger', 'asdjn', 'pepper', '13', '12', 'Veg', 'images/0ff2e76c6da585c47355caa38c87b1c1.png', '2'),
(4, '4', 'hambu', 'hamburger', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '', '10', '8', 'Veg', 'images/b6846b5e78e54fa43f2043d338ad51cd.png', '2'),
(5, '5', 'abc', 'hamburger', 'dâsdsdannnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'pepper,salach', '10', '8', 'Veg', 'images/bea02275917e7ed7c74d3dce1e25c329.png', '2'),
(6, '5', 'ádsfasjf', 'hamburger', 'asdjn', 'pepper', '13', '12', 'Veg', 'images/0ff2e76c6da585c47355caa38c87b1c1.png', '2'),
(7, '6', 'abc', 'hamburger', 'dâsdsdannnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'pepper,salach', '10', '8', 'Veg', 'images/bea02275917e7ed7c74d3dce1e25c329.png', '2'),
(8, '7', 'Chicken Burger', 'hamburger', 'A juicy, tender chicken patty - grilled or crispy fried - served on a toasted bun with fresh lettuce, ripe tomato, crunchy pickles, and a creamy mayo or special sauce. Perfectly balanced for a flavorful and satisfying bite every time.', 'cheese,chicken fried,Lettuce,Onion,Pickles,tomato', '15', '12.49', 'Veg', 'images/cfb893f1d604e0271b2a6a54c923a9ab.png', '2'),
(9, '8', 'Meat Lovers Pizza', 'Pizza', 'Loaded with pepperoni, sausage, ham, bacon, and beef. A dream for carnivores.', 'Bacon,Beyond Meat,black beans,cheese,Jalapeños ,Onion ,pepper,tomato', '17', '16.49', 'Veg', 'images/3446458e3dd824037147d72020db3286.png', '4'),
(10, '9', 'Chicken Burger', 'hamburger', 'A juicy, tender chicken patty - grilled or crispy fried - served on a toasted bun with fresh lettuce, ripe tomato, crunchy pickles, and a creamy mayo or special sauce. Perfectly balanced for a flavorful and satisfying bite every time.', 'cheese,chicken fried,Lettuce,Onion,Pickles,tomato', '15', '12.49', 'Veg', 'images/cfb893f1d604e0271b2a6a54c923a9ab.png', '2'),
(11, '10', 'Double Cheese Burger', 'Drink', 'Two juicy grilled beef patties stacked with two layers of melted cheese, tucked inside a soft, toasted bun. Finished with fresh lettuce, ripe tomato, pickles, onions, and a signature sauce. Double the meat, double the cheese - made for true burger lovers!', 'Beyond Meat,cheese,Lettuce,Onion,Pickles', '17', '5.99', 'Veg', 'images/a08090ea4134e08defc905336d62a471.png', '2'),
(12, '11', 'Double Cheese Burger', 'Drink', 'Two juicy grilled beef patties stacked with two layers of melted cheese, tucked inside a soft, toasted bun. Finished with fresh lettuce, ripe tomato, pickles, onions, and a signature sauce. Double the meat, double the cheese - made for true burger lovers!', 'Beyond Meat,cheese,Lettuce,Onion,Pickles', '17', '5.99', 'Veg', 'images/a08090ea4134e08defc905336d62a471.png', '2'),
(13, '12', 'Double Cheese Burger', 'Drink', 'Two juicy grilled beef patties stacked with two layers of melted cheese, tucked inside a soft, toasted bun. Finished with fresh lettuce, ripe tomato, pickles, onions, and a signature sauce. Double the meat, double the cheese - made for true burger lovers!', 'Beyond Meat,cheese,Lettuce,Onion,Pickles', '17', '5.99', 'Veg', 'images/a08090ea4134e08defc905336d62a471.png', '2'),
(14, '13', 'Coca - Cola', 'Drink', 'just drink it. Dude', '', '3', '1.49', 'Veg', 'images/c40a365aefd84c24e55bfddb0d6318ee.png', '8'),
(15, '14', 'Chicken Burger', 'Drink', 'A juicy, tender chicken patty - grilled or crispy fried - served on a toasted bun with fresh lettuce, ripe tomato, crunchy pickles, and a creamy mayo or special sauce. Perfectly balanced for a flavorful and satisfying bite every time.', 'cheese,chicken fried,Lettuce,tomato', '15', '2.49', 'Veg', 'images/cfb893f1d604e0271b2a6a54c923a9ab.png', '2'),
(16, '15', 'Cheese Burger', 'Drink', 'A classic favorite - featuring a perfectly grilled beef patty topped with a slice of melted cheese, nestled in a soft toasted bun. Served with fresh lettuce, tomato, pickles, onions, and your choice of sauce. Simple, tasty, and always satisfying.', 'Bacon,cheese,Jalapeños,Lettuce,Onion,Sautéed mushrooms,tomato', '13', '1.99', 'Veg', 'images/81b2ccd9349062166663dd837cbca52c.png', '2'),
(17, '16', 'Meat Lovers Pizza', 'Pizza', 'Loaded with pepperoni, sausage, ham, bacon, and beef. A dream for carnivores.', 'Bacon,Beyond Meat,black beans,cheese,Jalapeños ,Onion ,pepper,tomato', '17', '16.49', 'Veg', 'images/3446458e3dd824037147d72020db3286.png', '2'),
(18, '17', 'Double Cheese Burger', 'Hamburger', 'Two juicy grilled beef patties stacked with two layers of melted cheese, tucked inside a soft, toasted bun. Finished with fresh lettuce, ripe tomato, pickles, onions, and a signature sauce. Double the meat, double the cheese - made for true burger lovers!', 'Beyond Meat,cheese,Lettuce', '17', '5.99', 'Veg', 'images/a08090ea4134e08defc905336d62a471.png', '2'),
(19, '18', 'Chicken Burger', 'Hamburger', 'A juicy, tender chicken patty - grilled or crispy fried - served on a toasted bun with fresh lettuce, ripe tomato, crunchy pickles, and a creamy mayo or special sauce. Perfectly balanced for a flavorful and satisfying bite every time.', 'cheese,chicken fried,Lettuce,tomato', '15', '2.49', 'Veg', 'images/cfb893f1d604e0271b2a6a54c923a9ab.png', '5'),
(20, '19', 'Chicken Burger', 'Hamburger', 'A juicy, tender chicken patty - grilled or crispy fried - served on a toasted bun with fresh lettuce, ripe tomato, crunchy pickles, and a creamy mayo or special sauce. Perfectly balanced for a flavorful and satisfying bite every time.', 'cheese,chicken fried,Lettuce,tomato', '15', '2.49', 'Veg', 'images/cfb893f1d604e0271b2a6a54c923a9ab.png', '4'),
(21, '20', 'Double Cheese Burger', 'Hamburger', 'Two juicy grilled beef patties stacked with two layers of melted cheese, tucked inside a soft, toasted bun. Finished with fresh lettuce, ripe tomato, pickles, onions, and a signature sauce. Double the meat, double the cheese - made for true burger lovers!', 'Beyond Meat,cheese,Lettuce', '17', '5.99', 'Veg', 'images/a08090ea4134e08defc905336d62a471.png', '2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_main`
--

CREATE TABLE `order_main` (
  `id` int(11) NOT NULL,
  `order_number` varchar(100) NOT NULL,
  `order_username` varchar(100) NOT NULL,
  `order_date` varchar(100) NOT NULL,
  `order_time` varchar(100) NOT NULL,
  `order_status` varchar(100) NOT NULL,
  `order_address` varchar(500) NOT NULL,
  `user_firstname` varchar(100) NOT NULL,
  `user_lastname` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_contact` varchar(100) NOT NULL,
  `order_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_main`
--

INSERT INTO `order_main` (`id`, `order_number`, `order_username`, `order_date`, `order_time`, `order_status`, `order_address`, `user_firstname`, `user_lastname`, `user_email`, `user_contact`, `order_type`) VALUES
(1, '420027', 'ABC', '24-04-2025', '17:17:08', 'Delivered', 'ABC,XYZ', 'TAN', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(2, '398892', 'testa', '24-04-2025', '17:20:11', 'Cancelled', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(3, '358909', 'testa', '24-04-2025', '17:22:16', 'Delivered', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(4, '382413', 'testa', '24-04-2025', '17:25:04', 'Delivered', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(5, '997830', 'testa', '26-04-2025', '08:39:13', 'Delivery Failed', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(6, '957831', 'testa', '26-04-2025', '12:05:17', 'Delivered', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(7, '412695', 'testa', '26-04-2025', '13:29:28', 'Delivered', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(8, '584793', 'testa', '26-04-2025', '13:37:30', 'Delivered', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(9, '699010', 'testa', '26-04-2025', '17:03:00', 'Delivery Failed', 'ff', 'tan', 'NGUYEN', 'tandeptrai@gmail', '12345678', 'cod'),
(10, '485097', 'testa', '27-04-2025', '18:11:39', 'Cancelled', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(11, '636541', 'testa', '30-04-2025', '17:28:10', 'Delivered', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(12, '248191', 'testa', '30-04-2025', '17:32:57', 'Delivered', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(13, '347987', 'testa', '30-04-2025', '17:35:29', 'Delivery Failed', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(14, '495064', 'testa', '30-04-2025', '17:40:01', 'Delivery Failed', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(15, '505358', 'testa', '10-05-2025', '12:59:17', 'Cancelled', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(16, '159010', 'testa', '10-05-2025', '13:02:35', 'Cancelled', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(17, '612520', 'testa', '10-05-2025', '14:47:24', 'Delivered', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(18, '290129', 'testa', '15-05-2025', '15:35:10', 'Delivered', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(19, '693147', 'testa', '16-05-2025', '13:50:38', 'Preparing Order', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod'),
(20, '556245', 'testa', '16-05-2025', '13:55:04', 'Pending', 'Vo Van Kiet', 'tan', 'NGUYEN', 'tandeptrai@gmail.com', '12345678', 'cod');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_registration`
--

CREATE TABLE `user_registration` (
  `id` int(5) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_registration`
--

INSERT INTO `user_registration` (`id`, `firstname`, `lastname`, `username`, `password`, `email`, `contact`, `address`) VALUES
(2, 'tan', 'Nguyen', 'tanhaha', '1', 'tandeptrai@gmail', '123456789', 'abcdes'),
(3, 'TAN', 'NGUYEN', 'ABC', '$2y$10$y5p6cy7jTus5U/cfk1I2q.d3wIREPh0/oG6xqDXso37H1GI2y5fxW', 'tandeptrai@gmail', '12345678', 'ABC,XYZ'),
(4, 'TAN', 'NGUYEN', 'ABC1', '$2y$10$.L3e2ccwco27NqVwDZue3.dME.uqKIEkPEwgP4vOe7r', 'tandeptrai@gmail', '12345678', 'ABC,XYZ'),
(5, 'TAN', 'NGUYEN', 'test', '$2y$10$QvJQkZjtt2qcnXQ6IGN5Pu0eK72ZqUV8PakPYWjZPTI', 'tan@gmail.com', '12345678', 'ggggg'),
(7, 'haha', 'lala', 'lamxinhgai', '$2y$10$Eae2pz5.w52ZGy6bHjs4ZOMRSYN6tUf7r0ecqKVegJ9tHI7TME3YC', 'aa@gmail.com', '12345678', 'ádfg'),
(8, 'tan', 'NGUYEN', 'testa', '$2y$10$bxs6f45RhpmGC78.TDOnD.ShwsH0oPzqcid.QVJ.sATIvAnxvCP1O', 'tandeptrai@gmail.com', '12345678', 'Vo Van Kiet');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `food_categories`
--
ALTER TABLE `food_categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `food_ingredients`
--
ALTER TABLE `food_ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_main`
--
ALTER TABLE `order_main`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_registration`
--
ALTER TABLE `user_registration`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `food`
--
ALTER TABLE `food`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `food_categories`
--
ALTER TABLE `food_categories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `food_ingredients`
--
ALTER TABLE `food_ingredients`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `order_main`
--
ALTER TABLE `order_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `user_registration`
--
ALTER TABLE `user_registration`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
