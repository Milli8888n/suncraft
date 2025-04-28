-- Script thiết lập cơ sở dữ liệu cho Suncraft
-- Phiên bản: 1.0.0
-- Ngày: 2023-08-20
-- Đã điều chỉnh cho Namecheap

-- CREATE DATABASE đã bị xóa - database đã được tạo trong cPanel

-- Sử dụng database
USE `suncpsdo_suncraft_db`;

-- Xóa bảng cũ nếu đã tồn tại
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `post_categories`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `product_categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `settings`;

-- Tạo bảng Users
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `full_name` VARCHAR(100),
  `role` ENUM('admin', 'editor', 'user') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng Categories (cho bài viết)
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `parent_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng Posts
CREATE TABLE `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT,
  `excerpt` TEXT,
  `featured_image` VARCHAR(255),
  `status` ENUM('published', 'draft', 'trash') DEFAULT 'draft',
  `author_id` INT NOT NULL,
  `views` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` TIMESTAMP NULL,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng trung gian cho mối quan hệ nhiều-nhiều giữa Posts và Categories
CREATE TABLE `post_categories` (
  `post_id` INT,
  `category_id` INT,
  PRIMARY KEY (`post_id`, `category_id`),
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng Comments
CREATE TABLE `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `author_name` VARCHAR(100) NOT NULL,
  `author_email` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('approved', 'pending', 'spam') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`parent_id`) REFERENCES `comments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng Product Categories
CREATE TABLE `product_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `parent_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `product_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng Products
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` LONGTEXT,
  `short_description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `sale_price` DECIMAL(10,2) DEFAULT NULL,
  `sku` VARCHAR(50) UNIQUE,
  `stock_quantity` INT DEFAULT 0,
  `featured_image` VARCHAR(255),
  `gallery` TEXT,
  `category_id` INT,
  `status` ENUM('published', 'draft', 'trash') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `product_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng Settings
CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` LONGTEXT,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn dữ liệu mẫu cho users
INSERT INTO `users` (`username`, `password`, `email`, `full_name`, `role`) VALUES
('admin', '$2y$10$3YtqFl7Zfb5yxJ9stBF8i.zVY4XUEFHnrcMYRUyvzYpRXBP/eeUwe', 'admin@suncraft.com', 'Administrator', 'admin'),
('editor', '$2y$10$Yh9uSftCdJd3JRlmXhNnw.vVDm46C40e4mAJW4QzD.s4VT7CNPZLC', 'editor@suncraft.com', 'Editor User', 'editor');
-- Mật khẩu: admin123 và editor123 (đã được hash với bcrypt)

-- Chèn dữ liệu mẫu cho categories
INSERT INTO `categories` (`name`, `slug`, `description`) VALUES
('Tin tức', 'tin-tuc', 'Tin tức mới nhất về Suncraft'),
('Hướng dẫn', 'huong-dan', 'Các bài hướng dẫn sử dụng sản phẩm'),
('Blog', 'blog', 'Bài viết blog');

-- Chèn dữ liệu mẫu cho posts
INSERT INTO `posts` (`title`, `slug`, `content`, `excerpt`, `status`, `author_id`, `published_at`) VALUES
('Chào mừng đến với Suncraft', 'chao-mung-den-voi-suncraft', '<p>Chào mừng quý khách đến với trang web mới của chúng tôi. Chúng tôi sẽ cập nhật thông tin mới nhất về sản phẩm và dịch vụ.</p>', 'Bài viết chào mừng đến với trang web mới của Suncraft', 'published', 1, NOW()),
('Hướng dẫn sử dụng sản phẩm', 'huong-dan-su-dung-san-pham', '<p>Đây là hướng dẫn chi tiết cách sử dụng các sản phẩm của chúng tôi một cách hiệu quả nhất.</p>', 'Hướng dẫn sử dụng các sản phẩm Suncraft', 'published', 1, NOW());

-- Gán categories cho posts
INSERT INTO `post_categories` (`post_id`, `category_id`) VALUES
(1, 1), -- Post 'Chào mừng' thuộc category 'Tin tức'
(2, 2); -- Post 'Hướng dẫn' thuộc category 'Hướng dẫn'

-- Chèn dữ liệu mẫu cho product_categories
INSERT INTO `product_categories` (`name`, `slug`, `description`) VALUES
('Sản phẩm mới', 'san-pham-moi', 'Các sản phẩm mới nhất'),
('Khuyến mãi', 'khuyen-mai', 'Sản phẩm đang được khuyến mãi');

-- Chèn dữ liệu mẫu cho products
INSERT INTO `products` (`name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `sku`, `stock_quantity`, `category_id`, `status`) VALUES
('Sản phẩm mẫu 1', 'san-pham-mau-1', '<p>Chi tiết về sản phẩm mẫu 1 của chúng tôi.</p>', 'Mô tả ngắn về sản phẩm 1', 1000000, 900000, 'SP001', 10, 1, 'published'),
('Sản phẩm mẫu 2', 'san-pham-mau-2', '<p>Chi tiết về sản phẩm mẫu 2 của chúng tôi.</p>', 'Mô tả ngắn về sản phẩm 2', 2000000, NULL, 'SP002', 5, 2, 'published');

-- Chèn dữ liệu mẫu cho settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_title', 'Suncraft - Trang web chính thức'),
('site_description', 'Trang web chính thức của công ty Suncraft'),
('contact_email', 'contact@suncraft.com'),
('contact_phone', '0123456789'),
('contact_address', 'Số 1, Đường ABC, Quận XYZ, TP. HCM'),
('logo_url', 'assets/images/logo.png'),
('facebook_url', 'https://facebook.com/suncraft'),
('twitter_url', 'https://twitter.com/suncraft'),
('instagram_url', 'https://instagram.com/suncraft');

-- Tạo index để tăng tốc truy vấn
CREATE INDEX idx_posts_slug ON posts(slug);
CREATE INDEX idx_posts_author ON posts(author_id);
CREATE INDEX idx_posts_status ON posts(status);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_status ON products(status);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_product_categories_slug ON product_categories(slug); 