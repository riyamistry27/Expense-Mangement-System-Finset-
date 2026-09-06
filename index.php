<!DOCTYPE html>
<html>
<head>
<title>FinSet - Smart Expense Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ================= GLOBAL ================= */

body{
margin:0;
font-family:'Inter','Segoe UI',sans-serif;
scroll-behavior:smooth;
background:#E5E5E5;
color:#000;
}

/* ================= HEADER ================= */

.navbar{
padding:15px 40px;
background:rgba(255,255,255,0.95);
backdrop-filter:blur(10px);
box-shadow:0 3px 15px rgba(0,0,0,0.1);
}

.navbar-brand{
font-weight:700;
font-size:24px;
color:#14213D !important;
letter-spacing:1px;
}

.btn-outline-primary{
border-color:#FCA311;
color:#FCA311;
font-weight:600;
}

.btn-outline-primary:hover{
background:#FCA311;
border-color:#FCA311;
color:#000;
}

.btn-primary{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
}

.btn-primary:hover{
background:#ffb733;
color:#000;
}

/* ================= HERO ================= */

.hero{
height:100vh;
display:flex;
align-items:center;
justify-content:center;
text-align:center;
padding:0 20px;
color:#FFFFFF;
position:relative;
overflow:hidden;

background:
linear-gradient(rgba(20,33,61,0.85), rgba(20,33,61,0.9)),
url("assets/images/finance-banner.jpg");

background-size:cover;
background-position:center;
}

.hero h1{
font-size:56px;
font-weight:700;
margin-bottom:20px;
}

.hero p{
font-size:20px;
color:#E5E5E5;
margin-bottom:30px;
}

.btn-hero{
background:#FCA311;
color:#000;
font-weight:600;
padding:14px 36px;
border-radius:40px;
border:none;
transition:0.3s;
box-shadow:0 8px 20px rgba(0,0,0,0.3);
}

.btn-hero:hover{
background:#ffb733;
transform:translateY(-3px);
}

/* ================= FLOATING SHAPES ================= */

.shape{
position:absolute;
border-radius:50%;
filter:blur(120px);
opacity:0.35;
animation:float 10s infinite ease-in-out;
}

.shape1{
width:350px;
height:350px;
background:#FCA311;
top:-120px;
left:-120px;
}

.shape2{
width:400px;
height:400px;
background:#FFFFFF;
bottom:-150px;
right:-150px;
animation-delay:3s;
}

@keyframes float{
0%{transform:translateY(0)}
50%{transform:translateY(-50px)}
100%{transform:translateY(0)}
}

/* ================= FEATURES ================= */

.section{
padding:100px 20px;
background:#E5E5E5;
}

.section-title{
text-align:center;
margin-bottom:60px;
}

.section-title h2{
font-weight:700;
font-size:36px;
color:#14213D;
}

.section-title p{
color:#555;
}

.feature-card{
border:none;
border-radius:18px;
box-shadow:0 8px 25px rgba(0,0,0,0.08);
padding:40px 30px;
transition:0.3s;
background:#FFFFFF;
text-align:center;
}

.feature-card:hover{
transform:translateY(-10px);
box-shadow:0 15px 35px rgba(0,0,0,0.15);
}

.feature-icon{
font-size:35px;
color:#FCA311;
margin-bottom:15px;
}

/* ================= CTA ================= */

.cta{
background:#14213D;
color:#FFFFFF;
text-align:center;
padding:80px 20px;
}

.cta h2{
font-size:36px;
font-weight:700;
}

.cta p{
margin-top:10px;
opacity:0.9;
}

.cta .btn{
background:#FCA311;
color:#000;
font-weight:600;
padding:14px 36px;
border-radius:40px;
}

.cta .btn:hover{
background:#ffb733;
}

/* ================= FOOTER ================= */

footer{
background:#000000;
color:#E5E5E5;
padding:15px 20px;
text-align:center;
font-size:14px;
}

footer p{
margin:5px 0;
}

</style>

</head>

<body>

<!-- ================= HEADER ================= -->

<nav class="navbar navbar-expand-lg fixed-top">
<div class="container-fluid">

<a class="navbar-brand" href="#">FinSet</a>

<div>
<a href="login.php" class="btn btn-outline-primary me-2">Login</a>
<a href="register.php" class="btn btn-primary">Register</a>
</div>

</div>
</nav>


<!-- ================= HERO ================= -->

<section class="hero">

<div class="shape shape1"></div>
<div class="shape shape2"></div>

<div>

<h1>Manage Your Money Smartly</h1>

<p>Track Income, Expenses, Savings & Budgets in One Place</p>

<a href="register.php" class="btn btn-hero">Get Started</a>

</div>

</section>


<!-- ================= FEATURES ================= -->

<section class="section">

<div class="container">

<div class="section-title">
<h2>Powerful Features</h2>
<p>Everything you need to manage your finances efficiently</p>
</div>

<div class="row g-4">

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon">
<i class="fa-solid fa-wallet"></i>
</div>
<h4>Income Tracking</h4>
<p>Add, edit, and manage your income sources while tracking your earnings efficiently.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon">
<i class="fa-solid fa-receipt"></i>
</div>
<h4>Expense Management</h4>
<p>Keep track of your daily expenses with smart filters and organized expense reports.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon">
<i class="fa-solid fa-chart-pie"></i>
</div>
<h4>Budget Control</h4>
<p>Set monthly budgets and monitor spending progress.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon">
<i class="fa-solid fa-piggy-bank"></i>
</div>
<h4>Savings Overview</h4>
<p>Calculate savings rate and monitor financial growth.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon">
<i class="fa-solid fa-chart-line"></i>
</div>
<h4>Interactive Dashboard</h4>
<p>Interactive charts and clear summaries for quick financial insights.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon">
<i class="fa-solid fa-file-invoice"></i>
</div>
<h4>Monthly Reports</h4>
<p>Generate monthly expense reports categorized for better financial tracking.</p>
</div>
</div>

</div>

</div>

</section>


<!-- ================= CTA ================= -->

<section class="cta">

<h2>Take Control of Your Finances Today</h2>
<p>Join FinSet and start managing smarter</p>

<a href="register.php" class="btn mt-3">Create Free Account</a>

</section>


<!-- ================= FOOTER ================= -->

<footer>

<p>© <?php echo date("Y"); ?> FinSet. All Rights Reserved.</p>
<p>Designed for Smart Financial Management</p>

</footer>

</body>
</html>