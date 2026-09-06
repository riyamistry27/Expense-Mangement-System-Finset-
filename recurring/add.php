<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category_id = $_POST['category_id'] ?? NULL;
    $description = $_POST['description'];
    $frequency = $_POST['frequency'];
    $next_run = $_POST['next_run'];
    $goal_id = $_POST['goal_id'] ?? NULL;

    mysqli_query($conn,"
        INSERT INTO recurring_transactions
        (user_id,type,amount,category_id,description,frequency,next_run,goal_id)
        VALUES
        ('$user_id','$type','$amount','$category_id',
         '$description','$frequency','$next_run','$goal_id')
    ");

    header("Location: index.php");
    exit();
}

// Fetch categories
$cat_result = mysqli_query($conn,"
    SELECT * FROM categories WHERE user_id IS NULL OR user_id='$user_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Recurring</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* GLOBAL */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
}

/* LAYOUT */

.main-container{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:240px;
height:100vh;
background:#14213D;
color:white;
padding:25px 18px;
position:fixed;
left:0;
top:0;
display:flex;
flex-direction:column;
}

.sidebar a{
display:flex;
align-items:center;
gap:10px;
color:#E5E5E5;
padding:12px 14px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
transition:0.25s;
}

.sidebar a:hover{
background:#FCA311;
color:#000;
transform:translateX(4px);
}

/* CONTENT */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:25px;
display:flex;
flex-direction:column;
align-items:center;
}

/* PAGE HEADER */

.page-header{
width:100%;
max-width:700px;
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.page-header h3{
margin:0;
font-weight:600;
}

/* FORM CARD */

.form-card{
background:white;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
padding:35px;
width:700px;
}

/* INPUTS */

.form-control{
border-radius:8px;
padding:10px;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 0.15rem rgba(252,163,17,0.25);
}

/* BUTTONS */

.btn-add{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 18px;
border-radius:8px;
}

.btn-add:hover{
background:#ffb733;
}

.btn-back{
background:#6b7280;
border:none;
color:white;
padding:8px 18px;
border-radius:8px;
}

.btn-back:hover{
background:#4b5563;
}

/* RESPONSIVE */

@media(max-width:992px){

.sidebar{
width:200px;
}

.content{
margin-left:200px;
width:calc(100% - 200px);
}

.form-card{
width:100%;
}

}

</style>
</head>
<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="page-header">

<h3><i class="fa-solid fa-repeat"></i> Add Recurring Transaction</h3>

<a href="index.php" class="btn btn-back">
<i class="fa-solid fa-arrow-left"></i> Back
</a>

</div>

<div class="form-card">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">
<label>Type</label>
<select name="type" class="form-control" required>
<option value="income">Income</option>
<option value="expense">Expense</option>
</select>
</div>

<div class="col-md-6 mb-3">
<label>Amount</label>
<input type="number" step="0.01" name="amount" class="form-control" required>
</div>

</div>

<div class="mb-3">
<label>Category</label>
<select name="category_id" class="form-control">
<option value="">None</option>

<?php while($cat=mysqli_fetch_assoc($cat_result)){ ?>

<option value="<?php echo $cat['id']; ?>">
<?php echo $cat['category_name']; ?>
</option>

<?php } ?>

</select>
</div>

<div class="mb-3">
<label>Description</label>
<input type="text" name="description" class="form-control">
</div>

<div class="row">

<div class="col-md-6 mb-3">
<label>Frequency</label>
<select name="frequency" class="form-control" required>
<option value="monthly">Monthly</option>
<option value="weekly">Weekly</option>
<option value="yearly">Yearly</option>
</select>
</div>

<div class="col-md-6 mb-3">
<label>First Run Date</label>
<input type="date" name="next_run" class="form-control" required>
</div>

</div>

<?php
$goal_result = mysqli_query($conn,"
SELECT id,goal_name FROM savings_goals
WHERE user_id='$user_id' AND status='active'
");
?>

<div class="mb-3">
<label>Link to Goal (Optional)</label>

<select name="goal_id" class="form-control">

<option value="">None</option>

<?php while($g=mysqli_fetch_assoc($goal_result)){ ?>

<option value="<?php echo $g['id']; ?>">
<?php echo $g['goal_name']; ?>
</option>

<?php } ?>

</select>

</div>

<button class="btn btn-add">
<i class="fa-solid fa-check"></i> Save Recurring
</button>

</form>

</div>

</div>

</div>

</body>
</html>