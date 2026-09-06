<?php
session_start();
include "../config/database.php";

$id = $_GET['id'];

$query = "DELETE FROM income WHERE id='$id'";

mysqli_query($conn, $query);

header("Location: list.php");
?>