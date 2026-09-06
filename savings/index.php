<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
header("Location: ../login.php");
exit();
}

$user_id = $_SESSION['user_id'];

/* TOTAL INCOME */

$income_query = "SELECT SUM(amount) as total FROM income WHERE user_id='$user_id'";
$income_result = mysqli_query($conn,$income_query);
$income_row = mysqli_fetch_assoc($income_result);
$total_income = $income_row['total'] ?? 0;

/* TOTAL EXPENSE */

$expense_query = "SELECT SUM(amount) as total FROM expenses WHERE user_id='$user_id'";
$expense_result = mysqli_query($conn,$expense_query);
$expense_row = mysqli_fetch_assoc($expense_result);
$total_expense = $expense_row['total'] ?? 0;

/* TOTAL SAVINGS */

$raw_savings = $total_income - $total_expense;
$total_savings = max(0,$raw_savings);

/* SAVINGS RATE */

$savings_rate = 0;
if($total_income > 0){
$savings_rate = ($total_savings / $total_income) * 100;
}

/* AUTO SAVINGS SUGGESTION */

$suggested_savings = round($total_income * 0.20);
?>

<!DOCTYPE html>
<html>
<head>

<title>Savings Overview</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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

/* SUMMARY CARDS */

.summary-card{
border-radius:14px;
padding:18px;
color:white;
font-weight:500;
box-shadow:0 8px 18px rgba(0,0,0,0.08);
}

/* SAVINGS SUGGESTION */

.suggestion{
background:#fff3cd;
border-left:5px solid #FCA311;
padding:16px;
border-radius:10px;
font-size:15px;
margin-top:20px;
}

/* GOALS CONTAINER */

.goals-section{
background:white;
border-radius:16px;
padding:25px;
margin-top:25px;
box-shadow:0 8px 18px rgba(0,0,0,0.06);
}

/* HEADER */

.goals-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

/* ADD GOAL BUTTON */

.add-goal-btn{
background:#2563eb;
color:white;
border:none;
padding:8px 16px;
border-radius:8px;
font-size:14px;
display:flex;
align-items:center;
gap:6px;
transition:0.2s;
}

.add-goal-btn:hover{
background:#1d4ed8;
transform:translateY(-1px);
}

/* GOAL CARD */

.goal-card{
background:white;
border-radius:14px;
padding:20px;
box-shadow:0 6px 16px rgba(0,0,0,0.08);
transition:0.25s;
position:relative;
}

.goal-card:hover{
transform:translateY(-4px);
box-shadow:0 12px 26px rgba(0,0,0,0.12);
}

/* TITLE */

.goal-title{
font-size:16px;
font-weight:600;
margin-bottom:6px;
}

/* AMOUNT */

.goal-amount{
font-size:14px;
color:#6b7280;
}

/* PROGRESS BAR */

.goal-progress{
width:100%;
height:10px;
background:#e5e7eb;
border-radius:8px;
overflow:hidden;
margin-top:10px;
}

.goal-progress-bar{
height:100%;
background:#22c55e;
transition:width 0.5s ease;
}

/* DATE */

.goal-date{
font-size:13px;
color:#6b7280;
margin-top:8px;
}

/* BUTTON */

.goal-btn{
margin-top:12px;
width:100%;
border-radius:8px;
font-size:14px;
}

/* COMPLETED BADGE */

.completed-badge{
position:absolute;
top:10px;
right:10px;
background:#22c55e;
color:white;
font-size:12px;
padding:4px 8px;
border-radius:20px;
}

/* CELEBRATION */

.goal-complete{
animation:celebrate 0.8s ease;
}

@keyframes celebrate{

0%{transform:scale(0.9)}
50%{transform:scale(1.05)}
100%{transform:scale(1)}

}

/* PAGE HEADER */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:15px;
}

/* HISTORY BUTTON */

.history-btn{
display:flex;
align-items:center;
gap:6px;
border-radius:8px;
font-size:14px;
padding:7px 14px;
transition:0.2s;
}

.history-btn:hover{
background:#14213D;
color:white;
border-color:#14213D;
}

.add-goal-btn{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 16px;
border-radius:8px;
}

.add-goal-btn:hover{
background:#ffb733;
}
</style>

</head>

<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="page-header">

<h3><i class="fa-solid fa-piggy-bank"></i> Savings Overview</h3>

<a href="history.php" class="btn btn-outline-dark history-btn">
<i class="fa-solid fa-clock-rotate-left"></i> History
</a>

</div>
<div class="row mt-4">

<div class="col-md-3">
<div class="summary-card bg-success">
Total Income
<h4>₹ <?php echo number_format($total_income,2); ?></h4>
</div>
</div>

<div class="col-md-3">
<div class="summary-card bg-danger">
Total Expense
<h4>₹ <?php echo number_format($total_expense,2); ?></h4>
</div>
</div>

<div class="col-md-3">
<div class="summary-card bg-primary">
Total Savings
<h4>₹ <?php echo number_format($total_savings,2); ?></h4>
</div>
</div>

<div class="col-md-3">
<div class="summary-card bg-warning">
Savings Rate
<h4><?php echo round($savings_rate); ?>%</h4>
</div>
</div>

</div>


<div class="suggestion">

💡 <strong>Savings Suggestion:</strong>

Based on your income, you could aim to save around

<strong>₹ <?php echo number_format($suggested_savings,2); ?></strong>

per month using the 20% savings rule.

</div>


<div class="goals-section">

<div class="goals-header">

<h4><i class="fa-solid fa-bullseye"></i> Savings Goals</h4>

<a href="goals_add.php" class="add-goal-btn">
<i class="fa-solid fa-plus"></i> Add Goal
</a>

</div>

<div class="row">

<?php
$goals = mysqli_query($conn,"
SELECT * FROM savings_goals
WHERE user_id='$user_id'
ORDER BY created_at DESC
");

while($goal=mysqli_fetch_assoc($goals)){

$percent = ($goal['target_amount'] > 0)
? ($goal['saved_amount'] / $goal['target_amount']) * 100
: 0;

if($percent >= 100){
$percent = 100;
$status_class = "goal-complete";
}else{
$status_class = "";
}
?>

<div class="col-md-4 mb-4">

<div class="goal-card <?php echo $status_class; ?>">

<?php if($percent >= 100){ ?>
<div class="completed-badge">Completed 🎉</div>

<?php } ?>

<div class="goal-title">
<i class="fa-solid fa-bullseye"></i>
<?php echo htmlspecialchars($goal['goal_name']); ?>
</div>

<div class="goal-amount">
Saved ₹ <?php echo number_format($goal['saved_amount'],2); ?>

of ₹ <?php echo number_format($goal['target_amount'],2); ?>
</div>

<div class="goal-progress">

<div 
class="goal-progress-bar"
id="progress-<?php echo $goal['id']; ?>"
data-current="<?php echo $percent; ?>"
style="width: <?php echo $percent; ?>%">
</div>

</div>

<div class="goal-date">
Target Date: <?php echo $goal['target_date']; ?>
</div>

<button
type="button"
class="btn btn-outline-success goal-btn"
data-bs-toggle="modal"
data-bs-target="#contributeModal"
data-goal="<?php echo $goal['id']; ?>">

<i class="fa-solid fa-plus"></i> Add Contribution

</button>

</div>

</div>

<?php } ?>

</div>

</div>

</div>

</div>

<div class="modal fade" id="contributeModal" tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">
<i class="fa-solid fa-piggy-bank"></i>
Add Contribution
</h5>

<button type="button" class="btn-close" data-bs-dismiss="modal"></button>

</div>

<form method="POST" id="contributionForm">

<div class="modal-body">

<input type="hidden" name="goal_id" id="goal_id">

<div class="mb-3">

<label>Amount</label>

<input type="number"
step="0.01"
name="amount"
class="form-control"
required>

</div>

</div>

<div class="modal-footer">

<button class="btn btn-success">
<i class="fa-solid fa-check"></i>
Add Contribution
</button>

</div>

</form>

</div>

</div>

</div>
<script>

var contributeModal = document.getElementById('contributeModal');

contributeModal.addEventListener('show.bs.modal', function (event) {

var button = event.relatedTarget;

var goalId = button.getAttribute('data-goal');

document.getElementById('goal_id').value = goalId;

});


const form = document.getElementById("contributionForm");

form.addEventListener("submit", function(e){

e.preventDefault();

const goalId = document.getElementById("goal_id").value;
const amount = document.querySelector("input[name='amount']").value;

fetch("goals_process.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:`goal_id=${goalId}&amount=${amount}`
})

.then(res => res.text())
.then(()=>{

const progressBar = document.getElementById("progress-"+goalId);

let current = parseFloat(progressBar.dataset.current);

let target = current + 5;

if(target > 100) target = 100;

progressBar.style.width = target + "%";

progressBar.dataset.current = target;

var modal = bootstrap.Modal.getInstance(document.getElementById('contributeModal'));
modal.hide();

});

});

</script>
</body>

</html>