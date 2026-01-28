-- AllieDEals E-Commerce Database Schema
-- MySQL Database Setup for XAMPP

-- Drop database if exists (CAUTION: This will delete all data)
-- DROP DATABASE IF EXISTS alliedeals;

-- Create database
CREATE DATABASE IF NOT EXISTS alliedeals CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alliedeals;

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- =====================================================
-- USER ADDRESSES TABLE
-- =====================================================
CREATE TABLE user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    country VARCHAR(50) DEFAULT 'India',
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- =====================================================
-- ADMINS TABLE
-- =====================================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'superadmin') DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- =====================================================
-- CATEGORIES TABLE
-- =====================================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    parent_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id)
) ENGINE=InnoDB;

-- =====================================================
-- PRODUCTS TABLE
-- =====================================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('tshirts', 'gadgets') NOT NULL,
    subcategory VARCHAR(100),
    brand VARCHAR(100),
    base_price DECIMAL(10,2) NOT NULL,
    images TEXT,  -- JSON array of image URLs
    featured TINYINT(1) DEFAULT 0,
    trending TINYINT(1) DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0,
    review_count INT DEFAULT 0,
    tags VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_featured (featured),
    INDEX idx_trending (trending),
    FULLTEXT idx_search (name, description)
) ENGINE=InnoDB;

-- =====================================================
-- PRODUCT VARIANTS TABLE
-- =====================================================
CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size VARCHAR(10),
    color VARCHAR(50),
    color_hex VARCHAR(7) DEFAULT '#000000',
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_sku (sku),
    INDEX idx_stock (stock)
) ENGINE=InnoDB;

-- =====================================================
-- ORDERS TABLE
-- =====================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) DEFAULT 0,
    tax DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address TEXT NOT NULL,  -- JSON object
    payment_details TEXT,  -- JSON object with Razorpay details
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    tracking_number VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_order_id (order_id),
    INDEX idx_status (order_status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- =====================================================
-- ORDER ITEMS TABLE
-- =====================================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    variant_id INT NOT NULL,
    variant_details TEXT,  -- JSON object with size, color, sku
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB;

-- =====================================================
-- SESSIONS TABLE (for PHP session storage)
-- =====================================================
CREATE TABLE sessions (
    id VARCHAR(128) NOT NULL PRIMARY KEY,
    data TEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB;

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Insert default admin (password: admin123)
INSERT INTO admins (name, email, password, role) VALUES 
('Super Admin', 'admin@alliedeals.com', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8qB.V4g0g2DLcVqKxLb9pLqIiKKG6O', 'superadmin');

-- Insert categories
INSERT INTO categories (name, slug, description) VALUES 
('T-Shirts', 'tshirts', 'Stylish and comfortable t-shirts for all occasions'),
('Gadgets', 'gadgets', 'Latest digital gadgets and accessories');

-- Insert sample products
INSERT INTO products (name, description, category, brand, base_price, images, featured, trending) VALUES 
('Classic White T-Shirt', 'Premium cotton t-shirt perfect for everyday wear. Soft, comfortable, and durable.', 'tshirts', 'AllieDEals Classics', 499.00, '["https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800", "https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800"]', 1, 1),
('Graphic Print T-Shirt', 'Trendy graphic print t-shirt with vibrant colors. Express your style!', 'tshirts', 'Urban Style', 599.00, '["https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800"]', 1, 0),
('Wireless Earbuds Pro', 'Premium quality wireless earbuds with noise cancellation and 24hr battery life.', 'gadgets', 'TechPro', 2999.00, '["https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800"]', 1, 1),
('Smart Watch Elite', 'Feature-packed smartwatch with fitness tracking, heart rate monitor, and more.', 'gadgets', 'FitTech', 4999.00, '["https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800"]', 1, 1),
('Black Polo T-Shirt', 'Classic polo t-shirt in black. Perfect for casual and semi-formal occasions.', 'tshirts', 'AllieDEals Premium', 799.00, '["https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=800"]', 0, 0);

-- Insert product variants for T-Shirts
INSERT INTO product_variants (product_id, size, color, color_hex, price, stock, sku) VALUES 
-- Classic White T-Shirt
(1, 'S', 'White', '#FFFFFF', 499.00, 50, 'TSH-WHT-S-001'),
(1, 'M', 'White', '#FFFFFF', 499.00, 75, 'TSH-WHT-M-001'),
(1, 'L', 'White', '#FFFFFF', 499.00, 60, 'TSH-WHT-L-001'),
(1, 'XL', 'White', '#FFFFFF', 499.00, 40, 'TSH-WHT-XL-001'),
(1, 'XXL', 'White', '#FFFFFF', 499.00, 20, 'TSH-WHT-XXL-001'),

-- Graphic Print T-Shirt
(2, 'S', 'Black', '#000000', 599.00, 30, 'TSH-BLK-S-002'),
(2, 'M', 'Black', '#000000', 599.00, 45, 'TSH-BLK-M-002'),
(2, 'L', 'Black', '#000000', 599.00, 35, 'TSH-BLK-L-002'),
(2, 'XL', 'Black', '#000000', 599.00, 25, 'TSH-BLK-XL-002'),
(2, 'M', 'Navy', '#000080', 599.00, 40, 'TSH-NVY-M-002'),
(2, 'L', 'Navy', '#000080', 599.00, 30, 'TSH-NVY-L-002'),

-- Wireless Earbuds (no size/color variants for gadgets)
(3, NULL, 'Black', '#000000', 2999.00, 100, 'GAD-EAR-BLK-001'),

-- Smart Watch
(4, NULL, 'Silver', '#C0C0C0', 4999.00, 50, 'GAD-WTC-SLV-001'),
(4, NULL, 'Black', '#000000', 4999.00, 45, 'GAD-WTC-BLK-001'),

-- Black Polo T-Shirt
(5, 'M', 'Black', '#000000', 799.00, 40, 'TSH-PLO-M-003'),
(5, 'L', 'Black', '#000000', 799.00, 35, 'TSH-PLO-L-003'),
(5, 'XL', 'Black', '#000000', 799.00, 25, 'TSH-PLO-XL-003');

-- =====================================================
-- DONE
-- =====================================================
-- Database setup complete!
-- Access phpMyAdmin at: http://localhost/phpmyadmin
-- Database name: alliedeals
-- Admin credentials: admin@alliedeals.com / admin123
