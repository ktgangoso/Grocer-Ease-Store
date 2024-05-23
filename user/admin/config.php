<?php
// Start a session
session_start();

// Database connection parameters
$host = "localhost";
$username = "id22178904_register";
$password = "P@55w0rd123";
$database = "id22178904_grocereaststore";

// Create a connection to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the default timezone
date_default_timezone_set('Asia/Manila');

// Additional code can be added here as needed

?>


