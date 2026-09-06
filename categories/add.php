<?php
session_start();
include "../config/database.php";

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// default categories
$default_categories = [
    "Housing",
    "Transportation",
    "Food",
    "Utilities",
    "Clothing",
    "Medical/Healthcare",
    "Insurance"
];

$added = 0;

foreach ($default_categories as $category) {

    // check if already exists
    $check = "SELECT * FROM categories 
              WHERE user_id='$user_id' AND category_name='$category'";

    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) == 0) {

        $insert = "INSERT INTO categories (user_id, category_name)
                   VALUES ('$user_id', '$category')";

        mysqli_query($conn, $insert);

        $added++;
    }
}

$message = "$added categories added successfully.";
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Categories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<h3>Category Setup</h3>

<div class="alert alert-success">
<?php echo $message; ?>
</div>

<a href="list.php" class="btn btn-primary">View Categories</a>

</div>

</body>
</html>