<?php
ob_start();
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
header("Location: ../login.php");
exit();
}

$user_id = $_SESSION['user_id'];

$view  = $_GET['view']  ?? 'monthly';
$month = $_GET['month'] ?? date('m');
$year  = $_GET['year']  ?? date('Y');

$month = intval($month);
$year  = intval($year);

/* ================= INCOME & EXPENSE ================= */

if($view == 'monthly'){

$income_q = "
SELECT SUM(amount) as total
FROM income
WHERE user_id='$user_id'
AND MONTH(income_date)=$month
AND YEAR(income_date)=$year
";

$expense_q = "
SELECT SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
AND MONTH(expense_date)=$month
AND YEAR(expense_date)=$year
";

}else{

$income_q = "
SELECT SUM(amount) as total
FROM income
WHERE user_id='$user_id'
AND YEAR(income_date)=$year
";

$expense_q = "
SELECT SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
AND YEAR(expense_date)=$year
";

}

$income_r = mysqli_fetch_assoc(mysqli_query($conn,$income_q));
$expense_r = mysqli_fetch_assoc(mysqli_query($conn,$expense_q));

$total_income  = $income_r['total'] ?? 0;
$total_expense = $expense_r['total'] ?? 0;

$total_savings = max(0,$total_income - $total_expense);

$savings_rate = ($total_income>0)
? ($total_savings/$total_income)*100
: 0;


/* ================= CATEGORY ================= */

if($view=='monthly'){

$cat_q = "
SELECT c.category_name,SUM(e.amount) as total
FROM expenses e
JOIN categories c ON e.category_id=c.id
WHERE e.user_id='$user_id'
AND MONTH(e.expense_date)=$month
AND YEAR(e.expense_date)=$year
GROUP BY c.category_name
ORDER BY total DESC";

}else{

$cat_q = "
SELECT c.category_name,SUM(e.amount) as total
FROM expenses e
JOIN categories c ON e.category_id=c.id
WHERE e.user_id='$user_id'
AND YEAR(e.expense_date)=$year
GROUP BY c.category_name
ORDER BY total DESC";

}

$cat_result = mysqli_query($conn,$cat_q);

$cat_labels=[];
$cat_values=[];
$top_category="None";
$top_amount=0;

while($row=mysqli_fetch_assoc($cat_result)){

$cat_labels[]=$row['category_name'];
$cat_values[]=$row['total'];

if($row['total']>$top_amount){
$top_amount=$row['total'];
$top_category=$row['category_name'];
}

}

if(empty($cat_labels)){
$cat_labels=["No Data"];
$cat_values=[0];
}


/* ================= TREND ================= */

if($view=='monthly'){

$trend_q="
SELECT DAY(expense_date) as d,
SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
AND MONTH(expense_date)=$month
AND YEAR(expense_date)=$year
GROUP BY DAY(expense_date)
ORDER BY d";

$trend_result=mysqli_query($conn,$trend_q);

$trend_labels=[];
$trend_values=[];

while($row=mysqli_fetch_assoc($trend_result)){
$trend_labels[]="Day ".$row['d'];
$trend_values[]=$row['total'];
}

}else{

$trend_q="
SELECT MONTH(expense_date) as m,
SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
AND YEAR(expense_date)=$year
GROUP BY MONTH(expense_date)
ORDER BY m";

$trend_result=mysqli_query($conn,$trend_q);

$trend_labels=[];
$trend_values=[];

while($row=mysqli_fetch_assoc($trend_result)){
$trend_labels[]=date("M",mktime(0,0,0,$row['m'],10));
$trend_values[]=$row['total'];
}

}

if(empty($trend_labels)){
$trend_labels=["No Data"];
$trend_values=[0];
}


/* ================= DAILY SPENDING ================= */

$days = ($view=='monthly')
? cal_days_in_month(CAL_GREGORIAN,$month,$year)
: 365;

$avg_daily = ($total_expense>0)
? $total_expense/$days
: 0;


/* ================= HEALTH SCORE ================= */

if($total_income>0){

$health_score = round(
(0.5*$savings_rate) +
(0.5*(100-min(100,($total_expense/$total_income)*100)))
);

}else{

$health_score = 0;

}

$health_score=max(0,min(100,$health_score));


/* ================= PDF EXPORT ================= */

if(isset($_POST['export_pdf'])){

require_once(__DIR__.'/../fpdf/fpdf.php');

$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,10,'Financial Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','',12);

$pdf->Cell(0,8,"Total Income: Rs ".number_format($total_income,2),0,1);
$pdf->Cell(0,8,"Total Expense: Rs ".number_format($total_expense,2),0,1);
$pdf->Cell(0,8,"Total Savings: Rs ".number_format($total_savings,2),0,1);
$pdf->Cell(0,8,"Savings Rate: ".round($savings_rate)."%",0,1);
$pdf->Cell(0,8,"Average Daily Spending: Rs ".number_format($avg_daily,2),0,1);
$pdf->Cell(0,8,"Top Spending Category: $top_category (Rs ".number_format($top_amount,2).")",0,1);
$pdf->Cell(0,8,"Financial Health Score: $health_score / 100",0,1);

$pdf->Output("D","Financial_Report.pdf");
exit();
}

?>
<!DOCTYPE html>
<html>
<head>

<title>Financial Reports</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* YOUR ORIGINAL CSS REMAINS UNCHANGED */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000000;
overflow-x:hidden;
}

.main-container{display:flex;width:100%}

.sidebar{
width:240px;
height:100vh;
background:#14213D;
color:#FFFFFF;
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
font-weight:500;
}

.sidebar a:hover{
background:#FCA311;
color:#000;
transform:translateX(4px);
}

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:30px;
}

.card{
background:#FFF;
border-radius:14px;
border:none;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

.chart-box{height:260px;width:100%}

.stat-card{padding:18px;border-radius:14px}

.stat-content{display:flex;justify-content:space-between;align-items:center}

.stat-icon{font-size:28px}

.btn-generate{
background:#FCA311;
color:#000;
border:none;
border-radius:8px;
padding:10px 16px;
font-weight:500;
display:flex;
align-items:center;
justify-content:center;
gap:8px;
}

.btn-dark{
background:#FCA311;
color:#000;
border:none;
}

</style>
</head>

<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="container-fluid">

<!-- FILTERS -->

<div class="card p-3 mb-4">

<form method="GET" class="row g-3 align-items-end">

<div class="col-md-3">
<label class="form-label">View</label>
<select name="view" class="form-control">
<option value="monthly" <?= $view=='monthly'?'selected':'' ?>>Monthly</option>
<option value="yearly" <?= $view=='yearly'?'selected':'' ?>>Yearly</option>
</select>
</div>

<div class="col-md-3">
<label class="form-label">Month</label>
<select name="month" class="form-control">

<?php for($m=1;$m<=12;$m++): ?>

<option value="<?= $m ?>" <?= $m==$month?'selected':'' ?>>

<?= date("F",mktime(0,0,0,$m,10)) ?>

</option>

<?php endfor; ?>

</select>
</div>

<div class="col-md-3">
<label class="form-label">Year</label>
<select name="year" class="form-control">

<?php for($y=date('Y');$y>=2020;$y--): ?>

<option value="<?= $y ?>" <?= $y==$year?'selected':'' ?>>

<?= $y ?>

</option>

<?php endfor; ?>

</select>
</div>

<div class="col-md-3">
<button type="submit" class="btn btn-generate w-100">
<i class="fa-solid fa-chart-line"></i> Generate Report
</button>
</div>

</form>

</div>

<div class="row g-4">

<!-- INCOME -->
<div class="col-lg-3 col-md-6">
<div class="card bg-success stat-card">
<div class="stat-content">
<div class="stat-info">
<h6>Total Income</h6>
<h4>₹ <?= number_format($total_income,2) ?></h4>
</div>
<div class="stat-icon">
<i class="fa-solid fa-wallet"></i>
</div>
</div>
</div>
</div>

<!-- EXPENSE -->
<div class="col-lg-3 col-md-6">
<div class="card bg-danger stat-card">
<div class="stat-content">
<div class="stat-info">
<h6>Total Expense</h6>
<h4>₹ <?= number_format($total_expense,2) ?></h4>
</div>
<div class="stat-icon">
<i class="fa-solid fa-credit-card"></i>
</div>
</div>
</div>
</div>

<!-- SAVINGS -->
<div class="col-lg-3 col-md-6">
<div class="card bg-primary stat-card">
<div class="stat-content">
<div class="stat-info">
<h6>Savings Rate</h6>
<h4><?= round($savings_rate) ?>%</h4>
</div>
<div class="stat-icon">
<i class="fa-solid fa-piggy-bank"></i>
</div>
</div>
</div>
</div>

<!-- HEALTH -->
<div class="col-lg-3 col-md-6">
<div class="card bg-warning stat-card">
<div class="stat-content">
<div class="stat-info">
<h6>Health Score</h6>
<h4><?= $health_score ?>/100</h4>
</div>
<div class="stat-icon">
<i class="fa-solid fa-heart-pulse"></i>
</div>
</div>
</div>
</div>

</div>


<!-- CHARTS -->
<div class="row g-4 mt-1">

<div class="col-md-6">
<div class="card p-3">
<h5>Category Distribution</h5>
<div class="chart-box">
<canvas id="catChart"></canvas>
</div>
</div>
</div>

<div class="col-md-6">
<div class="card p-3">
<h5>Income vs Expense</h5>
<div class="chart-box">
<canvas id="barChart"></canvas>
</div>
</div>
</div>

</div>


<div class="row g-4 mt-1">

<div class="col-md-6">
<div class="card p-3">
<h5>Expense Trend</h5>
<div class="chart-box">
<canvas id="trendChart"></canvas>
</div>
</div>
</div>

<div class="col-md-6">
<div class="card p-3">
<h5>Insights</h5>
<p><b>Top Spending Category:</b> <?= $top_category ?> (₹ <?= number_format($top_amount,2) ?>)</p>
<p><b>Average Daily Spending:</b> ₹ <?= number_format($avg_daily,2) ?></p>
</div>
</div>

</div>


<div class="text-end mt-4">

<form method="POST">

<input type="hidden" name="view" value="<?= $view ?>">
<input type="hidden" name="month" value="<?= $month ?>">
<input type="hidden" name="year" value="<?= $year ?>">

<button name="export_pdf" class="btn btn-dark">
Export PDF
</button>

</form>

</div>


<script>

new Chart(document.getElementById("catChart"),{
type:'doughnut',
data:{
labels:<?= json_encode($cat_labels) ?>,
datasets:[{
data:<?= json_encode($cat_values) ?>,
backgroundColor:["#6c63ff","#1cc88a","#36b9cc","#f6c23e","#e74a3b","#858796"]
}]
},
options:{responsive:true,maintainAspectRatio:false}
});

new Chart(document.getElementById("barChart"),{
type:'bar',
data:{
labels:["Income","Expense"],
datasets:[{
label:"Amount",
data:[<?= $total_income ?>,<?= $total_expense ?>],
backgroundColor:["#1cc88a","#e74a3b"]
}]
},
options:{responsive:true,maintainAspectRatio:false}
});

new Chart(document.getElementById("trendChart"),{
type:'line',
data:{
labels:<?= json_encode($trend_labels) ?>,
datasets:[{
label:"Expense Trend",
data:<?= json_encode($trend_values) ?>,
borderColor:"#6c63ff",
backgroundColor:"rgba(108,99,255,0.2)",
fill:true,
tension:0.4
}]
},
options:{responsive:true,maintainAspectRatio:false}
});

</script>