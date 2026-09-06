<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$id = $_GET['id'];

$query = "DELETE FROM expenses 
          WHERE id='$id' AND user_id='$user_id'";

mysqli_query($conn, $query);

header("Location: list.php");
exit();
?>