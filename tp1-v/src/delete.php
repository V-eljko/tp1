<?php
// delete the record given in the url if the user is the owner or admin
// Start the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

require_once 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get the id from the url
$id = $_GET['id'];

// get the record from the database
$sql = "SELECT * FROM records WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// check if user is the owner of the record or admin
if ($row['user_id'] == $_SESSION['id'] || $_SESSION['role'] == 'admin') {
    // if the user is the owner or admin delete the record
    $sql = "DELETE FROM records WHERE id = $id";
    $conn->query($sql);
    header("location: index.php");
    exit;
} else {
    // if the user is not the owner or admin redirect to index.php
    header("location: index.php");
    exit;
}