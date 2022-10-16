<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <!-- Styles -->
    <link rel="stylesheet" href="./styles.css">
</head>

<body>
    <?php
    // Check if the user is logged in, if not then show him the login form
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        include "login.php";
    } else {
        include "home.php";
    }
    ?>
</body>

</html>