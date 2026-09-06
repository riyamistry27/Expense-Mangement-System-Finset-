<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$id = $_GET['id'];


// FETCH EXPENSE
$query = "SELECT * FROM expenses 
          WHERE id='$id' AND user_id='$user_id'";

$result = mysqli_query($conn, $query);
$expense = mysqli_fetch_assoc($result);


// FETCH CATEGORIES
$cat_query = "SELECT * FROM categories 
              WHERE user_id='$user_id'";

$cat_result = mysqli_query($conn, $cat_query);


// UPDATE EXPENSE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $update = "UPDATE expenses
               SET category_id='$category_id',
                   amount='$amount',
                   expense_date='$date',
                   description='$description'
               WHERE id='$id'
               AND user_id='$user_id'";

    mysqli_query($conn, $update);

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Expense</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
}

/* ===== LAYOUT ===== */

.main-container{
display:flex;
}

/* ===== SIDEBAR ===== */

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

/* ===== CONTENT ===== */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:25px;
}

/* ===== PAGE HEADER ===== */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.page-header h3{
margin:0;
font-weight:600;
}

/* ===== FORM CARD ===== */

.form-card{
background:#FFFFFF;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
padding:35px;
width:650px;
margin:auto;
}

/* ===== INPUTS ===== */

.form-control{
border-radius:8px;
padding:10px;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 0.15rem rgba(252,163,17,0.25);
}

/* ===== BUTTONS ===== */

.btn-update{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 18px;
border-radius:8px;
}

.btn-update:hover{
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

/* ===== RESPONSIVE ===== */

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

<!-- SIDEBAR -->
<?php include "../includes/sidebar.php"; ?>


<div class="content">

<!-- HEADER -->


<div class="container-fluid mt-4">

<div class="form-card">
<h3>
<i class="fa-solid fa-pen"></i> Edit Expense
</h3>

<form method="POST">


<div class="mb-3">

<label class="form-label">Category</label>

<select name="category_id" class="form-control" required>

<?php while($cat = mysqli_fetch_assoc($cat_result)) { ?>

<option value="<?php echo $cat['id']; ?>"
<?php if($cat['id'] == $expense['category_id']) echo "selected"; ?>>

<?php echo $cat['category_name']; ?>

</option>

<?php } ?>

</select>

</div>


<div class="mb-3">

<label class="form-label">Amount (₹)</label>

<input type="number"
       step="0.01"
       name="amount"
       class="form-control"
       value="<?php echo $expense['amount']; ?>"
       required>

</div>


<div class="mb-3">

<label class="form-label">Date</label>

<input type="date"
       name="date"
       class="form-control"
       value="<?php echo $expense['expense_date']; ?>"
       required>

</div>


<div class="mb-3">

<label class="form-label">Description</label>

<textarea name="description"
          class="form-control"
          rows="3"><?php echo $expense['description']; ?></textarea>

</div>


<div class="d-flex gap-2">

<button class="btn btn-update">
<i class="fa-solid fa-check"></i> Update
</button>
<a href="list.php" class="btn btn-back">
<i class="fa-solid fa-arrow-left"></i> Back
</a>

</div>


</form>

</div>

</div>

</div>

</div>

</body>
</html>