<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

mysqli_query($conn,"
    DELETE FROM recurring_transactions
    WHERE id='$id' AND user_id='$user_id'
");

header("Location: index.php");
exit();