<?php
// Database connection settings

$host = "localhost";
$username = "root";
$password = "";
$database = "expense_management";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

function log_admin_action($conn, $admin_id, $action){
    $admin_id = intval($admin_id);
    $action = mysqli_real_escape_string($conn, $action);

    mysqli_query($conn,"
        INSERT INTO admin_logs (admin_id, action)
        VALUES ('$admin_id','$action')
    ");
}



?>