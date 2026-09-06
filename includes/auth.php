<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /expense-management/login.php");
    exit();
}
?>