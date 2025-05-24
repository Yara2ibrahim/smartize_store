# 🛍️ Smartize Store – PHP & MySQL E-Commerce System

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.2+-7952B3?logo=bootstrap&logoColor=white)

Smartize Store is an enhanced e-commerce solution built with PHP and MySQL, featuring advanced cart management, product displays, and secure checkout processes.

## 🌟 Enhanced Features

### 🛒 Advanced Cart System
- Dynamic quantity adjustment (+/- buttons)
- Real-time price calculation
- Automatic shipping cost calculation
- Empty cart detection

### 🖼️ Improved Product Display
- Multiple product images gallery
- Category-based organization
- Responsive product cards
- Detailed product pages

### 🔒 Secure Checkout Process
- Contact information collection
- Shipping address validation
- Multiple payment methods:
  - Credit Card (Visa/Mastercard)
  - PayPal
  - Cash on Delivery

## 📂 Updated Project Structure

smartize_store/
├── assets/
│ ├── css/
│ │ └── style.css # Enhanced styles
│ └── js/
│ └── cart.js # Cart functionality
├── images/ # Product images storage
│ ├── products/
│ └── default.jpg # Default product image
├── includes/
│ ├── db.php # Database connection
│ ├── functions.php # Updated helper functions
│ └── them/ # Theme components
│ ├── footer.php # Updated footer
│ ├── header.php # Updated header
│ └── navigation.php # Navigation bar
├── cart.php # Enhanced cart page
├── product.php # Updated product view
├── products.php # Products listing
├── add_to_cart.php # Cart operations
├── update_cart.php # Cart item management
├── index.php # Homepage
└── README.md # Updated documentation


## 🚀 Installation Guide

### Prerequisites
- PHP 8.0+
- MySQL 5.7+
- Web server (Apache/Nginx)
- Composer (recommended)

### Setup Steps

1. Clone the repository:
```bash
git clone https://github.com/yourusername/smartize-store.git
cd smartize-store
Database Setup:

sql
CREATE DATABASE smartize_store;
USE smartize_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
Configuration:
Edit includes/db.php:

php
<?php
$host = 'localhost';
$dbname = 'smartize_store';
$username = 'your_db_username';
$password = 'your_db_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
Permissions:

bash
chmod -R 755 images/
🧑‍💻 Usage Instructions
Cart Management
Use +/- buttons to adjust quantities

Items automatically update in real-time

Empty cart shows friendly message

Shipping costs calculated automatically

Product Display
Main product image with thumbnails

Responsive design for all devices

Category-based organization

🛠️ Development Updates
Key Improvements:
Fixed cart total calculation

Added empty cart detection

Enhanced product image handling

Improved responsive design

Coding Standards:
PSR-12 compliant code

Proper error handling

Secure session management

Commented code blocks

🤝 How to Contribute
Fork the repository

Create your feature branch:

bash
git checkout -b feature/your-feature
Commit your changes:

bash
git commit -m 'Add some feature'
Push to the branch:

bash
git push origin feature/your-feature
Open a Pull Request

📜 License
Distributed under the MIT License. See LICENSE for more information.

📧 Contact
Project Maintainer - [Yara Ali Ibrahim] - Yara2ibrahim@gmail.com

Project Link: https://github.com/Yara2ibrahim/smartize-store



This updated README includes:

1. New badges for technologies
2. Enhanced feature list focusing on cart improvements
3. Updated project structure reflecting current files
4. Detailed installation instructions with new database schema
5. Specific usage instructions for cart functionality
6. Clear contribution guidelines
7. Properly formatted code blocks
8. Consistent section headers
9. Updated contact information
