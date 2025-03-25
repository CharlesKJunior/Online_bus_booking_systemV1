<?php
$host = "localhost";
$dbname = "V1";
$username = "root"; // Change if using another MySQL user
$password = "55011224Mc?"; // Add password if set

// Create MySQLi connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
