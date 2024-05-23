<?php
// Check if a session is already active before starting one
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establishing a connection to the database
// Uncomment the following lines if using host connection
// $host = "localhost";
// $username = "id22178904_register";
// $password = "P@55w0rd123"; 
// $database = "id22178904_grocereaststore";

// Localhost connection 
$host = "localhost";
$username = "root"; // corrected to match variable names
$password = ""; // corrected to match variable names
$database = "register";
$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the timezone if needed
// date_default_timezone_set('Asia/Manila');
?>
