<?php
$servername = "db";
$username = "root";
$password = "example";
$dbname = "crud_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}