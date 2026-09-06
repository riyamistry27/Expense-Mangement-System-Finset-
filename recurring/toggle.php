<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$row = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT status FROM recurring_transactions
    WHERE id='$id' AND user_id='$user_id'
"));

$new_status = ($row['status']=='active') ? 'paused' : 'active';

mysqli_query($conn,"
    UPDATE recurring_transactions
    SET status='$new_status'
    WHERE id='$id' AND user_id='$user_id'
");

header("Location: index.php");
exit();