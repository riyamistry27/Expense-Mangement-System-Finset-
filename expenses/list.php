<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$category = $_GET['category'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';
$keyword = $_GET['keyword'] ?? '';

$query = "SELECT expenses.*, categories.category_name
          FROM expenses
          JOIN categories ON expenses.category_id = categories.id
          WHERE expenses.user_id='$user_id'";

if($category != '') $query .= " AND expenses.category_id='$category'";
if($from != '') $query .= " AND expense_date >= '$from'";
if($to != '') $query .= " AND expense_date <= '$to'";
if($min != '') $query .= " AND amount >= '$min'";
if($max != '') $query .= " AND amount <= '$max'";
if($keyword != '') $query .= " AND description LIKE '%$keyword%'";

$query .= " ORDER BY expense_date DESC";

$result = mysqli_query($conn, $query);

$expenses = [];
while($row = mysqli_fetch_assoc($result)){
    $expenses[] = $row;
}
$total_query = "SELECT SUM(amount) as total FROM expenses WHERE user_id='$user_id'";

if($category != '') $total_query .= " AND category_id='$category'";
if($from != '') $total_query .= " AND expense_date >= '$from'";
if($to != '') $total_query .= " AND expense_date <= '$to'";
if($min != '') $total_query .= " AND amount >= '$min'";
if($max != '') $total_query .= " AND amount <= '$max'";
if($keyword != '') $total_query .= " AND description LIKE '%$keyword%'";

$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'] ?? 0;

$cat_query = "SELECT * FROM categories 
              WHERE user_id='$user_id' OR user_id IS NULL";
              $cat_result = mysqli_query($conn, $cat_query);
?>

<!DOCTYPE html>
<html>
<head>

<title>Expense List</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000;
}

/* ===== MAIN LAYOUT ===== */

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

.logo{
margin-bottom:30px;
font-size:20px;
font-weight:600;
color:#FCA311;
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
font-size:15px;
transition:all 0.25s ease;
}

.sidebar a:hover{
background:#FCA311;
color:#000;
transform:translateX(4px);
}

.sidebar hr{
border-color:rgba(255,255,255,0.1);
margin:20px 0;
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
margin-bottom:20px;
}

.page-header h3{
margin:0;
font-weight:600;
}

/* ===== ADD BUTTON ===== */

.btn-add{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 16px;
border-radius:8px;
transition:0.3s;
}

.btn-add:hover{
background:#ffb733;
transform:translateY(-1px);
}

/* ===== CARD ===== */

.card{
border:none;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
background:#FFFFFF;
padding:20px;
}

/* ===== TABLE ===== */

.table{
margin-top:10px;
}

.table thead{
background:#f8fafc;
}

.table th{
font-weight:600;
}

.table td{
vertical-align:middle;
}

.table-hover tbody tr:hover{
background:#f1f5f9;
}

/* ===== EXPENSE AMOUNT ===== */

.amount-expense{
color:#ef4444;
font-weight:600;
}

/* ===== CATEGORY BADGE ===== */

.badge-category{
background:#14213D;
color:white;
padding:4px 8px;
border-radius:6px;
font-size:12px;
}

/* ===== ACTION BUTTONS ===== */

.btn-edit{
background:#FCA311;
border:none;
color:#000;
font-size:13px;
padding:6px 12px;
border-radius:6px;
margin-right:5px;
}

.btn-edit:hover{
background:#ffb733;
}

.btn-delete{
background:#ef4444;
border:none;
color:white;
font-size:13px;
padding:6px 12px;
border-radius:6px;
}

.btn-delete:hover{
background:#dc2626;
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

<?php include "../includes/sidebar.php"; ?>

<div class="content">



<div class="container mt-2">

<div class="card p-4">

<div class="page-header">

<h3>Expense List</h3>

<a href="add.php" class="btn btn-add">
<i class="fa-solid fa-plus"></i> Add Expense
</a>

</div>


<form method="GET" class="row g-2 mb-3">

<div class="col-md-2">
<select name="category" class="form-control">
<option value="">All Categories</option>

<?php while($cat = mysqli_fetch_assoc($cat_result)) { ?>

<option value="<?php echo $cat['id']; ?>"
<?php if($category == $cat['id']) echo "selected"; ?>>
<?php echo $cat['category_name']; ?>
</option>

<?php } ?>

</select>
</div>

<div class="col-md-2">
<input type="date" name="from" class="form-control" value="<?php echo $from; ?>">
</div>

<div class="col-md-2">
<input type="date" name="to" class="form-control" value="<?php echo $to; ?>">
</div>

<div class="col-md-2">
<input type="number" name="min" placeholder="Min Amount" class="form-control" value="<?php echo $min; ?>">
</div>

<div class="col-md-2">
<input type="number" name="max" placeholder="Max Amount" class="form-control" value="<?php echo $max; ?>">
</div>

<div class="col-md-2">
<input type="text" name="keyword" placeholder="Search" class="form-control" value="<?php echo $keyword; ?>">
</div>

<div class="col-md-12 mt-2">

<button class="btn btn-primary">Search</button>

<a href="list.php" class="btn btn-secondary">Reset</a>

</div>

</form>


<h5>Total: ₹ <?php echo number_format($total,2); ?></h5>


<table class="table table-hover">

<tr>
<th>ID</th>
<th>Category</th>
<th>Amount</th>
<th>Date</th>
<th>Description</th>
<th>Action</th>
</tr>

<?php foreach($expenses as $row) { ?>
<tr>

<td><?php echo $row['id']; ?></td>

<td>
<span class="badge-category">
<?php echo htmlspecialchars($row['category_name']); ?>
</span>
</td>
<td class="amount-expense">
- ₹ <?php echo number_format($row['amount'],2); ?>
</td>
<td><?php echo $row['expense_date']; ?></td>

<td><?php echo htmlspecialchars($row['description']); ?></td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">
Edit
</a>

<a href="delete.php?id=<?php echo $row['id']; ?>" 
class="btn btn-delete"
onclick="return confirm('Delete this expense?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</div>

</body>
</html>