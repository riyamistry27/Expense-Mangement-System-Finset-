

<?php
session_start();
require_once "config/database.php";
include "recurring/process.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}




$user_id = $_SESSION['user_id'];


// ================= TOTAL INCOME =================

$income_query = "SELECT SUM(amount) as total_income 
                 FROM income 
                 WHERE user_id='$user_id'";

$income_result = mysqli_query($conn, $income_query);
$income_row = mysqli_fetch_assoc($income_result);

$total_income = $income_row['total_income'] ?? 0;


// ================= TOTAL EXPENSE =================

$expense_query = "SELECT SUM(amount) as total_expense 
                  FROM expenses 
                  WHERE user_id='$user_id'";

$expense_result = mysqli_query($conn, $expense_query);
$expense_row = mysqli_fetch_assoc($expense_result);

$total_expense = $expense_row['total_expense'] ?? 0;


// ================= TOTAL SAVINGS =================

// TOTAL SAVINGS (Calculated)
$raw_savings = $total_income - $total_expense;
$total_savings = max(0, $raw_savings);


// ================= TOTAL BALANCE =================

$total_balance = $total_income - $total_expense;


// ================= CATEGORY PIE CHART =================

$cat_query = "SELECT categories.category_name, SUM(expenses.amount) as total
              FROM expenses
              JOIN categories ON expenses.category_id = categories.id
              WHERE expenses.user_id='$user_id'
              GROUP BY categories.category_name";

$cat_result = mysqli_query($conn, $cat_query);

$cat_labels = [];
$cat_values = [];

while($row = mysqli_fetch_assoc($cat_result)) {

    $cat_labels[] = $row['category_name'];
    $cat_values[] = $row['total'];
}


// ================= MONTHLY EXPENSE CHART =================

$month_query = "SELECT MONTH(expense_date) as month, SUM(amount) as total
                FROM expenses
                WHERE user_id='$user_id'
                GROUP BY MONTH(expense_date)";

$month_result = mysqli_query($conn, $month_query);

$month_labels = [];
$month_values = [];

while($row = mysqli_fetch_assoc($month_result)) {

    $month_labels[] = date("F", mktime(0,0,0,$row['month'],10));
    $month_values[] = $row['total'];
}


/// ================= RECENT TRANSACTIONS (INCOME + EXPENSE) =================

$recent_query = "

SELECT 
'expense' as type,
expenses.amount,
expenses.expense_date as date,
categories.category_name as source,
expenses.description

FROM expenses

JOIN categories ON expenses.category_id = categories.id

WHERE expenses.user_id='$user_id'


UNION ALL


SELECT 
'income' as type,
income.amount,
income.income_date as date,
income.source,
NULL as description

FROM income

WHERE income.user_id='$user_id'


ORDER BY date DESC

LIMIT 5

";

$recent_result = mysqli_query($conn, $recent_query);
// ================= BUDGET PROGRESS =================

$current_month = date('m');
$current_year = date('Y');

$budget_query = "
SELECT amount 
FROM budgets
WHERE user_id='$user_id'
AND month='$current_month'
AND year='$current_year'
";

$budget_result = mysqli_query($conn, $budget_query);
$budget_row = mysqli_fetch_assoc($budget_result);

$total_budget = $budget_row['amount'] ?? 0;


// monthly expense

$monthly_expense_query = "
SELECT SUM(amount) as monthly_expense
FROM expenses
WHERE user_id='$user_id'
AND MONTH(expense_date)='$current_month'
AND YEAR(expense_date)='$current_year'
";

$monthly_expense_result = mysqli_query($conn, $monthly_expense_query);
$monthly_expense_row = mysqli_fetch_assoc($monthly_expense_result);

$monthly_expense = $monthly_expense_row['monthly_expense'] ?? 0;


// percentage calculation

$budget_percent = 0;

if($total_budget > 0){
$budget_percent = ($monthly_expense / $total_budget) * 100;
}

// ================= TREND CHART (INCOME VS EXPENSE) =================

$trend_year = date('Y');

$trend_query = "
SELECT 
    MONTH(date_column) as month,
    SUM(income_amount) as total_income,
    SUM(expense_amount) as total_expense
FROM (
    SELECT 
        income_date as date_column,
        amount as income_amount,
        0 as expense_amount
    FROM income
    WHERE user_id='$user_id'
    AND YEAR(income_date)='$trend_year'

    UNION ALL

    SELECT 
        expense_date as date_column,
        0 as income_amount,
        amount as expense_amount
    FROM expenses
    WHERE user_id='$user_id'
    AND YEAR(expense_date)='$trend_year'
) as combined
GROUP BY MONTH(date_column)
ORDER BY month
";

$trend_result = mysqli_query($conn, $trend_query);

$trend_labels = [];
$trend_income = [];
$trend_expense = [];

while($row = mysqli_fetch_assoc($trend_result)){
    $trend_labels[] = date("F", mktime(0,0,0,$row['month'],10));
    $trend_income[] = $row['total_income'];
    $trend_expense[] = $row['total_expense'];
}



/* ================= BUDGET REMINDER ================= */

// Get current month budget
$budget_data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT amount 
    FROM budgets 
    WHERE user_id='$user_id'
    AND month=MONTH(CURDATE())
    AND year=YEAR(CURDATE())
"));

if($budget_data){

    $budget_amount = $budget_data['amount'];

    // Get total expenses of current month
    $expense_data = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT SUM(amount) as total
        FROM expenses
        WHERE user_id='$user_id'
        AND MONTH(expense_date)=MONTH(CURDATE())
        AND YEAR(expense_date)=YEAR(CURDATE())
    "));

    $spent = $expense_data['total'] ?? 0;

    $percentage = ($budget_amount > 0)
        ? ($spent / $budget_amount) * 100
        : 0;

    if($percentage >= 90){

        $message = "You have used 90% of your monthly budget.";

        // Prevent duplicate notification for today
        $exists = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT id FROM notifications
            WHERE user_id='$user_id'
            AND message='$message'
            AND DATE(created_at)=CURDATE()
        "));

        if(!$exists){
            mysqli_query($conn,"
                INSERT INTO notifications (user_id,message,type)
                VALUES ('$user_id','$message','budget')
            ");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

/* ================= GLOBAL ================= */

html, body{
height:100%;
overflow:hidden;
}

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000;
}

/* ================= LAYOUT ================= */

.main-container{
display:flex;
}

/* ================= SIDEBAR ================= */

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
padding:12px 14px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
transition:0.3s;
font-size:15px;
}

.sidebar a:hover{
background:#FCA311;
color:#000;
}

.logo{
margin-bottom:25px;
font-size:20px;
font-weight:600;
}

/* ================= CONTENT ================= */

.content{
margin-left:240px;
width:calc(100% - 240px);
display:flex;
flex-direction:column;
height:100vh;
}

/* ================= TOPBAR ================= */

.topbar{
background:#FFFFFF;
padding:15px 20px;
border-radius:12px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

/* ================= SUMMARY CARDS ================= */

.summary-card{
border-radius:15px;
transition:0.3s;
}

.summary-card:hover{
transform:translateY(-4px);
}

/* override bootstrap colors */

.bg-primary{
background:#14213D !important;
}

.bg-success{
background:#22c55e !important;
}

.bg-danger{
background:#ef4444 !important;
}

.bg-warning{
background:#FCA311 !important;
color:#000 !important;
}

/* ================= GENERAL CARD ================= */

.card{
border:none;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
background:#FFFFFF;
transition:0.3s;
}

.card:hover{
transform:translateY(-3px);
}

/* ================= TABLE ================= */

.table{
margin-top:10px;
}

.table th{
background:#f8fafc;
font-weight:600;
}

.table td{
vertical-align:middle;
}

.table tr:hover{
background:#f1f5f9;
}

/* ================= BADGES ================= */

.badge{
padding:6px 10px;
font-size:12px;
border-radius:6px;
}

/* ================= PROGRESS BAR ================= */

.progress{
border-radius:20px;
background:#E5E5E5;
}

.progress-bar{
font-weight:600;
}

/* ================= CHART TITLE ================= */

.card h5{
font-weight:600;
margin-bottom:15px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:900px){

.sidebar{
display:none;
}

.content{
margin-left:0;
}

}
/* ================= TOPBAR ================= */

.topbar{
background:#FFFFFF;
padding:15px 25px;
border-radius:12px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 6px 20px rgba(0,0,0,0.08);
margin-bottom:20px;
flex-shrink:0;

}

.topbar-left h4{
margin:0;
font-weight:600;
}

.topbar-left small{
color:#6b7280;
}

/* RIGHT SIDE */

.topbar-right{
display:flex;
align-items:center;
gap:18px;
}

/* NOTIFICATION ICON */

.notification{
position:relative;
font-size:20px;
color:#14213D;
text-decoration:none;
transition:0.3s;
}

.notification:hover{
color:#FCA311;
}

/* BADGE */

.notif-badge{
position:absolute;
top:-8px;
right:-10px;
background:#ef4444;
color:white;
border-radius:50%;
padding:3px 6px;
font-size:11px;
font-weight:600;
}

/* PROFILE BUTTON */

.profile-btn{
background:#FCA311;
color:#000;
padding:8px 14px;
border-radius:8px;
text-decoration:none;
font-weight:500;
transition:0.3s;
}

.profile-btn:hover{
background:#ffb733;
transform:translateY(-1px);
}

.container-fluid{
flex:1;
overflow-y:auto;
padding-top:10px;
}

/* ================= CHART CENTER FIX ================= */

.chart-container{
height:350px;
display:flex;
align-items:center;
justify-content:center;
position:relative;
}

.chart-container canvas{
max-width:350px !important;
max-height:350px !important;
}

</style>

</head>

<body>

<div class="main-container">

    <?php include "includes/sidebar.php"; ?>

    <div class="content">

        <?php include "includes/header.php"; ?>

        <div class="container-fluid mt-4">


            <!-- SUMMARY CARDS -->

            <div class="row">

                <div class="col-md-3">
                    <div class="card summary-card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Balance</h6>
                            <h3>₹ <?php echo number_format($total_balance,2); ?></h3>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card summary-card bg-success text-white">
                        <div class="card-body">
                            <h6>Income</h6>
                            <h3>₹ <?php echo number_format($total_income,2); ?></h3>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card summary-card bg-danger text-white">
                        <div class="card-body">
                            <h6>Expense</h6>
                            <h3>₹ <?php echo number_format($total_expense,2); ?></h3>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card summary-card bg-warning text-white">
                        <div class="card-body">
                            <h6>Savings</h6>
                            <h3>₹ <?php echo number_format($total_savings,2); ?></h3>
                        </div>
                    </div>
                </div>

            </div>



            <!-- CHARTS -->

            <!-- CHARTS -->

<div class="row mt-4">

    <div class="col-md-6">
        <div class="card p-3">
            <h5>Expense by Category</h5>

           <div class="chart-container">
    <canvas id="pieChart"></canvas>
</div>

        </div>
    </div>


    <div class="col-md-6">
        <div class="card p-3">
            <h5>Monthly Expense</h5>

            <div style="height:350px;">
                <canvas id="barChart"></canvas>
            </div>

        </div>
    </div>

    <div class="row mt-4">
    <div class="col-md-12">
        <div class="card p-3">
            <h5>Income vs Expense Trend (<?php echo date('Y'); ?>)</h5>

            <div style="height:350px;">
                <canvas id="trendChart"></canvas>
            </div>

        </div>
    </div>
</div>

</div>

<div class="row mt-4">

    <!-- RECENT TRANSACTIONS -->
    <div class="col-md-6">

        <div class="card p-3">

            <h5>Recent Transactions</h5>

            <table class="table">

                <tr>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($recent_result)) { ?>

                <tr>

                    <td>
                        <?php 
                        if($row['type']=="income"){
                        echo "<span class='badge bg-success'>Income</span>";
                        }
                        else{
                        echo "<span class='badge bg-danger'>Expense</span>";
                        }
                        ?>
                    </td>

                    <td><?php echo htmlspecialchars($row['source']); ?></td>

                    <td>
                        <?php 
                        if($row['type']=="income"){
                        echo "<span class='text-success'>+ ₹ ".number_format($row['amount'],2)."</span>";
                        }
                        else{
                        echo "<span class='text-danger'>- ₹ ".number_format($row['amount'],2)."</span>";
                        }
                        ?>
                    </td>

                    <td><?php echo $row['date']; ?></td>

                </tr>

                <?php } ?>

            </table>

        </div>

    </div>



    <!-- BUDGET PROGRESS -->
    <div class="col-md-6">

        <div class="card p-3">

            <h5>Monthly Budget</h5>

            <p>Total Budget: ₹ <?php echo number_format($total_budget,2); ?></p>

            <p>Used: ₹ <?php echo number_format($monthly_expense,2); ?></p>

            <div class="progress" style="height:25px;">

                <div class="progress-bar bg-success"
                style="width: <?php echo $budget_percent; ?>%;">

                <?php echo round($budget_percent); ?>%

                </div>

            </div>

            <br>

            <p>
            Remaining: ₹ <?php echo number_format($total_budget - $monthly_expense,2); ?>
            </p>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    // PIE CHART

    var pieCanvas = document.getElementById("pieChart");

    if (pieCanvas) {

        new Chart(pieCanvas, {

            type: "pie",

            data: {

                labels: <?php echo json_encode($cat_labels); ?>,

                datasets: [{

                    data: <?php echo json_encode($cat_values); ?>,

                    backgroundColor: [

                        "#6c63ff",
                        "#1cc88a",
                        "#36b9cc",
                        "#f6c23e",
                        "#e74a3b",
                        "#858796",
                        "#fd7e14"

                    ]

                }]

            },

            options: {

                responsive: true,

                plugins: {

                    legend: {
                        position: "bottom"
                    }

                }

            }

        });

    }



    // BAR CHART

    var barCanvas = document.getElementById("barChart");

    if (barCanvas) {

        new Chart(barCanvas, {

            type: "bar",

            data: {

                labels: <?php echo json_encode($month_labels); ?>,

                datasets: [{

                    label: "Monthly Expense",

                    data: <?php echo json_encode($month_values); ?>,

                    backgroundColor: "#6c63ff",

                    borderRadius: 8

                }]

            },

            options: {

                responsive: true,

                scales: {

                    y: {
                        beginAtZero: true
                    }

                }

            }

        });

    }

});

// TREND CHART

var trendCanvas = document.getElementById("trendChart");

if (trendCanvas) {

    new Chart(trendCanvas, {

        type: "line",

        data: {

            labels: <?php echo json_encode($trend_labels); ?>,

            datasets: [

                {
                    label: "Income",
                    data: <?php echo json_encode($trend_income); ?>,
                    borderColor: "#1cc88a",
                    backgroundColor: "rgba(28,200,138,0.1)",
                    tension: 0.4,
                    fill: true
                },

                {
                    label: "Expense",
                    data: <?php echo json_encode($trend_expense); ?>,
                    borderColor: "#e74a3b",
                    backgroundColor: "rgba(231,74,59,0.1)",
                    tension: 0.4,
                    fill: true
                }

            ]

        },

        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "top"
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }

    });

}

</script>

</body>

</html>