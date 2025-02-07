Web03: E-Commerce Platform for Local Artisans

Overview

Web03 is a PHP & MySQL-based e-commerce platform designed to empower local artisans by providing them with a digital marketplace to showcase and sell handmade products. The platform features an Instagram Reels-like product browsing experience, allowing users to navigate through products via vertical swipes and view multiple images through horizontal swipes.

Features

Reels-Style Product Browsing – Swipe up/down to explore different products, left/right to view product images.

User Authentication – Separate login for buyers and sellers.

Product Listings – Sellers can upload and manage products with images.

Shopping Cart – Buyers can add products to cart for seamless checkout.

Reviews & Ratings – Users can review purchased products.

Mobile-Friendly UI – Optimized for touch interactions.

Installation Guide

Prerequisites

XAMPP (for Apache, MySQL, and PHP)

Web browser

Setup Instructions

Clone the Repository

git clone https://github.com/YOUR-USERNAME/Web03-Ecommerce.git

Move Files to XAMPP

Copy the entire project folder to C:\xampp\htdocs\

The directory should now be C:\xampp\htdocs\Web03-Ecommerce

Start XAMPP

Open XAMPP Control Panel

Start Apache and MySQL

Database Setup

Open http://localhost/phpmyadmin/

Create a new database named ``

Use the following schema to manually create the required tables:

Database Schema

cart Table

CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL
);

orders Table

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    review_rating INT,
    review_text TEXT,
    total_price DECIMAL(20,2),
    address TEXT NOT NULL,
    phone_number VARCHAR(15) NOT NULL
);

order_items Table

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(20,2)
);

payments Table

CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(15) NOT NULL,
    card_number VARCHAR(16) NOT NULL
);

products Table

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(20,2),
    description TEXT NOT NULL,
    image_1 VARCHAR(255),
    image_2 VARCHAR(255),
    image_3 VARCHAR(255),
    image_4 VARCHAR(255),
    image_5 VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0.00,
    review_count INT DEFAULT 0
);

reviews Table

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    buyer_id INT NOT NULL,
    rating INT NOT NULL,
    review_text TEXT NOT NULL
);

users Table

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    user_type ENUM('buyer', 'seller')
);

Run the Application

Open your browser and go to:

http://localhost/Web03-Ecommerce/

Sign up as a buyer or seller and explore the platform!

License

This project is licensed under the MIT License – you are free to use, modify, and distribute it with proper attribution.

Contributing

Feel free to contribute by submitting issues or pull requests. Let's build a better marketplace together!

Now, you just need to replace YOUR-USERNAME with your GitHub username in the repository link before committing this README file. Let me know if you need any modifications! 🚀

