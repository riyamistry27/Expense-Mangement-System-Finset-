<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* MONTHLY INCOME */

$income_query = "
SELECT 
YEAR(income_date) as year,
MONTH(income_date) as month,
SUM(amount) as total_income
FROM income
WHERE user_id='$user_id'
GROUP BY YEAR(income_date), MONTH(income_date)
";

$income_result = mysqli_query($conn, $income_query);

$income_data = [];

while($row = mysqli_fetch_assoc($income_result)){
    $key = $row['year']."-".$row['month'];
    $income_data[$key] = $row['total_income'];
}


/* MONTHLY EXPENSE */

$expense_query = "
SELECT 
YEAR(expense_date) as year,
MONTH(expense_date) as month,
SUM(amount) as total_expense
FROM expenses
WHERE user_id='$user_id'
GROUP BY YEAR(expense_date), MONTH(expense_date)
";

$expense_result = mysqli_query($conn, $expense_query);

$expense_data = [];

while($row = mysqli_fetch_assoc($expense_result)){
    $key = $row['year']."-".$row['month'];
    $expense_data[$key] = $row['total_expense'];
}


/* MERGE MONTHS */

$months = array_unique(array_merge(array_keys($income_data), array_keys($expense_data)));

$labels = [];
$savings_values = [];

foreach($months as $month_key){

list($year,$month) = explode("-",$month_key);

$income = $income_data[$month_key] ?? 0;
$expense = $expense_data[$month_key] ?? 0;

$savings = max(0, $income - $expense);

$labels[] = date("M Y", mktime(0,0,0,$month,10,$year));
$savings_values[] = $savings;

}

sort($months);
?>

<!DOCTYPE html>
<html>

<head>

<title>Savings History</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
}

/* PAGE HEADER */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

/* CARD */

.card{
border-radius:16px;
box-shadow:0 8px 18px rgba(0,0,0,0.08);
border:none;
}

/* TABLE */

.table thead{
background:#f1f5f9;
}

.table tbody tr{
transition:0.2s;
}

.table tbody tr:hover{
background:#f9fafb;
}

/* AMOUNT COLORS */

.income{
color:#16a34a;
font-weight:500;
}

.expense{
color:#dc2626;
font-weight:500;
}

.savings{
color:#2563eb;
font-weight:500;
}

</style>

</head>

<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="page-header">

<h3><i class="fa-solid fa-clock-rotate-left"></i> Savings History</h3>

<a href="index.php" class="btn btn-outline-dark">
<i class="fa-solid fa-arrow-left"></i> Back
</a>

</div>

<div class="card p-4 mb-4">

<h5 class="mb-3">
<i class="fa-solid fa-chart-line"></i> Savings Trend
</h5>

<canvas id="savingsChart" height="90"></canvas>

</div>

<div class="card p-4">

<table class="table align-middle">

<thead>

<tr>
<th>Month</th>
<th>Income</th>
<th>Expense</th>
<th>Savings</th>
<th>Savings Rate</th>
</tr>

</thead>

<tbody>

<?php

foreach($months as $month_key){

list($year,$month) = explode("-",$month_key);

$income = $income_data[$month_key] ?? 0;
$expense = $expense_data[$month_key] ?? 0;

$raw_savings = $income - $expense;
$savings = max(0,$raw_savings);

$rate = ($income > 0) ? ($savings / $income) * 100 : 0;

?>

<tr>

<td>
<?php echo date("F Y", mktime(0,0,0,$month,10,$year)); ?>
</td>

<td class="income">
₹ <?php echo number_format($income,2); ?>
</td>

<td class="expense">
₹ <?php echo number_format($expense,2); ?>
</td>

<td class="savings">
₹ <?php echo number_format($savings,2); ?>
</td>

<td>
<?php echo round($rate); ?>%
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script>

const ctx = document.getElementById('savingsChart');

new Chart(ctx, {
type: 'line',
data: {
labels: <?php echo json_encode($labels); ?>,
datasets: [{
label: 'Monthly Savings',
data: <?php echo json_encode($savings_values); ?>,
borderColor: '#22c55e',
backgroundColor: 'rgba(34,197,94,0.1)',
fill: true,
tension: 0.4,
pointRadius: 4
}]
},
options: {
responsive:true,
plugins:{
legend:{
display:false
}
},
scales:{
y:{
beginAtZero:true
}
}
}
});

</script>

</body>

</html>