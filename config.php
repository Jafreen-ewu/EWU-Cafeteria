<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$DB_HOST='localhost'; $DB_USER='root'; $DB_PASS=''; $DB_NAME='ewu_cafeteria';
$mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASS);
if ($mysqli->connect_error) die('MySQL conn error:'.$mysqli->connect_error);
$mysqli->query("CREATE DATABASE IF NOT EXISTS `".$DB_NAME."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($DB_NAME);
$mysqli->query("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, role ENUM('admin','staff','student') NOT NULL, wallet DECIMAL(10,2) DEFAULT 0)"); 
$mysqli->query("CREATE TABLE IF NOT EXISTS menu (id INT AUTO_INCREMENT PRIMARY KEY, item_name VARCHAR(150) NOT NULL, price DECIMAL(10,2) NOT NULL, stock INT NOT NULL DEFAULT 0)"); 
$mysqli->query("CREATE TABLE IF NOT EXISTS orders (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, menu_id INT NOT NULL, qty INT NOT NULL, total DECIMAL(10,2) NOT NULL, payment_method ENUM('WALLET','CASH') DEFAULT 'CASH', status ENUM('PENDING','COMPLETED') DEFAULT 'PENDING', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(menu_id) REFERENCES menu(id) ON DELETE CASCADE)"); 
// seed
$r = $mysqli->query("SELECT COUNT(*) c FROM users")->fetch_assoc();
if ($r['c']==0) {
  $mysqli->query("INSERT INTO users (username,password,role,wallet) VALUES ('admin1',MD5('admin123'),'admin',0),('staff1',MD5('staff123'),'staff',200),('student1',MD5('student123'),'student',500)"); 
}
$r2 = $mysqli->query("SELECT COUNT(*) c FROM menu")->fetch_assoc();
if ($r2['c']==0) {
  $mysqli->query("INSERT INTO menu (item_name,price,stock) VALUES ('Chicken Biryani',150,40),('Beef Tehari',180,30),('Coffee',80,100),('Samosa',20,300)"); 
}
$conn = $mysqli;
?>