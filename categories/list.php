<?php
session_start();
include "../config/database.php";

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM categories WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Category List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<h3>Categories</h3>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Category Name</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['category_name']; ?></td>
</tr>

<?php } ?>

</table>

<a href="../dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

</div>

</body>
</html>