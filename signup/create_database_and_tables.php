<?php
  // Database connection parameters
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "project";

  // Create connection
  $conn = new mysqli($servername, $username, $password);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Attempt to create database
  $sql = "CREATE DATABASE IF NOT EXISTS $dbname";


  // Select the database
  $conn->select_db($dbname);

  // Attempt to create 'offices' table
  $sql = "CREATE TABLE IF NOT EXISTS offices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    office_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
  )";

 
  // Attempt to create 'clients' table
  $sql = "CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    occupation VARCHAR(255)
  )";


  // Close the database connection
  $conn->close();
?>