
<style>

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

/* ===== LOGO ===== */

.logo{
margin-bottom:30px;
font-size:20px;
font-weight:600;
color:#FCA311;
}

/* ===== SIDEBAR LINKS ===== */

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

/* ===== SIDEBAR DIVIDER ===== */

.sidebar hr{
border-color:rgba(255,255,255,0.1);
margin:20px 0;
}

/* ===== RESPONSIVE ===== */

@media(max-width:992px){

.sidebar{
width:200px;
}

}

</style>
    <?php

$user_id = $_SESSION['user_id'] ?? 0;

$msg_count = 0;



?>

   <div class="sidebar">

    <div class="logo">
        <h4><i class="fa-solid fa-wallet"></i> FinSet</h4>
    </div>

    <a href="/expense-management/dashboard.php">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>

    <a href="/expense-management/income/list.php">
        <i class="fa-solid fa-money-bill-trend-up"></i> Income
    </a>

    <a href="/expense-management/expenses/list.php">
        <i class="fa-solid fa-receipt"></i> Expenses
    </a>

    <a href="/expense-management/recurring/index.php">
        <i class="fa-solid fa-repeat"></i> Recurring
    </a>

    <a href="/expense-management/savings/index.php">
        <i class="fa-solid fa-piggy-bank"></i> Savings
    </a>

    <a href="/expense-management/budget/set.php">
        <i class="fa-solid fa-wallet"></i> Budget
    </a>

    <a href="/expense-management/reports/monthly.php">
        <i class="fa-solid fa-chart-pie"></i> Reports
    </a>

    <hr>

    <a href="/expense-management/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>

    <a href="/expense-management/support/contact.php">
<i class="fa-solid fa-envelope"></i> Contact Admin
</a>

<a href="/expense-management/support/messages.php">
<i class="fa-solid fa-comments"></i> My Messages

<?php if($msg_count > 0){ ?>



<?php } ?>

</a>
</div>

</div>