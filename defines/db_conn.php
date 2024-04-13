<?php
// https://auth-db749.hstgr.io/index.php
// Database connection parameters
$servername = "localhost";
$username = "u956940883_adeanjy";
$password = "W1z@8o8a3p";
$database = "u956940883_adeanjy";


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "root";
$database = "quiz_db";

try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    // Terminate script execution or handle the error as necessary
    exit(); // This line is added to terminate script execution if the connection fails
}
