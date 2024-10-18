<?php
// Database configuration
$servername = "localhost"; // XAMPP runs MySQL on localhost by default
$username = "root";        // Default XAMPP MySQL username
$password = "";            // Default XAMPP MySQL password is empty
$dbname = "krishna"; // Replace with your actual database name

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Close connection
// $conn->close();
?>
