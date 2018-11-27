-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 18 2018 г., 20:30
-- Версия сервера: 5.7.21-20-beget-5.7.21-20-1-log
-- Версия PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `demo`
--

--
-- Дамп данных таблицы `admin_module_article_categories`
--

INSERT INTO `admin_module_article_categories` (`id`, `title`, `image`, `order_num`, `slug`, `tree`, `lft`, `rgt`, `depth`, `time`, `status`) VALUES
(1, 'Статьи', '', 1, 'all', 1, 1, 2, 0, 0, 1);

--
-- Дамп данных таблицы `admin_module_article_items`
--

INSERT INTO `admin_module_article_items` (`id`, `category_id`, `title`, `image`, `short`, `text`, `slug`, `time`, `views`, `status`) VALUES
(1, 1, 'Что выбрать iPhone 8 или iPhone 5?', '', 'Что выбрать iPhone 8 или iPhone 5?  Выбирайте то что Вам по душе.', '<p>Что выбрать iPhone 8 или iPhone 5?  Выбирайте то что Вам по душе.</p>', 'chto_vybrat_iphone_8_ili_iphone_5', 1507114994, 20, 1),
(2, 1, 'Какой планшет лучше?', '', 'Лучший планшет, тот который: быстро работает, долго работает, удобен для пользователя.', '<p>Лучший планшет, тот который: быстро работает, долго работает, удобен для пользователя.</p>', 'kakoj_planshet_luchshe', 1507115103, 21, 1);

--
-- Дамп данных таблицы `admin_module_block`
--

INSERT INTO `admin_module_block` (`id`, `text`, `assets_css`, `assets_js`, `slug`) VALUES
(1, 'Наш магазин является эксклюзивным поставщиком Apple, Samsung, Xiaomi! Самые низкие цены на рынке!', '', '', 'bunner1');

--
-- Дамп данных таблицы `admin_module_catalog_brand`
--

INSERT INTO `admin_module_catalog_brand` (`id`, `slug`, `title`, `image`, `description`, `time`, `status`, `short`) VALUES
(1, 'samsung', 'Samsung', '/uploads/brand/samsung_0febb037cd.png', '', 0, 1, 'Мы официальный салон компании'),
(2, 'apple', 'Apple', '/uploads/brand/apple_ee23dae596.png', '', 0, 1, 'Широкий ассортимент моделей iPhone, iPad и аксессуаров'),
(3, 'xiaomi', 'Xiaomi', '/uploads/brand/xiaomi_0a19a7e7b0.png', '', 0, 1, 'Прямые поставки от производителя');

--
-- Дамп данных таблицы `admin_module_catalog_category`
--

INSERT INTO `admin_module_catalog_category` (`id`, `slug`, `title`, `image`, `description`, `fields`, `tree`, `lft`, `rgt`, `depth`, `order_num`, `time`, `status`) VALUES
(2, 'smartfony', 'Смартфоны', '', '', '[{\"name\":\"type\",\"title\":\"\\u0422\\u0438\\u043f \\u044d\\u043b\\u0435\\u043c\\u0435\\u043d\\u0442\\u0430\",\"type\":\"data\",\"options\":\"\\u0421\\u043c\\u0430\\u0440\\u0442\\u0444\\u043e\\u043d\"},{\"name\":\"os\",\"title\":\"\\u041e\\u043f\\u0435\\u0440\\u0430\\u0446\\u0438\\u043e\\u043d\\u043d\\u0430\\u044f \\u0441\\u0438\\u0441\\u0442\\u0435\\u043c\\u0430\",\"type\":\"select\",\"options\":[\"iOS 11\",\"iOS 10\",\"iOS 8\",\"Android 7.1 Nougat\",\"Xiaomi MIUI 8 \\u043d\\u0430 \\u043e\\u0441\\u043d\\u043e\\u0432\\u0435 Android\",\"Android 6.0 Marshmallow\"]},{\"name\":\"diagonal\",\"title\":\"\\u0414\\u0438\\u0430\\u0433\\u043e\\u043d\\u0430\\u043b\\u044c\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"resolution\",\"title\":\"\\u0420\\u0430\\u0437\\u0440\\u0435\\u0448\\u0435\\u043d\\u0438\\u0435 \\u044d\\u043a\\u0440\\u0430\\u043d\\u0430\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"camera\",\"title\":\"\\u041a\\u0430\\u043c\\u0435\\u0440\\u0430\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"memory\",\"title\":\"\\u041f\\u0430\\u043c\\u044f\\u0442\\u044c\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"weight\",\"title\":\"\\u0412\\u0435\\u0441\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"dimensions\",\"title\":\"\\u0420\\u0430\\u0437\\u043c\\u0435\\u0440\\u044b (\\u0428x\\u0412x\\u0422)\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"color\",\"title\":\"\\u0426\\u0432\\u0435\\u0442\",\"type\":\"select\",\"options\":[\"\\u0437\\u043e\\u043b\\u043e\\u0442\\u0438\\u0441\\u0442\\u044b\\u0439\",\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\",\"\\u0441\\u0435\\u0440\\u044b\\u0439\",\"\\u0431\\u0435\\u043b\\u044b\\u0439\",\"\\u0447\\u0435\\u0440\\u043d\\u044b\\u0439\"]}]', 1, 4, 5, 1, 1, 1507102418, 1),
(3, 'planshety', 'Планшеты', '', '', '[{\"name\":\"type\",\"title\":\"\\u0422\\u0438\\u043f \\u044d\\u043b\\u0435\\u043c\\u0435\\u043d\\u0442\\u0430\",\"type\":\"data\",\"options\":\"\\u041f\\u043b\\u0430\\u043d\\u0448\\u0435\\u0442\"},{\"name\":\"os\",\"title\":\"\\u041e\\u043f\\u0435\\u0440\\u0430\\u0446\\u0438\\u043e\\u043d\\u043d\\u0430\\u044f \\u0441\\u0438\\u0441\\u0442\\u0435\\u043c\\u0430\",\"type\":\"select\",\"options\":[\"iOS 11\",\"iOS 10\",\"iOS 8\",\"Android 7.1 Nougat\",\"Xiaomi MIUI 8 \\u043d\\u0430 \\u043e\\u0441\\u043d\\u043e\\u0432\\u0435 Android\",\"Android 6.0 Marshmallow\"]},{\"name\":\"diagonal\",\"title\":\"\\u0414\\u0438\\u0430\\u0433\\u043e\\u043d\\u0430\\u043b\\u044c\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"resolution\",\"title\":\"\\u0420\\u0430\\u0437\\u0440\\u0435\\u0448\\u0435\\u043d\\u0438\\u0435 \\u044d\\u043a\\u0440\\u0430\\u043d\\u0430\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"camera\",\"title\":\"\\u041a\\u0430\\u043c\\u0435\\u0440\\u0430\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"memory\",\"title\":\"\\u041f\\u0430\\u043c\\u044f\\u0442\\u044c\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"weight\",\"title\":\"\\u0412\\u0435\\u0441\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"dimensions\",\"title\":\"\\u0420\\u0430\\u0437\\u043c\\u0435\\u0440\\u044b (\\u0428x\\u0412x\\u0422)\",\"type\":\"string\",\"options\":\"\"},{\"name\":\"color\",\"title\":\"\\u0426\\u0432\\u0435\\u0442\",\"type\":\"select\",\"options\":[\"\\u0437\\u043e\\u043b\\u043e\\u0442\\u0438\\u0441\\u0442\\u044b\\u0439\",\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\",\"\\u0441\\u0435\\u0440\\u044b\\u0439\",\"\\u0431\\u0435\\u043b\\u044b\\u0439\",\"\\u0447\\u0435\\u0440\\u043d\\u044b\\u0439\"]}]', 1, 2, 3, 1, 1, 1507102481, 1);

--
-- Дамп данных таблицы `admin_module_catalog_group`
--

INSERT INTO `admin_module_catalog_group` (`id`, `category_id`, `brand_id`, `name`, `time`, `status`, `external_name`) VALUES
(1, 2, 2, 'iPhone 8 Plus 256GB', 1526414258, 1, NULL),
(2, 2, 2, 'iPhone 8 256GB', 1526414258, 1, NULL),
(3, 2, 2, 'iPhone 6 Plus 128GB', 1526414258, 1, NULL),
(4, 2, 2, 'iPhone X 256GB', 1526414258, 1, NULL),
(5, 2, 3, 'Mi A1 64GB', 1526414258, 1, NULL),
(6, 2, 3, 'Mi Max 32Gb', 1526414258, 1, NULL),
(7, 3, 1, 'Galaxy Tab A 10.1 SM-T585 LTE 16Gb', 1526414258, 1, NULL),
(8, 3, 2, 'iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A', 1526414258, 1, NULL);

--
-- Дамп данных таблицы `admin_module_catalog_item`
--

INSERT INTO `admin_module_catalog_item` (`id`, `slug`, `title`, `image`, `description`, `group_id`, `category_id`, `type`, `brand_id`, `name`, `article`, `available`, `price`, `base_price`, `discount`, `data`, `time`, `status`, `external_id`, `external_name`, `gift`, `new`, `order_num`, `image_alt`, `external_manual`) VALUES
(1, 'iphone_iphone_8_plus_256gb_zolotistyj', 'Смартфон Apple iPhone 8 Plus 256GB золотистый', '/uploads/photos/iphone_iphone_8_plus_256gb_zolotistyj_42f0ed3b1a.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 1, 2, 'Смартфон', 2, 'iPhone 8 Plus 256GB', 'золотистый', 1, 67800, 45000, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"5.5\\\"\",\"resolution\":\"1920x1080\",\"camera\":\"\\u0434\\u0432\\u043e\\u0439\\u043d\\u0430\\u044f, 12\\/12 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/1.8\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"202 \\u0433\",\"dimensions\":\"78.10x158.40x7.50 \\u043c\\u043c\",\"color\":\"\\u0437\\u043e\\u043b\\u043e\\u0442\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507108070, 1, 0, NULL, NULL, NULL, 1, '/uploads/photos/iphone_iphone_8_plus_256gb_zolotistyj_641cef0142.jpg', NULL),
(2, 'apple_iphone_8_plus_256gb_serebristyj', 'Смартфон Apple iPhone 8 Plus 256GB серебристый', '/uploads/photos/apple_iphone_8_plus_256gb_serebristyj_3dd9d1ecc3.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 1, 2, 'Смартфон', 2, 'iPhone 8 Plus 256GB', 'серебристый', 1, 67900, 45000, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"5.5\\\"\",\"resolution\":\"1920x1080\",\"camera\":\"\\u0434\\u0432\\u043e\\u0439\\u043d\\u0430\\u044f, 12\\/12 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/1.8\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"202 \\u0433\",\"dimensions\":\"78.10x158.40x7.50 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507108475, 1, 0, NULL, NULL, NULL, 2, '/uploads/photos/apple_iphone_8_plus_256gb_serebristyj_f5e2ce2af4.jpg', NULL),
(3, 'apple_iphone_8_plus_256gb_seryj_kosmos', 'Смартфон Apple iPhone 8 Plus 256GB серый космос', '/uploads/photos/apple_iphone_8_plus_256gb_seryj_kosmos_ccf17c4e99.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 1, 2, 'Смартфон', 2, 'iPhone 8 Plus 256GB', 'серый космос', 1, 68000, 45000, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"5.5\\\"\",\"resolution\":\"1920x1080\",\"camera\":\"\\u0434\\u0432\\u043e\\u0439\\u043d\\u0430\\u044f, 12\\/12 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/1.8\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"202 \\u0433\",\"dimensions\":\"78.10x158.40x7.50 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u044b\\u0439\"}', 1507108566, 1, 0, NULL, NULL, NULL, 3, '/uploads/photos/apple_iphone_8_plus_256gb_seryj_kosmos_cfc7646d9f.jpg', NULL),
(4, 'apple_iphone_8_256gb_zolotistyj', 'Смартфон Apple iPhone 8 256GB золотистый', '/uploads/photos/apple_iphone_8_256gb_zolotistyj_b585b90faf.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 2, 2, 'Смартфон', 2, 'iPhone 8 256GB', 'золотистый', 1, 59800, 45000, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"4.7\\\"\",\"resolution\":\"1334x750\",\"camera\":\"12 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/1.8\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"148 \\u0433\",\"dimensions\":\"67.30x138.40x7.30 \\u043c\\u043c\",\"color\":\"\\u0437\\u043e\\u043b\\u043e\\u0442\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507108648, 1, 0, NULL, NULL, NULL, 4, '/uploads/photos/apple_iphone_8_256gb_zolotistyj_dad4f6111c.jpg', NULL),
(5, 'apple_iphone_8_256gb_serebristyj', 'Смартфон Apple iPhone 8 256GB серебристый', '/uploads/photos/apple_iphone_8_256gb_serebristyj_18db1b8c0b.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 2, 2, 'Смартфон', 2, 'iPhone 8 256GB', 'серебристый', 1, 59850, 45000, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"4.7\\\"\",\"resolution\":\"1334x750\",\"camera\":\"12 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/1.8\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"148 \\u0433\",\"dimensions\":\"67.30x138.40x7.30 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507108792, 1, 0, NULL, NULL, NULL, 5, '/uploads/photos/apple_iphone_8_256gb_serebristyj_ee47d43c72.jpg', NULL),
(6, 'apple_iphone_8_256gb_seryj_kosmos', 'Смартфон Apple iPhone 8 256GB серый космос', '/uploads/photos/apple_iphone_8_256gb_seryj_kosmos_6c05e22665.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 2, 2, 'Смартфон', 2, 'iPhone 8 256GB', 'серый космос', 1, 59900, 45000, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"4.7\\\"\",\"resolution\":\"1334x750\",\"camera\":\"12 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/1.8\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"148 \\u0433\",\"dimensions\":\"67.30x138.40x7.30 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u044b\\u0439\"}', 1507108819, 1, 0, NULL, NULL, NULL, 6, '/uploads/photos/apple_iphone_8_256gb_seryj_kosmos_a17731d135.jpg', NULL),
(7, 'apple_iphone_6_plus_128gb_zolotistyj', 'Смартфон Apple iPhone 6 Plus 128GB золотистый', '/uploads/photos/apple_iphone_6_plus_128gb_zolotistyj_464b03fcb8.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 3, 2, 'Смартфон', 2, 'iPhone 6 Plus 128GB', 'золотистый', 1, 49500, 45000, NULL, '{\"os\":\"iOS 8\",\"diagonal\":\"4.7\\\"\",\"resolution\":\"1334x750\",\"camera\":\"8 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/2.2\",\"memory\":\"128 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"172 \\u0433\",\"dimensions\":\"77.80x158.10x7.10 \\u043c\\u043c\",\"color\":\"\\u0437\\u043e\\u043b\\u043e\\u0442\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507108846, 1, 0, NULL, NULL, NULL, 7, '/uploads/photos/apple_iphone_6_plus_128gb_zolotistyj_e03ef1e806.jpg', NULL),
(8, 'apple_iphone_6_plus_128gb_serebristyj', 'Смартфон Apple iPhone 6 Plus 128GB серебристый', '/uploads/photos/apple_iphone_6_plus_128gb_serebristyj_2000b55bc0.jpg', '<img src=\"/uploads/catalog/01_f7f85c9fd6.jpg\"><p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', 3, 2, 'Смартфон', 2, 'iPhone 6 Plus 128GB', 'серебристый', 1, 49600, 45000, NULL, '{\"os\":\"iOS 8\",\"diagonal\":\"4.7\\\"\",\"resolution\":\"1334x750\",\"camera\":\"8 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/2.2\",\"memory\":\"128 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"172 \\u0433\",\"dimensions\":\"77.80x158.10x7.10 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507108992, 1, 0, NULL, NULL, NULL, 8, '/uploads/photos/apple_iphone_6_plus_128gb_serebristyj_98f819e7d7.jpg', NULL),
(9, 'apple_iphone_6_plus_128gb_seryj_kosmos', 'Смартфон Apple iPhone 6 Plus 128GB серый космос', '/uploads/photos/apple_iphone_6_plus_128gb_seryj_kosmos_34bb9200f4.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, GPS, ГЛОНАСС</p>', NULL, 2, 'Смартфон', 2, 'iPhone 6 Plus 128GB', 'серый космос', 0, 49700, 45000, NULL, '{\"os\":\"iOS 8\",\"diagonal\":\"4.7\\\"\",\"resolution\":\"1334x750\",\"camera\":\"8 \\u041c\\u041f, \\u0430\\u0432\\u0442\\u043e\\u0444\\u043e\\u043a\\u0443\\u0441, F\\/2.2\",\"memory\":\"128 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"172 \\u0433\",\"dimensions\":\"77.80x158.10x7.10 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u044b\\u0439\"}', 1507109041, 1, 0, NULL, NULL, NULL, 10, NULL, NULL),
(11, 'smartfon_apple_iphone_x_256gb_serebristyj', 'Смартфон Apple iPhone X 256GB серебристый', '/uploads/photos/smartfon_apple_iphone_x_256gb_serebristyj_fe128873dc.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, <span class=\"white _q\">GPS<span class=\"q-icon\"><i> </i></span></span>, <span class=\"white _q\">ГЛОНАСС<span class=\"q-icon\"><i> </i></span></span>, A-GPS, GALILEO, функция точного определения местоположения iBeacon, QZSS</p>', 4, 2, 'Смартфон', 2, 'iPhone X 256GB', 'серебристый', 1, 91990, 91990, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"5.8\\\"\",\"resolution\":\"2436x1125\",\"camera\":\"12\\u041c\\u041f + 12\\u041c\\u041f (\\u0434\\u0432\\u043e\\u0439\\u043d\\u0430\\u044f)\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"174 \\u0433\",\"dimensions\":\"70.9x143.6x7.7 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507405019, 1, 0, NULL, NULL, NULL, 11, '/uploads/photos/smartfon_apple_iphone_x_256gb_serebristyj_de64393a26.jpg', NULL),
(12, 'smartfon_apple_iphone_x_256gb_seryj_kosmos', 'Смартфон Apple iPhone X 256GB серый космос', '/uploads/photos/smartfon_apple_iphone_x_256gb_seryj_kosmos_bf9d28bc22.jpg', '<p>3G, 4G LTE, LTE-A, Wi-Fi, Bluetooth, NFC, <span class=\"white _q\">GPS<span class=\"q-icon\"><i> </i></span></span>, <span class=\"white _q\">ГЛОНАСС<span class=\"q-icon\"><i> </i></span></span>, A-GPS, GALILEO, функция точного определения местоположения iBeacon, QZSS</p>', 4, 2, 'Смартфон', 2, 'iPhone X 256GB', 'серый космос', 1, 91990, 91990, NULL, '{\"os\":\"iOS 11\",\"diagonal\":\"5.8\\\"\",\"resolution\":\"2436x1125\",\"camera\":\"12\\u041c\\u041f + 12\\u041c\\u041f (\\u0434\\u0432\\u043e\\u0439\\u043d\\u0430\\u044f)\",\"memory\":\"256 \\u0413\\u0431, \\u0431\\u0435\\u0437 \\u0441\\u043b\\u043e\\u0442\\u0430 \\u0434\\u043b\\u044f \\u043a\\u0430\\u0440\\u0442 \\u043f\\u0430\\u043c\\u044f\\u0442\\u0438\",\"weight\":\"174 \\u0433\",\"dimensions\":\"70.9x143.6x7.7 \\u043c\\u043c\",\"color\":\"\\u0441\\u0435\\u0440\\u044b\\u0439\"}', 1507405568, 1, 0, NULL, NULL, NULL, 12, '/uploads/photos/smartfon_apple_iphone_x_256gb_seryj_kosmos_90792ce0d3.jpg', NULL),
(13, 'smartfon_xiaomi_mi_a1_64gb', 'Смартфон Xiaomi Mi A1 64GB ', '/uploads/photos/smartfon_xiaomi_mi_a1_64gb_bac1dc7ff3.jpg', '', 5, 2, 'Смартфон', 3, 'Mi A1 64GB', '', 1, 18990, 0, NULL, '{\"os\":\"Android 7.1 Nougat\",\"diagonal\":\"5.5\\\"\",\"resolution\":\"1920x1080\",\"camera\":\"12\\u041c\\u041f + 12\\u041c\\u041f (\\u0434\\u0432\\u043e\\u0439\\u043d\\u0430\\u044f)\",\"memory\":\"64\",\"weight\":\"165\",\"dimensions\":\"75.8x155.4x7.3\",\"color\":\"\\u0447\\u0435\\u0440\\u043d\\u044b\\u0439\"}', 1507405745, 1, 0, NULL, NULL, NULL, 13, '/uploads/photos/smartfon_xiaomi_mi_a1_64gb_7a01797bbd.jpg', NULL),
(16, 'smartfon_xiaomi_mi_max_32gb', 'Смартфон Xiaomi Mi Max 32Gb ', '/uploads/photos/smartfon_xiaomi_mi_max_32gb_4e926634ed.jpg', '', 6, 2, 'Смартфон', 3, 'Mi Max 32Gb', '', 1, 23900, 0, NULL, '{\"os\":\"Xiaomi MIUI 8 \\u043d\\u0430 \\u043e\\u0441\\u043d\\u043e\\u0432\\u0435 Android\",\"diagonal\":\"6.44\\\"\",\"resolution\":\"1920x1080\",\"camera\":\"16\\u041c\\u041f\",\"memory\":\"32\",\"weight\":\"203\",\"dimensions\":\"88.3x173.1x7.5\",\"color\":\"\\u0437\\u043e\\u043b\\u043e\\u0442\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507406429, 1, 0, NULL, NULL, NULL, 16, '/uploads/photos/smartfon_xiaomi_mi_max_32gb_9236aa4243.jpg', NULL),
(17, 'planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_e23753de9d.jpg', '', 7, 3, 'Планшет', 1, 'Galaxy Tab A 10.1 SM-T585 LTE 16Gb', 'черный', 1, 21990, 0, NULL, '{\"os\":\"Android 6.0 Marshmallow\",\"diagonal\":\"10.1\",\"resolution\":\"1920\\u04451200\",\"camera\":\"8\\u041c\\u041f\",\"memory\":\"16\",\"weight\":\"525\",\"dimensions\":\"155x254x8\",\"color\":\"\\u0447\\u0435\\u0440\\u043d\\u044b\\u0439\"}', 1507406684, 1, 0, NULL, NULL, NULL, 19, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_5ae4e139d7.jpg', NULL),
(18, 'planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_5295eb9516.jpg', '', 7, 3, 'Планшет', 1, 'Galaxy Tab A 10.1 SM-T585 LTE 16Gb', 'белый', 1, 21990, 0, NULL, '{\"os\":\"Android 6.0 Marshmallow\",\"diagonal\":\"10.1\",\"resolution\":\"1920\\u04451200\",\"camera\":\"8\\u041c\\u041f\",\"memory\":\"16\",\"weight\":\"525\",\"dimensions\":\"155x254x8\",\"color\":\"\\u0431\\u0435\\u043b\\u044b\\u0439\"}', 1507406880, 1, 0, NULL, NULL, NULL, 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_d6b703125c.jpg', NULL),
(19, 'planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_seryj_kosmos', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серый космос', '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_seryj_kosmos_e0af5841d4.jpg', '', 8, 3, 'Планшет', 2, 'iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A', 'серый космос', 1, 89990, 0, NULL, '{\"os\":\"iOS 10\",\"diagonal\":\"12.9\\\"\",\"resolution\":\"2732x2048\",\"camera\":\"12\\u041c\\u041f\",\"memory\":\"512\\u0413\\u0411\",\"weight\":\"692\",\"dimensions\":\"220.6x305.7x6.9\",\"color\":\"\\u0441\\u0435\\u0440\\u044b\\u0439\"}', 1507407068, 1, 0, NULL, NULL, NULL, 18, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_seryj_kosmos_7b0391f41e.jpg', NULL),
(20, 'planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_serebristyj', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серебристый', '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_serebristyj_0339f45cb6.jpg', '', 8, 3, 'Планшет', 2, 'iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A', 'серебристый', 1, 89990, 0, NULL, '{\"os\":\"iOS 10\",\"diagonal\":\"12.9\\\"\",\"resolution\":\"2732x2048\",\"camera\":\"12\\u041c\\u041f\",\"memory\":\"512\\u0413\\u0411\",\"weight\":\"692\",\"dimensions\":\"220.6x305.7x6.9\",\"color\":\"\\u0441\\u0435\\u0440\\u0435\\u0431\\u0440\\u0438\\u0441\\u0442\\u044b\\u0439\"}', 1507407318, 1, 0, NULL, NULL, NULL, 20, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_serebristyj_5d0df2f440.jpg', NULL);

--
-- Дамп данных таблицы `admin_module_catalog_item_data`
--

INSERT INTO `admin_module_catalog_item_data` (`id`, `item_id`, `name`, `value`) VALUES
(2042, 1, 'memory', '256 Гб, без слота для карт памяти'),
(2039, 1, 'diagonal', '5.5\"'),
(2040, 1, 'resolution', '1920x1080'),
(2041, 1, 'camera', 'двойная, 12/12 МП, автофокус, F/1.8'),
(2019, 4, 'weight', '148 г'),
(2050, 2, 'memory', '256 Гб, без слота для карт памяти'),
(2047, 2, 'diagonal', '5.5\"'),
(2048, 2, 'resolution', '1920x1080'),
(2049, 2, 'camera', 'двойная, 12/12 МП, автофокус, F/1.8'),
(2059, 3, 'weight', '202 г'),
(2060, 3, 'dimensions', '78.10x158.40x7.50 мм'),
(2058, 3, 'memory', '256 Гб, без слота для карт памяти'),
(2056, 3, 'resolution', '1920x1080'),
(2020, 4, 'dimensions', '67.30x138.40x7.30 мм'),
(2023, 5, 'diagonal', '4.7\"'),
(2024, 5, 'resolution', '1334x750'),
(2025, 5, 'camera', '12 МП, автофокус, F/1.8'),
(2036, 6, 'dimensions', '67.30x138.40x7.30 мм'),
(2032, 6, 'resolution', '1334x750'),
(1921, 7, 'camera', '8 МП, автофокус, F/2.2'),
(1922, 7, 'memory', '128 Гб, без слота для карт памяти'),
(1919, 7, 'diagonal', '4.7\"'),
(1920, 7, 'resolution', '1334x750'),
(1929, 8, 'camera', '8 МП, автофокус, F/2.2'),
(1930, 8, 'memory', '128 Гб, без слота для карт памяти'),
(1927, 8, 'diagonal', '4.7\"'),
(1928, 8, 'resolution', '1334x750'),
(1632, 9, 'resolution', '1334x750'),
(1633, 9, 'camera', '8 МП, автофокус, F/2.2'),
(1634, 9, 'memory', '128 Гб, без слота для карт памяти'),
(2035, 6, 'weight', '148 г'),
(2034, 6, 'memory', '256 Гб, без слота для карт памяти'),
(2033, 6, 'camera', '12 МП, автофокус, F/1.8'),
(2026, 5, 'memory', '256 Гб, без слота для карт памяти'),
(1630, 9, 'os', 'iOS 8'),
(1631, 9, 'diagonal', '4.7\"'),
(1926, 8, 'os', 'iOS 8'),
(1918, 7, 'os', 'iOS 8'),
(2067, 11, 'weight', '174 г'),
(2063, 11, 'diagonal', '5.8\"'),
(2064, 11, 'resolution', '2436x1125'),
(2065, 11, 'camera', '12МП + 12МП (двойная)'),
(2101, 12, 'color', 'серый'),
(2099, 12, 'weight', '174 г'),
(2100, 12, 'dimensions', '70.9x143.6x7.7 мм'),
(2096, 12, 'resolution', '2436x1125'),
(2075, 13, 'weight', '165'),
(2076, 13, 'dimensions', '75.8x155.4x7.3'),
(2072, 13, 'resolution', '1920x1080'),
(2073, 13, 'camera', '12МП + 12МП (двойная)'),
(2109, 20, 'color', 'серебристый'),
(2117, 19, 'color', 'серый'),
(2092, 16, 'dimensions', '88.3x173.1x7.5'),
(2093, 16, 'color', 'золотистый'),
(2090, 16, 'memory', '32'),
(2091, 16, 'weight', '203'),
(1985, 17, 'camera', '8МП'),
(1984, 17, 'resolution', '1920х1200'),
(1983, 17, 'diagonal', '10.1'),
(1994, 18, 'memory', '16'),
(1993, 18, 'camera', '8МП'),
(1992, 18, 'resolution', '1920х1200'),
(2108, 20, 'dimensions', '220.6x305.7x6.9'),
(2110, 19, 'os', 'iOS 10'),
(2111, 19, 'diagonal', '12.9\"'),
(2112, 19, 'resolution', '2732x2048'),
(2113, 19, 'camera', '12МП'),
(2105, 20, 'camera', '12МП'),
(2104, 20, 'resolution', '2732x2048'),
(1635, 9, 'weight', '172 г'),
(1636, 9, 'dimensions', '77.80x158.10x7.10 мм'),
(1637, 9, 'color', 'серый'),
(2038, 1, 'os', 'iOS 11'),
(2046, 2, 'os', 'iOS 11'),
(2057, 3, 'camera', 'двойная, 12/12 МП, автофокус, F/1.8'),
(2018, 4, 'memory', '256 Гб, без слота для карт памяти'),
(2017, 4, 'camera', '12 МП, автофокус, F/1.8'),
(2027, 5, 'weight', '148 г'),
(2022, 5, 'os', 'iOS 11'),
(2066, 11, 'memory', '256 Гб, без слота для карт памяти'),
(2062, 11, 'os', 'iOS 11'),
(2097, 12, 'camera', '12МП + 12МП (двойная)'),
(2098, 12, 'memory', '256 Гб, без слота для карт памяти'),
(2074, 13, 'memory', '64'),
(2088, 16, 'resolution', '1920x1080'),
(2089, 16, 'camera', '16МП'),
(1982, 17, 'os', 'Android 6.0 Marshmallow'),
(1990, 18, 'os', 'Android 6.0 Marshmallow'),
(1991, 18, 'diagonal', '10.1'),
(2103, 20, 'diagonal', '12.9\"'),
(1995, 18, 'weight', '525'),
(2114, 19, 'memory', '512ГБ'),
(2102, 20, 'os', 'iOS 10'),
(1986, 17, 'memory', '16'),
(2043, 1, 'weight', '202 г'),
(2044, 1, 'dimensions', '78.10x158.40x7.50 мм'),
(2051, 2, 'weight', '202 г'),
(2052, 2, 'dimensions', '78.10x158.40x7.50 мм'),
(2054, 3, 'os', 'iOS 11'),
(2055, 3, 'diagonal', '5.5\"'),
(2014, 4, 'os', 'iOS 11'),
(2015, 4, 'diagonal', '4.7\"'),
(2016, 4, 'resolution', '1334x750'),
(2028, 5, 'dimensions', '67.30x138.40x7.30 мм'),
(2030, 6, 'os', 'iOS 11'),
(2031, 6, 'diagonal', '4.7\"'),
(1923, 7, 'weight', '172 г'),
(1924, 7, 'dimensions', '77.80x158.10x7.10 мм'),
(1925, 7, 'color', 'золотистый'),
(1931, 8, 'weight', '172 г'),
(1932, 8, 'dimensions', '77.80x158.10x7.10 мм'),
(1933, 8, 'color', 'серебристый'),
(2068, 11, 'dimensions', '70.9x143.6x7.7 мм'),
(2094, 12, 'os', 'iOS 11'),
(2095, 12, 'diagonal', '5.8\"'),
(2070, 13, 'os', 'Android 7.1 Nougat'),
(2071, 13, 'diagonal', '5.5\"'),
(2086, 16, 'os', 'Xiaomi MIUI 8 на основе Android'),
(2087, 16, 'diagonal', '6.44\"'),
(1987, 17, 'weight', '525'),
(1988, 17, 'dimensions', '155x254x8'),
(1989, 17, 'color', 'черный'),
(1996, 18, 'dimensions', '155x254x8'),
(1997, 18, 'color', 'белый'),
(2115, 19, 'weight', '692'),
(2116, 19, 'dimensions', '220.6x305.7x6.9'),
(2106, 20, 'memory', '512ГБ'),
(2107, 20, 'weight', '692'),
(2021, 4, 'color', 'золотистый'),
(2029, 5, 'color', 'серебристый'),
(2037, 6, 'color', 'серый'),
(2045, 1, 'color', 'золотистый'),
(2053, 2, 'color', 'серебристый'),
(2061, 3, 'color', 'серый'),
(2069, 11, 'color', 'серебристый'),
(2077, 13, 'color', 'черный');

--
-- Дамп данных таблицы `admin_module_comment_rating`
--

INSERT INTO `admin_module_comment_rating` (`id`, `entity`, `entityId`, `session`, `value`, `createdBy`, `updatedBy`, `createdAt`, `updatedAt`) VALUES
(1, 'ef6b18b6', 11, '64168a96b0463b2c99f23c9f039c320b5add08c81f1f61.95585333', 4, 2, 2, 1524435144, 1524435144),
(2, 'ef6b18b6', 16, '64168a96b0463b2c99f23c9f039c320b5add08c81f1f61.95585333', 5, 2, 2, 1524435150, 1524435150),
(3, 'ef6b18b6', 18, '64168a96b0463b2c99f23c9f039c320b5add08c81f1f61.95585333', 5, 2, 2, 1524435283, 1524435283);

--
-- Дамп данных таблицы `admin_module_delivery`
--

INSERT INTO `admin_module_delivery` (`id`, `title`, `description`, `price`, `free_from`, `available_from`, `need_address`, `order_num`, `status`, `slug`) VALUES
(1, 'Доставка курьером', '', 300, 5000, 0, 1, 1, 1, 'dostavka_kurerom'),
(2, 'Самовывоз', '', 0, 0, 0, 0, 2, 1, 'samovyvoz');

--
-- Дамп данных таблицы `admin_module_delivery_payment`
--

INSERT INTO `admin_module_delivery_payment` (`delivery_id`, `payment_id`) VALUES
(1, 3),
(1, 1),
(2, 3),
(2, 2);

--
-- Дамп данных таблицы `admin_module_faq`
--

INSERT INTO `admin_module_faq` (`id`, `question`, `answer`, `order_num`, `status`) VALUES
(1, 'Как осуществить покупку?', 'Очень просто! Добавляете нужный товар в корзину, указываете имя и телефон, нажимаете кнопку \"Оформить заказ\"', 1, 1);

--
-- Дамп данных таблицы `admin_module_files`
--

INSERT INTO `admin_module_files` (`id`, `title`, `file`, `size`, `slug`, `downloads`, `time`, `order_num`) VALUES
(1, 'Прайс-лист', '/uploads/files/katalog-demoyiistudioru-20171012.xlsx', 10017, 'price-list', 18, 1507830269, 1),
(2, '3eer', '/uploads/files/1475053605182078426.jpg', 13102, '3eer', 0, 1525813575, 2);

--
-- Дамп данных таблицы `admin_module_gallery_categories`
--

INSERT INTO `admin_module_gallery_categories` (`id`, `title`, `image`, `slug`, `tree`, `lft`, `rgt`, `depth`, `order_num`, `time`, `status`) VALUES
(1, 'Смартфоны', '', 'smartfony', 1, 1, 2, 0, 1, 0, 1);

--
-- Дамп данных таблицы `admin_module_news`
--

INSERT INTO `admin_module_news` (`id`, `title`, `image`, `short`, `text`, `slug`, `time`, `views`, `status`) VALUES
(1, 'В продажу поступили Apple iPhone X!', '', 'В продажу поступили Apple iPhone X! Лучшие цены!', '<p>В продажу поступили Apple iPhone 8 Plus! Лучшие цены!</p>', 'v_prodazhu_postupili_apple_iphone_8_plus', 1507109493, 29, 1),
(2, 'Бесплатная доставка при покупке от 5000 руб!', '', 'Бесплатная доставка при покупке от 5000 руб! Быстро и бесплатно доставим при покупке свыше 5000 руб.', '<p>Бесплатная доставка при покупке от 5000 руб! Быстро и бесплатно доставим при покупке свыше 5000 руб.</p>', 'besplatnaya_dostavka_pri_pokupke_ot_5000_rub', 1506936780, 31, 1);

--
-- Дамп данных таблицы `admin_module_pages`
--

INSERT INTO `admin_module_pages` (`id`, `title`, `text`, `slug`) VALUES
(1, 'Новости', '', 'page-news'),
(2, 'Контакты', '', 'page-contact'),
(3, 'Корзина', '', 'page-shopcart'),
(4, 'Часто задаваемые вопросы (FAQ)', '', 'page-faq'),
(5, 'Галлереи', '', 'page-gallery'),
(6, 'Yii Studio быстрая и удобная система управления контентом на основе Yii2: интернет-магазин, блог, сайт-визитка, онлайн-сервис', '<p>Это демо-магазин развернутый с использованием Yii Studio. Управление каталогом товаров, службы доставки, способы оплаты, комментарии и оценки, SEO, корзина и оформление заказов, покупка в один клик, произвольные HTML-блоки, управление страницами, новостями, генерация карты сайта, обратная связь, вопросы и ответы (модуль FAQ), фотогалерея, модуль акций, скидок, модуль экспорта/импортаExcel, YML, управление файлами, модуль рассылки, гостевая книга, управление пользователями и их разрешениями (RBAC)...</p>', 'page-shop'),
(7, 'Пример страницы', '<p>Пример страницы</p>', 'page-opt');

--
-- Дамп данных таблицы `admin_module_payment`
--

INSERT INTO `admin_module_payment` (`id`, `title`, `description`, `available_to`, `is_manual`, `order_num`, `status`, `class`, `data`, `slug`) VALUES
(1, 'Оплата курьеру', '', 0, 1, 1, 1, 'admin\\modules\\payment\\payment_systems\\Manual', '[]', 'oplata_kureru'),
(2, 'Оплата при самовывозе', '', 0, 1, 2, 1, 'admin\\modules\\payment\\payment_systems\\Manual', 'null', 'oplata_pri_samovyvoze'),
(3, 'Онлайн-оплата', '', 0, 0, 3, 1, 'admin\\modules\\payment\\payment_systems\\Manual', 'null', 'onlajn_oplata');

--
-- Дамп данных таблицы `admin_module_payment_checkout`
--

INSERT INTO `admin_module_payment_checkout` (`id`, `time`, `status`, `payment_id`, `payment_title`, `order_id`, `description`, `request`) VALUES
(1, 1525186109, 2, NULL, NULL, 9, 'Статус оплаты заказа изменен на \"Оплачен\"', '{\"_csrf-app_demo\":\"G6X4Il-hjxM1ZQ_Gu5YOzDaeZ0ImL4aWCFFGwH5DkKs8fxJ77n5jVpwqzgXDE9udKWL8PUceTadGcqeNrLZjfQ==\",\"paid_status\":\"1\"}');

--
-- Дамп данных таблицы `admin_module_shopcart_goods`
--

INSERT INTO `admin_module_shopcart_goods` (`id`, `order_id`, `item_id`, `count`, `options`, `price`, `discount`) VALUES
(2, 2, 9, 1, '', 49700, 0),
(3, 3, 3, 1, '', 68000, 0),
(4, 4, 12, 1, '', 91990, 0),
(5, 5, 14, 1, '', 14990, 0),
(6, 6, 12, 1, '', 91990, 0),
(7, 7, 14, 1, '', 14990, 0),
(8, 8, 12, 2, '', 91990, 0),
(9, 9, 13, 1, '', 18990, 0);

--
-- Дамп данных таблицы `admin_module_shopcart_orders`
--

INSERT INTO `admin_module_shopcart_orders` (`id`, `user_id`, `name`, `address`, `phone`, `email`, `comment`, `remark`, `delivery_id`, `delivery_cost`, `delivery_details`, `payment_id`, `payment_details`, `paid_status`, `paid_time`, `paid_details`, `discount`, `data`, `access_token`, `ip`, `time`, `new`, `status`) VALUES
(2, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', '4FdzYAsWtiivPHct7rnlajIEcyhHuC1M', '127.0.0.1', 1507109448, 0, 0),
(3, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', 'HpjOcAH94SgvGd9AHv8Wx_sy9_Tux0Fq', '109.252.76.147', 1507322774, 0, 0),
(4, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', '3bU0X14gWWtziU_WpgypGfP4-ryfWKnl', '109.252.76.147', 1507745524, 0, 0),
(5, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', '6-KJ5iv1Mdv96BkpEegKJMwVG4IAK8YH', '46.149.86.175', 1520325817, 0, 0),
(6, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', 'ie7CM8JkNwlmd_9NDTjmO2YRYNWtbkC9', '62.117.74.115', 1520341099, 0, 0),
(7, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', 'Afmqa6fFj9csfS27MNcC_9BJAK4BS7KB', '185.43.86.90', 1520521214, 0, 0),
(8, NULL, '', '', '', '', '', '', NULL, 0, NULL, NULL, NULL, 0, 0, NULL, 0, 'null', 'H_yOMHksuwlhqrqVEycD4OwV2DzSktFr', '5.59.32.41', 1524919519, 0, 0),
(9, 1, 'Алексей', 'г. Москва, ул. Строителей, 1', '8(495)123-45-67', 'admin@yiistudio.ru', 'Нужна быстрая доставка', '', 1, 0, 'Доставка курьером', 3, 'Онлайн-оплата', 1, 1525186109, 'Статус оплаты заказа изменен в панели управления на \"Оплачен\"', 0, '[]', 'l44OftThZHxnLbrVJsgZutFzwW8Qbhz8', '31.135.211.1', 1525185965, 0, 7);

--
-- Дамп данных таблицы `admin_module_sitemap`
--

INSERT INTO `admin_module_sitemap` (`id`, `class`, `data`) VALUES
(1, '\\admin\\modules\\catalog\\models\\Item', '{\"priority\":\"0.8\"}');

--
-- Дамп данных таблицы `admin_module_yml_export`
--

INSERT INTO `admin_module_yml_export` (`id`, `title`, `data`) VALUES
(1, 'Экспорт на Яндекс.Маркет', '{\"brands\":\"\",\"class\":\"admin\\\\modules\\\\yml\\\\external_export\\\\Shop\",\"count\":\"0\",\"categories\":\"\",\"to_excel\":\"1\",\"status\":\"1\",\"shop_name\":\"demo.yiistudio.ru\",\"shop_company\":\"demo.yiistudio.ru\",\"shop_url\":\"demo.yiistudio.ru\",\"shop_agency\":\"\",\"shop_email\":\"\",\"shop_cpa\":\"0\",\"all_delivery_options_cost\":\"0\",\"all_delivery_options_days\":\"1-2\",\"delivery_free_from\":\"5000\",\"delivery_options_cost\":\"300\",\"delivery_options_days\":\"1-2\"}');

--
-- Дамп данных таблицы `admin_photos`
--

INSERT INTO `admin_photos` (`id`, `class`, `item_id`, `image`, `description`, `order_num`) VALUES
(1, 'admin\\modules\\catalog\\models\\Item', 3, '/uploads/photos/apple_iphone_8_plus_256gb_seryj_kosmos_ccf17c4e99.jpg', 'Смартфон Apple iPhone 8 Plus 256GB серый космос', 1),
(3, 'admin\\modules\\catalog\\models\\Item', 3, '/uploads/photos/apple_iphone_8_plus_256gb_seryj_kosmos_cfc7646d9f.jpg', 'Смартфон Apple iPhone 8 Plus 256GB серый космос', 3),
(4, 'admin\\modules\\catalog\\models\\Item', 3, '/uploads/photos/apple_iphone_8_plus_256gb_seryj_kosmos_e83c8e6cbd.jpg', 'Смартфон Apple iPhone 8 Plus 256GB серый космос', 4),
(5, 'admin\\modules\\catalog\\models\\Item', 2, '/uploads/photos/apple_iphone_8_plus_256gb_serebristyj_3dd9d1ecc3.jpg', 'Смартфон Apple iPhone 8 Plus 256GB серебристый', 5),
(7, 'admin\\modules\\catalog\\models\\Item', 2, '/uploads/photos/apple_iphone_8_plus_256gb_serebristyj_f5e2ce2af4.jpg', 'Смартфон Apple iPhone 8 Plus 256GB серебристый', 7),
(8, 'admin\\modules\\catalog\\models\\Item', 2, '/uploads/photos/apple_iphone_8_plus_256gb_serebristyj_249d596a74.jpg', 'Смартфон Apple iPhone 8 Plus 256GB серебристый', 8),
(9, 'admin\\modules\\catalog\\models\\Item', 1, '/uploads/photos/iphone_iphone_8_plus_256gb_zolotistyj_42f0ed3b1a.jpg', 'Смартфон Apple iPhone 8 Plus 256GB золотистый', 9),
(11, 'admin\\modules\\catalog\\models\\Item', 1, '/uploads/photos/iphone_iphone_8_plus_256gb_zolotistyj_641cef0142.jpg', 'Смартфон Apple iPhone 8 Plus 256GB золотистый', 11),
(12, 'admin\\modules\\catalog\\models\\Item', 1, '/uploads/photos/iphone_iphone_8_plus_256gb_zolotistyj_c03b51b17f.jpg', 'Смартфон Apple iPhone 8 Plus 256GB золотистый', 12),
(13, 'admin\\modules\\catalog\\models\\Item', 6, '/uploads/photos/apple_iphone_8_256gb_seryj_kosmos_6c05e22665.jpg', 'Смартфон Apple iPhone 8 256GB серый космос', 13),
(15, 'admin\\modules\\catalog\\models\\Item', 6, '/uploads/photos/apple_iphone_8_256gb_seryj_kosmos_a17731d135.jpg', 'Смартфон Apple iPhone 8 256GB серый космос', 15),
(16, 'admin\\modules\\catalog\\models\\Item', 6, '/uploads/photos/apple_iphone_8_256gb_seryj_kosmos_2d6684873b.jpg', 'Смартфон Apple iPhone 8 256GB серый космос', 16),
(17, 'admin\\modules\\catalog\\models\\Item', 5, '/uploads/photos/apple_iphone_8_256gb_serebristyj_18db1b8c0b.jpg', 'Смартфон Apple iPhone 8 256GB серебристый', 17),
(20, 'admin\\modules\\catalog\\models\\Item', 5, '/uploads/photos/apple_iphone_8_256gb_serebristyj_ee47d43c72.jpg', 'Смартфон Apple iPhone 8 256GB серебристый', 19),
(21, 'admin\\modules\\catalog\\models\\Item', 5, '/uploads/photos/apple_iphone_8_256gb_serebristyj_c927da9ae9.jpg', 'Смартфон Apple iPhone 8 256GB серебристый', 20),
(22, 'admin\\modules\\catalog\\models\\Item', 4, '/uploads/photos/apple_iphone_8_256gb_zolotistyj_b585b90faf.jpg', 'Смартфон Apple iPhone 8 256GB золотистый', 21),
(24, 'admin\\modules\\catalog\\models\\Item', 4, '/uploads/photos/apple_iphone_8_256gb_zolotistyj_dad4f6111c.jpg', 'Смартфон Apple iPhone 8 256GB золотистый', 23),
(25, 'admin\\modules\\catalog\\models\\Item', 4, '/uploads/photos/apple_iphone_8_256gb_zolotistyj_cbb3633470.jpg', 'Смартфон Apple iPhone 8 256GB золотистый', 24),
(26, 'admin\\modules\\catalog\\models\\Item', 9, '/uploads/photos/apple_iphone_6_plus_128gb_seryj_kosmos_34bb9200f4.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серый космос', 25),
(27, 'admin\\modules\\catalog\\models\\Item', 9, '/uploads/photos/apple_iphone_6_plus_128gb_seryj_kosmos_f17800d1f8.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серый космос', 26),
(28, 'admin\\modules\\catalog\\models\\Item', 9, '/uploads/photos/apple_iphone_6_plus_128gb_seryj_kosmos_45a1c2f981.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серый космос', 27),
(29, 'admin\\modules\\catalog\\models\\Item', 9, '/uploads/photos/apple_iphone_6_plus_128gb_seryj_kosmos_ccd9c6847e.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серый космос', 28),
(30, 'admin\\modules\\catalog\\models\\Item', 8, '/uploads/photos/apple_iphone_6_plus_128gb_serebristyj_2000b55bc0.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серебристый', 29),
(31, 'admin\\modules\\catalog\\models\\Item', 8, '/uploads/photos/apple_iphone_6_plus_128gb_serebristyj_e51eebdeb9.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серебристый', 32),
(32, 'admin\\modules\\catalog\\models\\Item', 8, '/uploads/photos/apple_iphone_6_plus_128gb_serebristyj_98f819e7d7.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серебристый', 30),
(33, 'admin\\modules\\catalog\\models\\Item', 8, '/uploads/photos/apple_iphone_6_plus_128gb_serebristyj_42b652ecb5.jpg', 'Смартфон Apple iPhone 6 Plus 128GB серебристый', 31),
(34, 'admin\\modules\\catalog\\models\\Item', 7, '/uploads/photos/apple_iphone_6_plus_128gb_zolotistyj_464b03fcb8.jpg', 'Смартфон Apple iPhone 6 Plus 128GB золотистый', 33),
(35, 'admin\\modules\\catalog\\models\\Item', 7, '/uploads/photos/apple_iphone_6_plus_128gb_zolotistyj_e03ef1e806.jpg', 'Смартфон Apple iPhone 6 Plus 128GB золотистый', 34),
(36, 'admin\\modules\\catalog\\models\\Item', 7, '/uploads/photos/apple_iphone_6_plus_128gb_zolotistyj_6b1336aa25.jpg', 'Смартфон Apple iPhone 6 Plus 128GB золотистый', 35),
(37, 'admin\\modules\\catalog\\models\\Item', 7, '/uploads/photos/apple_iphone_6_plus_128gb_zolotistyj_d628af401c.jpg', 'Смартфон Apple iPhone 6 Plus 128GB золотистый', 36),
(62, 'admin\\modules\\catalog\\models\\Item', 12, '/uploads/photos/smartfon_apple_iphone_x_256gb_seryj_kosmos_bf9d28bc22.jpg', 'Смартфон Apple iPhone X 256GB серый космос', 54),
(64, 'admin\\modules\\catalog\\models\\Item', 12, '/uploads/photos/smartfon_apple_iphone_x_256gb_seryj_kosmos_90792ce0d3.jpg', 'Смартфон Apple iPhone X 256GB серый космос', 56),
(42, 'admin\\modules\\gallery\\models\\Category', 1, '/uploads/photos/smartfony_053c57df49.jpg', 'Смартфоны', 37),
(48, 'admin\\modules\\gallery\\models\\Category', 1, '/uploads/photos/smartfony_ada5105153.jpg', 'Смартфоны', 43),
(49, 'admin\\modules\\gallery\\models\\Category', 1, '/uploads/photos/smartfony_ea8d66fd3c.jpg', 'Смартфоны', 44),
(60, 'admin\\modules\\catalog\\models\\Item', 11, '/uploads/photos/smartfon_apple_iphone_x_256gb_serebristyj_de64393a26.jpg', 'Смартфон Apple iPhone X 256GB серебристый', 52),
(61, 'admin\\modules\\catalog\\models\\Item', 11, '/uploads/photos/smartfon_apple_iphone_x_256gb_serebristyj_d67fb2f8c6.jpg', 'Смартфон Apple iPhone X 256GB серебристый', 53),
(54, 'admin\\modules\\gallery\\models\\Category', 1, '/uploads/photos/smartfony_c0715f20e0.jpg', 'Смартфоны', 49),
(58, 'admin\\modules\\catalog\\models\\Item', 11, '/uploads/photos/smartfon_apple_iphone_x_256gb_serebristyj_fe128873dc.jpg', 'Смартфон Apple iPhone X 256GB серебристый', 50),
(65, 'admin\\modules\\catalog\\models\\Item', 12, '/uploads/photos/smartfon_apple_iphone_x_256gb_seryj_kosmos_e75d478d5e.jpg', 'Смартфон Apple iPhone X 256GB серый космос', 57),
(66, 'admin\\modules\\catalog\\models\\Item', 13, '/uploads/photos/smartfon_xiaomi_mi_a1_64gb_bac1dc7ff3.jpg', 'Смартфон Xiaomi Mi A1 64GB', 58),
(68, 'admin\\modules\\catalog\\models\\Item', 13, '/uploads/photos/smartfon_xiaomi_mi_a1_64gb_7a01797bbd.jpg', 'Смартфон Xiaomi Mi A1 64GB', 60),
(69, 'admin\\modules\\catalog\\models\\Item', 13, '/uploads/photos/smartfon_xiaomi_mi_a1_64gb_91737ae31d.jpg', 'Смартфон Xiaomi Mi A1 64GB', 61),
(73, 'admin\\modules\\catalog\\models\\Item', 16, '/uploads/photos/smartfon_xiaomi_mi_max_32gb_4e926634ed.jpg', 'Смартфон Xiaomi Mi Max 32Gb', 65),
(75, 'admin\\modules\\catalog\\models\\Item', 16, '/uploads/photos/smartfon_xiaomi_mi_max_32gb_9236aa4243.jpg', 'Смартфон Xiaomi Mi Max 32Gb', 67),
(76, 'admin\\modules\\catalog\\models\\Item', 16, '/uploads/photos/smartfon_xiaomi_mi_max_32gb_d99721914c.jpg', 'Смартфон Xiaomi Mi Max 32Gb', 68),
(77, 'admin\\modules\\catalog\\models\\Item', 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_e23753de9d.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', 69),
(78, 'admin\\modules\\catalog\\models\\Item', 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_62b6f9bce4.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', 73),
(79, 'admin\\modules\\catalog\\models\\Item', 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_5ae4e139d7.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', 70),
(80, 'admin\\modules\\catalog\\models\\Item', 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_48f59e3a41.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', 74),
(81, 'admin\\modules\\catalog\\models\\Item', 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_b491ff2fcd.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', 71),
(82, 'admin\\modules\\catalog\\models\\Item', 17, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_chernyj_064a2d0d3b.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb черный', 72),
(83, 'admin\\modules\\catalog\\models\\Item', 18, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_fff3dc77e9.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', 79),
(84, 'admin\\modules\\catalog\\models\\Item', 18, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_241233d709.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', 80),
(85, 'admin\\modules\\catalog\\models\\Item', 18, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_9109e8a3f8.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', 77),
(86, 'admin\\modules\\catalog\\models\\Item', 18, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_d6b703125c.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', 76),
(87, 'admin\\modules\\catalog\\models\\Item', 18, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_5295eb9516.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', 75),
(88, 'admin\\modules\\catalog\\models\\Item', 18, '/uploads/photos/planshet_samsung_galaxy_tab_a_101_sm_t585_lte_16gb_belyj_cd9052d53c.jpg', 'Планшет Samsung Galaxy Tab A 10.1 SM-T585 LTE 16Gb белый', 78),
(89, 'admin\\modules\\catalog\\models\\Item', 19, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_seryj_kosmos_e0af5841d4.jpg', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серый космос', 81),
(91, 'admin\\modules\\catalog\\models\\Item', 19, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_seryj_kosmos_7b0391f41e.jpg', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серый космос', 83),
(92, 'admin\\modules\\catalog\\models\\Item', 19, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_seryj_kosmos_7dafac6e64.jpg', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серый космос', 84),
(93, 'admin\\modules\\catalog\\models\\Item', 20, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_serebristyj_0339f45cb6.jpg', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серебристый', 85),
(95, 'admin\\modules\\catalog\\models\\Item', 20, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_serebristyj_5d0df2f440.jpg', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серебристый', 87),
(96, 'admin\\modules\\catalog\\models\\Item', 20, '/uploads/photos/planshet_apple_ipad_pro_129_wi_fi__cellular_512gb_mplj2rua_serebristyj_c192904348.jpg', 'Планшет Apple iPad Pro 12.9 Wi-Fi + Cellular 512GB MPLJ2RU/A серебристый', 88);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
