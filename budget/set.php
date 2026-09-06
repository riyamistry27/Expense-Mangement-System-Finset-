<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$current_month = date('m');
$current_year = date('Y');


/* ================= SAVE BUDGET ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $budget = $_POST['budget'];

    $check = "SELECT * FROM budgets 
              WHERE user_id='$user_id' 
              AND month='$current_month' 
              AND year='$current_year'";

    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

        $update = "UPDATE budgets 
                   SET amount='$budget'
                   WHERE user_id='$user_id'
                   AND month='$current_month'
                   AND year='$current_year'";

        mysqli_query($conn, $update);

        $message = "Budget updated successfully";

    } else {

        $insert = "INSERT INTO budgets (user_id, amount, month, year)
                   VALUES ('$user_id', '$budget', '$current_month', '$current_year')";

        mysqli_query($conn, $insert);

        $message = "Budget set successfully";
    }
}


/* ================= GET BUDGET ================= */

$budget_query = "SELECT amount FROM budgets
                 WHERE user_id='$user_id'
                 AND month='$current_month'
                 AND year='$current_year'";

$budget_result = mysqli_query($conn, $budget_query);
$budget_row = mysqli_fetch_assoc($budget_result);

$budget_amount = $budget_row['amount'] ?? 0;


/* ================= GET EXPENSE ================= */

$expense_query = "SELECT SUM(amount) as total
                  FROM expenses
                  WHERE user_id='$user_id'
                  AND MONTH(expense_date)='$current_month'
                  AND YEAR(expense_date)='$current_year'";

$expense_result = mysqli_query($conn, $expense_query);
$expense_row = mysqli_fetch_assoc($expense_result);

$total_expense = $expense_row['total'] ?? 0;

/* ================= CATEGORY EXPENSE ================= */

$cat_query = "
SELECT c.category_name, SUM(e.amount) as total
FROM expenses e
JOIN categories c ON e.category_id = c.id
WHERE e.user_id='$user_id'
AND MONTH(e.expense_date)='$current_month'
AND YEAR(e.expense_date)='$current_year'
GROUP BY c.category_name
ORDER BY total DESC
";

$cat_result = mysqli_query($conn,$cat_query);


/* ================= CALCULATIONS ================= */

$remaining = $budget_amount - $total_expense;

$percent = 0;

if($budget_amount > 0){
$percent = ($total_expense / $budget_amount) * 100;
}

$percent = min($percent,100);


/* ================= DAILY LIMIT ================= */

$days_left = date("t") - date("d");

$daily_limit = 0;

if($days_left > 0){
$daily_limit = $remaining / $days_left;
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Budget Module</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

/* GLOBAL */

body{
margin:0;
background:#f1f5f9;
font-family:'Segoe UI',sans-serif;
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
}

.sidebar a{
display:block;
color:#E5E5E5;
padding:12px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
}

.sidebar a:hover{
background:#FCA311;
color:black;
}

/* CONTENT */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:25px;
}

/* CARD */

.card{
border-radius:16px;
border:none;
box-shadow:0 8px 20px rgba(0,0,0,0.06);
}

/* STAT CARDS */

.stat-card{
color:white;
border-radius:14px;
padding:20px;
}

.stat-card h6{
font-size:14px;
opacity:0.9;
}

.stat-card h3{
font-weight:600;
}

/* COLORS */

.card-budget{
background:#2563eb;
}

.card-expense{
background:#ef4444;
}

.card-remaining{
background:#16a34a;
}

/* PROGRESS */

.progress{
height:16px;
border-radius:10px;
background:#e5e7eb;
}

/* SUGGESTION */

.suggestion{
background:#fff7ed;
border-left:5px solid #f97316;
padding:16px;
border-radius:10px;
}

/* CHART */

.chart-card{
max-width:380px;
margin:auto;
}

/* CHART SIZE CONTROL */

.chart-container{
max-width:260px;
margin:auto;
}

.chart-container canvas{
width:100% !important;
height:260px !important;
}

</style>

</head>

<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="container-fluid">

<h3><i class="fa-solid fa-wallet"></i> Monthly Budget</h3>

<?php
if(isset($message)){
echo "<div class='alert alert-success mt-3'>$message</div>";
}
?>


<!-- SET BUDGET -->

<div class="card p-4 mt-3">

<form method="POST">

<label class="form-label">Set Budget (₹)</label>

<input type="number"
name="budget"
class="form-control mb-3"
value="<?php echo $budget_amount; ?>"
required>

<button class="btn btn-success">
Save Budget
</button>

</form>

</div>


<!-- STAT CARDS -->

<div class="row mt-4">

<div class="col-md-4">
<div class="stat-card card-budget">
<h6>Monthly Budget</h6>
<h3>₹ <?php echo number_format($budget_amount,2); ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="stat-card card-expense">
<h6>Total Expense</h6>
<h3>₹ <?php echo number_format($total_expense,2); ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="stat-card card-remaining">
<h6>Remaining Budget</h6>
<h3>₹ <?php echo number_format($remaining,2); ?></h3>
</div>
</div>

</div>


<!-- PROGRESS BAR -->

<div class="card p-4 mt-4">

<h6>Budget Usage</h6>

<div class="progress mt-2">

<div class="progress-bar 
<?php echo ($percent > 90 ? 'bg-danger' : 'bg-success'); ?>"
style="width: <?php echo $percent; ?>%">

<?php echo round($percent); ?>%

</div>

</div>

<?php if($percent >= 90){ ?>

<div class="alert alert-danger mt-3">
⚠ You have used <?php echo round($percent); ?>% of your budget.
</div>

<?php } ?>

</div>


<!-- DAILY LIMIT -->

<div class="suggestion mt-4">

<strong>Daily Spending Advice</strong><br>

<?php

if($remaining > 0){

echo "You can spend approximately 
<strong>₹ ".number_format($daily_limit,2)." per day</strong>
for the rest of this month.";

}else{

echo "<span class='text-danger'>
You have exceeded your budget.
</span>";

}

?>

</div>


<!-- CHART -->

<div class="row mt-4">

<!-- DOUGHNUT CHART -->

<div class="col-md-6">

<div class="card p-4">

<h6 class="text-center mb-3">Budget vs Expense</h6>

<div style="max-width:260px;margin:auto;">

<canvas id="budgetChart"></canvas>

</div>

</div>

</div>

<!-- CATEGORY BREAKDOWN -->

<div class="col-md-6">

<div class="card p-4">

<h6 class="mb-3">Expense by Category</h6>

<table class="table table-sm">

<tr>
<th>Category</th>
<th class="text-end">Amount</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($cat_result)){
?>

<tr>

<td>
<i class="fa-solid fa-circle-dot text-primary"></i>
<?php echo $row['category_name']; ?>
</td>

<td class="text-end text-danger">
₹ <?php echo number_format($row['total'],2); ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

const ctx = document.getElementById('budgetChart');

if(ctx){

new Chart(ctx, {

type: 'doughnut',

data: {

labels: ['Expense','Remaining'],

datasets: [{

data: [
<?php echo $total_expense; ?>,
<?php echo max($remaining,0); ?>
],

backgroundColor: [
'#ef4444',
'#16a34a'
]

}]

},

options:{
responsive:true,
plugins:{
legend:{
position:'bottom'
}
}
}

});

}

});

</script>

</body>
</html>