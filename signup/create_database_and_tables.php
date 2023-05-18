<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectKaitlinFraniMarlRico";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Attempt to create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Attempt to create 'offices' table
$sql = "CREATE TABLE IF NOT EXISTS offices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    office_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table offices: " . $conn->error);
}

// Attempt to create 'clients' table
$sql = "CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    occupation VARCHAR(255)
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table clients: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS providers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medical_office_id INT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    occupation VARCHAR(255) NOT NULL,
    zipcode VARCHAR(10) NOT NULL,
    food_preference VARCHAR(255) NOT NULL,
    active_inactive BOOLEAN NOT NULL DEFAULT 1,
    FOREIGN KEY (medical_office_id) REFERENCES offices(id)
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table providers: " . $conn->error);
}

// Attempt to create 'availabilities' table
$sql = "CREATE TABLE IF NOT EXISTS availabilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    provider_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (provider_id) REFERENCES providers(id)
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating providers availabilities: " . $conn->error);
}
$sql = "CREATE TABLE IF NOT EXISTS appointments_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    provider_id INT NOT NULL,
    appointment_date DATE,
    start_time TIME,
    end_time TIME,
    status ENUM('pending', 'accepted', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending',
    message TEXT,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (provider_id) REFERENCES providers(id)
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating appointments: " . $conn->error);
}




// Close the database connection
$conn->close();
?>