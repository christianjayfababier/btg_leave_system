<?php 
$host = "127.0.0.1";
$username = "root"; // usually "root" for localhost, not "localhost"
$password = "";
$database = "btg_leave_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}