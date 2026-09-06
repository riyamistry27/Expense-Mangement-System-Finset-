<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/database.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

/* ================= ADD CATEGORY ================= */

if(isset($_POST['add_category'])){
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);

    if(!empty($name)){
        mysqli_query($conn,"
            INSERT INTO categories (category_name, user_id)
            VALUES ('$name', NULL)
        ");

        log_admin_action($conn, $_SESSION['admin_id'], "Added category: $name");
    }

    header("Location: categories.php");
    exit();
}

/* ================= DELETE ================= */

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    mysqli_query($conn,"
        DELETE FROM categories
        WHERE id='$id' AND user_id IS NULL
    ");

    log_admin_action($conn, $_SESSION['admin_id'], "Deleted category (ID: $id)");

    header("Location: categories.php");
    exit();
}

/* ================= UPDATE ================= */

if(isset($_POST['update_category'])){
    $id = intval($_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);

    mysqli_query($conn,"
        UPDATE categories
        SET category_name='$name'
        WHERE id='$id' AND user_id IS NULL
    ");

    log_admin_action($conn, $_SESSION['admin_id'], "Updated category (ID: $id)");

    header("Location: categories.php");
    exit();
}

/* ================= FETCH ================= */

$result = mysqli_query($conn,"
    SELECT * FROM categories
    WHERE user_id IS NULL
    ORDER BY category_name ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Categories Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#f4f6f9;
font-family:'Inter','Segoe UI',sans-serif;
color:#111827;
}

/* ===== MAIN LAYOUT ===== */

.main-container{
display:flex;
min-height:100vh;
}

/* ===== SIDEBAR ===== */

.sidebar{
width:230px;
height:100vh;
background:#1e293b;
color:white;
padding:22px;
position:fixed;
left:0;
top:0;
display:flex;
flex-direction:column;
}

.sidebar h4{
margin-bottom:28px;
font-weight:600;
letter-spacing:0.4px;
}

/* SIDEBAR LINKS */

.sidebar a{
display:flex;
align-items:center;
gap:10px;
color:#cbd5e1;
padding:12px 14px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
font-size:14px;
transition:all 0.25s ease;
}

.sidebar a:hover{
background:#334155;
color:white;
transform:translateX(3px);
}

.sidebar a.text-danger:hover{
background:#ef4444;
color:white;
}

/* ===== CONTENT ===== */

.content{
margin-left:230px;
width:calc(100% - 230px);
padding:28px;
}

/* ===== TOPBAR ===== */

.topbar{
background:white;
padding:14px 20px;
border-radius:10px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 3px 10px rgba(0,0,0,0.06);
margin-bottom:25px;
}

.topbar h5{
margin:0;
font-weight:600;
}

/* ===== CARD ===== */

.card{
border:none;
border-radius:14px;
box-shadow:0 6px 16px rgba(0,0,0,0.06);
background:white;
}

/* ===== FORM INPUT ===== */

.form-control{
border-radius:8px;
padding:10px;
font-size:14px;
border:1px solid #d1d5db;
}

.form-control:focus{
border-color:#6366f1;
outline:none;
box-shadow:0 0 0 2px rgba(99,102,241,0.15);
}

/* ===== BUTTONS ===== */

.btn-primary{
background:#6366f1;
border:none;
border-radius:8px;
font-weight:500;
}

.btn-primary:hover{
background:#4f46e5;
}

.btn-warning{
border-radius:6px;
font-size:13px;
font-weight:500;
}

.btn-danger{
border-radius:6px;
font-size:13px;
font-weight:500;
}

/* ===== TABLE ===== */

.table{
margin-bottom:0;
font-size:14px;
}

.table thead{
background:#f1f5f9;
}

.table th{
font-weight:600;
color:#374151;
border:none;
}

.table td{
vertical-align:middle;
border-top:1px solid #f1f5f9;
}

/* TABLE HOVER */

.table-hover tbody tr:hover{
background:#f9fafb;
}

/* ===== INLINE UPDATE INPUT ===== */

table input.form-control{
display:inline-block;
width:160px;
margin-right:6px;
}

/* ===== RESPONSIVE ===== */

@media(max-width:992px){

.sidebar{
width:200px;
}

.content{
margin-left:200px;
width:calc(100% - 200px);
}

}

</style>
</head>

<body>

<div class="main-container">

<div class="sidebar">
<h4>Admin Panel</h4>
<a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>

<a href="users.php"><i class="fa-solid fa-users"></i> Users</a>

<a href="categories.php"><i class="fa-solid fa-layer-group"></i> Categories</a>

<a href="messages.php">
<i class="fa-solid fa-envelope"></i> User Messages
</a>

<a href="logs.php"><i class="fa-solid fa-file-lines"></i> Audit Logs</a>

<a href="logout.php" class="text-danger">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>
</div>

<div class="content">

<div class="topbar">
<h5>Categories Management</h5>
</div>

<div class="card p-4">

<form method="POST" class="row mb-4">
<div class="col-md-6">
<input type="text" name="category_name"
class="form-control" placeholder="Enter category name" required>
</div>
<div class="col-md-3">
<button type="submit" name="add_category"
class="btn btn-primary w-100">
Add Category
</button>
</div>
</form>

<div class="table-responsive">
<table class="table table-hover">

<thead>
<tr>
<th>ID</th>
<th>Category Name</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['category_name']); ?></td>

<td>

<form method="POST" class="d-inline">
<input type="hidden" name="category_id"
value="<?php echo $row['id']; ?>">

<input type="text" name="category_name"
value="<?php echo htmlspecialchars($row['category_name']); ?>"
class="form-control d-inline"
style="width:150px;">

<button type="submit"
name="update_category"
class="btn btn-sm btn-warning">
Update
</button>
</form>

<a href="?delete=<?php echo $row['id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this category?')">
Delete
</a>

</td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

</div>
</div>
</div>

</body>
</html>