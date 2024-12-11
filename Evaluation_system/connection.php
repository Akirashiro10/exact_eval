<?php
// Database configuration
$host = 'localhost';
$dbname = 'eval_sys';
$user = 'root';
$pass = '';

// Create connection
$connection = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

echo "Connected successfully";

// Close connection
$connection->close();
?>
