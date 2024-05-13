-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 11, 2024 lúc 07:15 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `eco`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `action`
--

CREATE TABLE `action` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Đang đổ dữ liệu cho bảng `action`
--

INSERT INTO `action` (`id`, `name`, `code`) VALUES
(1, 'Quản lý người dùng', 'QLND'),
(2, 'Quản lý khách hàng', 'QLKH');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `sdt` char(10) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `dob` date NOT NULL DEFAULT current_timestamp(),
  `gender` char(10) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_per` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `email`, `sdt`, `adress`, `dob`, `gender`, `password`, `id_per`, `status`) VALUES
(1, 'administration', 'admin', 'admin@tiembanh.com', '0979485768', '123a', '2024-05-05', 'other', '$2a$10$9H2frlE9E2O8f81LAwTRJO96JTR.nMEDBX4LQ.Nd3LBDBFFHtgEiC', 1, 1),
(2, 'Nguyễn Hoàng Quân', 'nhquan123', 'nhq16022003@gmail.com', '0979485768', '123 a', '2003-02-16', 'male', '$2y$10$I2//7Zr0zZyk53aqGppJ0ec.HFTqrfxkCqSuhYFSlamT4/n0QNs.i', 4, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `customer_id`, `product_id`, `quantity`) VALUES
(1, 1, 48, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id`, `name`, `parent_id`) VALUES
(1, 'Bánh quy', NULL),
(2, 'Hộp bánh', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Đang đổ dữ liệu cho bảng `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `phone`, `address`, `password`, `created_at`) VALUES
(1, 'Nguyễn Hoàng Quân', 'nhq16022003@gmail.com', '0979485768', '150 abc', '123', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `describe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Đang đổ dữ liệu cho bảng `permission`
--

INSERT INTO `permission` (`id`, `name`, `describe`) VALUES
(1, 'administration', 'admin'),
(4, 'Xin chào', 'jklfdjaslfdsa');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `per_act`
--

CREATE TABLE `per_act` (
  `id` int(11) NOT NULL,
  `id_per` int(11) NOT NULL,
  `id_action` int(11) NOT NULL,
  `f` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Đang đổ dữ liệu cho bảng `per_act`
--

INSERT INTO `per_act` (`id`, `id_per`, `id_action`, `f`) VALUES
(0, 1, 1, 'view'),
(0, 1, 1, 'create'),
(0, 1, 1, 'edit'),
(0, 1, 1, 'delete'),
(0, 1, 2, 'view'),
(0, 1, 2, 'create'),
(0, 1, 2, 'edit'),
(0, 1, 2, 'delete'),
(0, 2, 2, 'view'),
(0, 2, 2, 'create'),
(0, 2, 2, 'edit'),
(0, 2, 2, 'delete'),
(0, 3, 2, 'create'),
(0, 3, 2, 'edit'),
(0, 3, 2, 'delete'),
(0, 4, 2, 'view'),
(0, 4, 2, 'create'),
(0, 4, 2, 'edit'),
(0, 4, 2, 'delete');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `import_price` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `image_link` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `view` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `content`, `price`, `import_price`, `discount`, `image_link`, `stock`, `created_at`, `view`) VALUES
(1, 1, 'Bread Ww Cluster', 'Destruction of Right Carpal, Open Approach', 47047, 41863, 9, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 100, NULL, NULL),
(2, 1, 'Longos - Chicken Cordon Bleu', 'Repair Right Upper Leg Skin, External Approach', 14939, 29893, 97, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 98, NULL, NULL),
(3, 2, 'Bread - Mini Hamburger Bun', 'Occlusion of Left Ulnar Artery, Percutaneous Endoscopic Approach', 46332, 37105, 55, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 76, NULL, NULL),
(4, 1, 'Jello - Assorted', 'Resection of Left Extraocular Muscle, Open Approach', 15680, 5949, 84, 'http://dummyimage.com/250x250.png/dddddd/000000', 64, NULL, NULL),
(5, 2, 'Nut - Macadamia', 'Division of Right Parietal Bone, Open Approach', 24897, 14138, 11, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 87, NULL, NULL),
(6, 2, 'Coffee - Almond Amaretto', 'Excision of Tongue, Percutaneous Approach, Diagnostic', 20515, 39275, 32, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 71, NULL, NULL),
(7, 2, 'Wheat - Soft Kernal Of Wheat', 'Dilation of Right Temporal Artery, Bifurcation, with Intraluminal Device, Percutaneous Endoscopic Approach', 21098, 8680, 59, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 66, NULL, NULL),
(8, 1, 'Cumin - Ground', 'Supplement Atrial Septum with Nonautologous Tissue Substitute, Percutaneous Approach', 17135, 18269, 12, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 0, NULL, NULL),
(9, 1, 'Veal - Leg', 'Replacement of Right Pulmonary Artery with Nonautologous Tissue Substitute, Percutaneous Endoscopic Approach', 37247, 48897, 60, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 61, NULL, NULL),
(10, 2, 'Coffee - Cafe Moreno', 'Removal of Drainage Device from Female Perineum, Percutaneous Endoscopic Approach', 16456, 7434, 16, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 62, NULL, NULL),
(11, 1, 'Soup - Campbells Tomato Ravioli', 'Repair Upper Gingiva, Open Approach', 26871, 33141, 34, 'http://dummyimage.com/250x250.png/dddddd/000000', 49, NULL, NULL),
(12, 1, 'Cheese - Camembert', 'Dilation of Small Intestine, Percutaneous Approach', 10218, 27616, 95, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 19, NULL, NULL),
(13, 1, 'Oil - Margarine', 'Dilation of Jejunum with Intraluminal Device, Via Natural or Artificial Opening Endoscopic', 10042, 17690, 69, 'http://dummyimage.com/250x250.png/dddddd/000000', 61, NULL, NULL),
(14, 1, 'Longos - Burritos', 'Removal of External Fixation Device from Left Metacarpocarpal Joint, Percutaneous Endoscopic Approach', 30075, 13853, 92, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 82, NULL, NULL),
(15, 2, 'Juice - Lime', 'Muscle Performance Treatment of Musculoskeletal System - Upper Back / Upper Extremity using Other Equipment', 31339, 16199, 63, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 3, NULL, NULL),
(16, 2, 'Bagel - 12 Grain Preslice', 'Dilation of Coronary Artery, Three Arteries, Bifurcation, with Three Drug-eluting Intraluminal Devices, Percutaneous Endoscopic Approach', 44601, 33674, 17, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 16, NULL, NULL),
(17, 1, 'Liners - Banana, Paper', 'Muscle Performance Assessment of Genitourinary System using Orthosis', 41803, 29032, 30, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 86, NULL, NULL),
(18, 1, 'Water - Perrier', 'Inspection of Skin, External Approach', 21326, 25752, 75, 'http://dummyimage.com/250x250.png/dddddd/000000', 15, NULL, NULL),
(19, 2, 'Wine - Domaine Boyar Royal', 'Replacement of Nasal Turbinate with Nonautologous Tissue Substitute, Via Natural or Artificial Opening', 43503, 5872, 50, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 63, NULL, NULL),
(20, 2, 'Longos - Grilled Chicken With', 'Extraction of Left Hip Bursa and Ligament, Percutaneous Approach', 31568, 38085, 17, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 58, NULL, NULL),
(21, 1, 'Bagel - Whole White Sesame', 'Dilation of Left Pulmonary Artery, Percutaneous Endoscopic Approach', 34542, 37577, 6, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 48, NULL, NULL),
(22, 2, 'Wine - Barbera Alba Doc 2001', 'Removal of Radioactive Element from Respiratory Tract, External Approach', 47639, 34252, 87, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 60, NULL, NULL),
(23, 2, 'Sobe - Berry Energy', 'Destruction of Minor Salivary Gland, Open Approach', 18628, 20072, 21, 'http://dummyimage.com/250x250.png/dddddd/000000', 51, NULL, NULL),
(24, 1, 'Scallops - U - 10', 'Bypass Descending Colon to Sigmoid Colon, Open Approach', 22663, 26053, 72, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 9, NULL, NULL),
(25, 1, 'Cheese - Gouda', 'Revision of External Fixation Device in Left Pelvic Bone, External Approach', 21481, 48106, 35, 'http://dummyimage.com/250x250.png/dddddd/000000', 26, NULL, NULL),
(26, 1, 'Liners - Baking Cups', 'Removal of External Fixation Device from Left Femoral Shaft, Percutaneous Endoscopic Approach', 23118, 48826, 25, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 96, NULL, NULL),
(27, 2, 'Yogurt - Assorted Pack', 'Central Nervous System, Drainage', 5337, 5511, 49, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 86, NULL, NULL),
(28, 2, 'Rootbeer', 'Bypass Right External Iliac Artery to Bilateral Femoral Arteries, Percutaneous Endoscopic Approach', 4645, 30392, 38, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 0, NULL, NULL),
(29, 1, 'Soda Water - Club Soda, 355 Ml', 'Dilation of Left Common Carotid Artery, Bifurcation, with Four or More Drug-eluting Intraluminal Devices, Open Approach', 31604, 46015, 34, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 61, NULL, NULL),
(30, 1, 'Juice - Clam, 46 Oz', 'Supplement Right 2nd Toe with Autologous Tissue Substitute, Open Approach', 19057, 25155, 33, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 40, NULL, NULL),
(31, 2, 'Salt - Sea', 'Extirpation of Matter from Right Zygomatic Bone, Open Approach', 8653, 30298, 54, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 96, NULL, NULL),
(32, 1, 'Wine - Chianti Classica Docg', 'Supplement Left Knee Joint with Liner, Patellar Surface, Open Approach', 7499, 42235, 82, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 45, NULL, NULL),
(33, 1, 'Pasta - Ravioli', 'Destruction of Ampulla of Vater, Via Natural or Artificial Opening', 21443, 42337, 28, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 20, NULL, NULL),
(34, 2, 'Napkin White - Starched', 'Revision of Drainage Device in Right Shoulder Joint, Percutaneous Endoscopic Approach', 38898, 14129, 65, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 21, NULL, NULL),
(35, 2, 'Cheese - Marble', 'Resection of Scrotum, External Approach', 14053, 20312, 57, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 61, NULL, NULL),
(36, 2, 'Wine - Red, Harrow Estates, Cab', 'Beam Radiation of Salivary Glands using Electrons, Intraoperative', 5188, 24845, 77, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 85, NULL, NULL),
(37, 1, 'Muffin Mix - Banana Nut', 'Drainage of Upper Artery, Open Approach', 49520, 23171, 87, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 7, NULL, NULL),
(38, 2, 'Cookie Trail Mix', 'Removal of Radioactive Element from Hepatobiliary Duct, Via Natural or Artificial Opening Endoscopic', 4442, 28191, 58, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 90, NULL, NULL),
(39, 1, 'Island Oasis - Ice Cream Mix', 'Supplement Left Vocal Cord with Synthetic Substitute, Open Approach', 40381, 42450, 38, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 83, NULL, NULL),
(40, 2, 'Nantucket - Pomegranate Pear', 'Insertion of Internal Fixation Device into Cervicothoracic Vertebral Joint, Percutaneous Endoscopic Approach', 2828, 3389, 88, 'http://dummyimage.com/250x250.png/dddddd/000000', 48, NULL, NULL),
(41, 1, 'Vaccum Bag - 14x20', 'Bypass Superior Mesenteric Vein to Lower Vein with Autologous Tissue Substitute, Open Approach', 24665, 16061, 97, 'http://dummyimage.com/250x250.png/cc0000/ffffff', 2, NULL, NULL),
(42, 1, 'Pants Custom Dry Clean', 'Destruction of Right Thorax Bursa and Ligament, Percutaneous Approach', 46149, 3065, 29, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 45, NULL, NULL),
(43, 1, 'Muffin - Mix - Mango Sour Cherry', 'Removal of Nonautologous Tissue Substitute from Hepatobiliary Duct, Via Natural or Artificial Opening', 19053, 30173, 76, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 81, NULL, NULL),
(44, 1, 'Rabbit - Saddles', 'Drainage of Right Upper Arm Subcutaneous Tissue and Fascia, Open Approach, Diagnostic', 48002, 25341, 95, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 1, NULL, NULL),
(45, 2, 'Tuna - Loin', 'Excision of Right Lower Lobe Bronchus, Percutaneous Endoscopic Approach, Diagnostic', 18972, 29321, 0, 'http://dummyimage.com/250x250.png/dddddd/000000', 33, NULL, NULL),
(46, 1, 'Shrimp, Dried, Small / Lb', 'Beam Radiation of Mediastinum using Heavy Particles (Protons,Ions)', 41155, 32380, 19, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 61, NULL, NULL),
(47, 1, 'Cookies - Assorted', 'Restriction of Right Neck Lymphatic with Intraluminal Device, Percutaneous Approach', 36486, 8895, 37, 'http://dummyimage.com/250x250.png/5fa2dd/ffffff', 77, NULL, NULL),
(48, 2, 'Straw - Regular', 'Destruction of Right Axillary Vein, Percutaneous Approach', 44879, 35347, 31, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 17, NULL, NULL),
(49, 1, 'Chicken - White Meat, No Tender', 'Revision of Intraluminal Device in Tracheobronchial Tree, Percutaneous Endoscopic Approach', 10141, 5299, 22, 'http://dummyimage.com/250x250.png/ff4444/ffffff', 29, NULL, NULL),
(50, 1, 'Rolled Oats', 'Measurement of Lymphatic Pressure, Percutaneous Approach', 4587, 3780, 35, 'http://dummyimage.com/250x250.png/dddddd/000000', 28, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL,
  `supplier_company_name` varchar(255) NOT NULL,
  `supplier_contact_name` varchar(255) NOT NULL,
  `supplier_phone` varchar(20) NOT NULL,
  `supplier_email` varchar(255) NOT NULL,
  `supplier_address` text NOT NULL,
  `order_date` date NOT NULL DEFAULT current_timestamp(),
  `delivery_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `shipping_method` varchar(255) NOT NULL,
  `shipping_terms` text NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_fee` int(11) NOT NULL,
  `tax` int(10) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Đang đổ dữ liệu cho bảng `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `supplier_company_name`, `supplier_contact_name`, `supplier_phone`, `supplier_email`, `supplier_address`, `order_date`, `delivery_date`, `payment_method`, `shipping_method`, `shipping_terms`, `shipping_address`, `shipping_fee`, `tax`, `notes`, `status`) VALUES
(1, 'Sài Gòn', 'Quân Nguyễne', '0979485768', 'nhquan160203@gmail.com', 'fabàâ', '2024-05-10', '2024-05-12', 'Cash On Delivery', 'Cash On Delivery', 'Shipping', '123 abc', 20000, 10, '', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchase_order_detail`
--

CREATE TABLE `purchase_order_detail` (
  `id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Đang đổ dữ liệu cho bảng `purchase_order_detail`
--

INSERT INTO `purchase_order_detail` (`id`, `po_id`, `product_id`, `quantity`, `unit_price`, `total_price`) VALUES
(13, 1, 1, 10, 1000, 10000),
(14, 1, 5, 200, 10000, 2000000),
(15, 1, 2, 21, 21000, 441000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL COMMENT 'Mã số định danh duy nhất của nhà cung cấp',
  `company_name` varchar(255) NOT NULL COMMENT 'Tên của công ty cung cấp (chuỗi, không được null)',
  `contact_name` varchar(255) DEFAULT NULL COMMENT 'Tên của người liên hệ (chuỗi)',
  `contact_email` varchar(255) DEFAULT NULL COMMENT 'Email của người liên hệ (chuỗi)',
  `contact_phone` varchar(20) DEFAULT NULL COMMENT 'Số điện thoại của người liên hệ (chuỗi)',
  `address` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ nhà cung cấp dòng 1 (chuỗi)',
  `postal_code` varchar(20) DEFAULT NULL COMMENT 'Mã bưu chính của nhà cung cấp (chuỗi)',
  `country` varchar(50) DEFAULT NULL COMMENT 'Quốc gia của nhà cung cấp (chuỗi)'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Đang đổ dữ liệu cho bảng `supplier`
--

INSERT INTO `supplier` (`id`, `company_name`, `contact_name`, `contact_email`, `contact_phone`, `address`, `postal_code`, `country`) VALUES
(1, 'Sài Gòn', 'Quân Nguyễne', 'nhquan160203@gmail.com', '0979485768', 'fabàâ', '700000', 'Vietnam'),
(2, 'XYZ Corporation', 'Jane Smith', 'jane@example.com', '987654321', '456 Le Van B, Phuong D, Quan E', '700000', 'Vietnam'),
(6, 'GHI Corporation', 'Lisa Williams', 'lisa@example.com', '369852147', '987 Nguyen Hue, Phuong L, Quan M', '700000', 'Vietnam'),
(7, 'JKL Enterprises', 'Christopher Garcia', 'chris@example.com', '258147369', '852 Tran Phu, Phuong N, Quan O', '700000', 'Vietnam'),
(8, 'MNO Ltd', 'Jessica Martinez', 'jessica@example.com', '147369852', '147 Vo Van Tan, Phuong P, Quan Q', '700000', 'Vietnam'),
(9, 'STU Company', 'Daniel Hernandez', 'daniel@example.com', '369147258', '369 Nguyen Trai, Phuong R, Quan S', '700000', 'Vietnam'),
(10, 'VWX Corporation', 'Mary Nguyen', 'mary@example.com', '951753456', '159 Ton That Thuyet, Phuong T, Quan U', '700000', 'Vietnam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `token` varchar(100) NOT NULL,
  `tokenExpire` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `pass`, `created`, `token`, `tokenExpire`) VALUES
(1, 'Dao Duy Vu', 'duyvu0810', 'duyvu08102003@gmail.com', '919c9b24962ed027fc149357212dfa0191f5325e', '2024-04-15', '', '2024-04-17 10:02:36.416672'),
(2, 'Nguyen Thuy Linh', 'thuylinh1407', 'linh@gmail.com', 'dd5fef9c1c1da1394d6d34b248c51be2ad740840', '2024-04-15', '', '2024-04-15 00:49:14.903797'),
(3, 'Nguyen Van A', 'vanA0102', 'vanA@gmail.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', '2024-04-15', '', '2024-04-15 00:53:56.204501'),
(4, 'Nguyen Van B', 'vanB0203', 'vanB@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '', '2024-04-15 09:54:28.970952'),
(5, 'Tran Thi A', 'tranthiA', 'tranA@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '', '2024-04-15 09:52:36.667716'),
(6, 'Tran Thi B', 'tranthiB', 'tranB@gmail.com', 'dd5fef9c1c1da1394d6d34b248c51be2ad740840', '2024-04-15', '', '2024-04-15 09:54:58.151791'),
(7, 'Ho Thi Tu', 'hothitu', 'thitu@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '', '2024-04-15 10:12:44.317568'),
(8, 'Dao Duc Hanh', 'duchanh', 'duchanh@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '', '2024-04-15 10:17:00.701663'),
(10, 'Tran Thi Huong', 'thihuong', 'thihuong@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '', '2024-04-15 10:19:41.863076'),
(11, 'Dao Thi Cam Tu', 'camtu01', 'camtudao8b@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '65ensoufkc', '2024-04-17 05:28:21.000000'),
(12, 'Nguyen Huy Tuan', 'huytuan', 'huytuan@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-04-15', '', '2024-04-15 10:29:44.574006'),
(13, 'Nguyen Van C', 'vanC0304', 'vanC@gmail.com', '908f704ccaadfd86a74407d234c7bde30f2744fe', '2024-04-15', '', '2024-04-15 11:10:39.155771'),
(14, 'Diep Ba Tan', 'diepbatan', 'batan@gmail.com', '645b8fbaef16c732ce0d5d1b18e228ae871a51ce', '2024-04-15', '', '2024-04-15 12:17:27.904294'),
(15, 'Diep Oanh', 'diepoanh', 'diepoanh@gmail.com', '74b6e03975602439931f5c6117d7d53441984927', '2024-04-15', '', '2024-04-15 12:20:10.378664'),
(16, 'Quang Tran', 'quangtran', 'quangtran@gmail.com', '786d671ca45846f14a6e0a46508afa09bb426123', '2024-04-16', '', '2024-04-16 12:08:03.657559'),
(17, 'Nguyễn Hoàng Quân', 'quan1602', 'nhq16022003@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', '2024-04-23', 'c4krftnla3', '2024-05-06 18:13:09.000000'),
(18, 'Nguyễn Hoàng Quân', 'quan123', 'nhquan1602@gmail.com', '69c5fcebaa65b560eaf06c3fbeb481ae44b8d618', '2024-05-06', '', '2024-05-06 15:39:23.644357');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `purchase_order_detail`
--
ALTER TABLE `purchase_order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `action`
--
ALTER TABLE `action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `purchase_order_detail`
--
ALTER TABLE `purchase_order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã số định danh duy nhất của nhà cung cấp', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Các ràng buộc cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Các ràng buộc cho bảng `purchase_order_detail`
--
ALTER TABLE `purchase_order_detail`
  ADD CONSTRAINT `purchase_order_detail_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`id`),
  ADD CONSTRAINT `purchase_order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
