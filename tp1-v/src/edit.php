<?php
require_once 'db.php';

// get the id from the url
$id = $_GET['id'];

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // get the values from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    // update the record in the database
    $sql = "UPDATE records SET title = '$title', description = '$description' WHERE id = $id";
    $conn->query($sql);
    // redirect to home page
    header("location: home.php");
}

/**
 * On this page will be shown the form to edit a record
 * wich record to edit is determined by the id in the url
 * and the user must be logged in
 */

// Start the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}


// get the record from the database
$sql = "SELECT * FROM records WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// check if user is the owner of the record or admin
if ($row['user_id'] == $_SESSION['id'] || $_SESSION['role'] == 'admin') {
    // if the user is the owner or admin show the form
?>
<form action="edit.php?id=<?php echo $id; ?>" method="post">
    <label for="title">Title</label>
    <input type="text" name="title" value="<?php echo $row['title']; ?>" />
    <label for="description">Description</label>
    <textarea name="description" id="description" cols="30" rows="10"><?php echo $row['description']; ?></textarea>
    <input type="submit" name="submit" value="Edit" />

</form>
<?php
} else {
    // if the user is not the owner or admin redirect to index.php
    header("location: index.php");
    exit;
}