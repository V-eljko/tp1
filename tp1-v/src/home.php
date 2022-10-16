<?php
session_start();
// if user access page without login redirect to index.php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
// if user click logout button
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <a href="index.php?logout='1'"
        style="position: absolute; top: 10px; right: 16px; font-size: 18px; background:#12acee;color:white; ">Logout</a>
    <?php
    // This page is seperated into two parts
    // 1. The form to add a new record
    // 2. The table to show all the records with a edit and delete button
    // 2.5 It is possible to select multiples records and delete them

    require_once 'db.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    // The form will consist of a Title, Description
    // Each form will be attached to a user, so we need to get the user id, also only the user can edit his own records, admins can edit all records
    // The user id will be stored in a session variable
    ?>
    <div class="form-container">
        <form action="home.php" method="post">
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="10"></textarea>
            <label for="tags">Tags</label>
            <?php
            // Create a query to get all the tags
            $sql = "SELECT * FROM tags";
            // Execute the query
            $result = $conn->query($sql);
            ?>
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
    <?php
    # Second part : The table to show all the records with a edit and delete button
    // Create a query to get all the records
    $sql = "SELECT * FROM records";
    // Execute the query
    $result = $conn->query($sql);
    // Check if there are any records
    if ($result->num_rows > 0) {
        // Create a table
        echo "<table>";
        // Create a header row
        echo "<tr>";
        echo "<th>Title</th>";
        echo "<th>Description</th>";
        echo "<th>Tags</th>";
        echo "<th>Edit</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        // Loop through the records
        while ($row = $result->fetch_assoc()) {
            // Get the record id
            $record_id = $row['id'];
            // Get the record title
            $record_title = $row['title'];
            // Get the record description
            $record_description = $row['description'];
            // Create a row
            echo "<tr>";
            echo "<td>$record_title</td>";
            echo "<td>$record_description</td>";
            echo "<td>";
            // Create a query to get all the tags for this record
            $sql = "SELECT * FROM records_tags WHERE record_id = '$record_id'";
            // Execute the query
            $result2 = $conn->query($sql);

            echo "</td>";
            echo "<td><a href='edit.php?id=$record_id'>Edit</a></td>";
            echo "<td><a href='delete.php?id=$record_id'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
    <?php
    # Handle Post request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // if any of the fields is empty exit
        if (empty($_POST['title']) || empty($_POST['description'])) {
            exit;
        }
        // Get the title
        $title = $_POST['title'];
        // Get the description
        $description = $_POST['description'];
        // Get the tags
        $tags = $_POST['tags'];
        // Get the user id
        $user_id = $_SESSION['id'];
        // Create a query to insert the record
        $sql = "INSERT INTO records (title, description, user_id) VALUES ('$title', '$description', '$user_id')";
        // Execute the query
        $result = $conn->query($sql);
        // Get the record id
        $record_id = $conn->insert_id;
    }

    ?>

</body>

</html>