<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'rasel');
define('DB_PASS', 'rasel123');
define('DB_NAME', 'contract');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
$conn->query($sql_create_db);
$conn->select_db(DB_NAME);

// Create table if it doesn't exist
$sql_create_table = "CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gmail VARCHAR(255) NOT NULL,
    contract_number VARCHAR(100) NOT NULL UNIQUE,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_gmail (gmail),
    INDEX idx_contract (contract_number)
)";

$conn->query($sql_create_table);
?>
