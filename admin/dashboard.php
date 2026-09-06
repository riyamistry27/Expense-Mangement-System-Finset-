<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

/* ================= DASHBOARD DATA ================= */

// Total Users
$user_q = "SELECT COUNT(*) as total FROM users";
$user_r = mysqli_fetch_assoc(mysqli_query($conn,$user_q));
$total_users = isset($user_r['total']) ? $user_r['total'] : 0;

// Total Categories
$cat_q = "SELECT COUNT(*) as total FROM categories";
$cat_r = mysqli_fetch_assoc(mysqli_query($conn,$cat_q));
$total_categories = isset($cat_r['total']) ? $cat_r['total'] : 0;

// Active Users (Last 30 days login)
             $active_q = "SELECT COUNT(*) as total FROM users 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$active_r = mysqli_fetch_assoc(mysqli_query($conn,$active_q));
$active_users = isset($active_r['total']) ? $active_r['total'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#f4f6f9;
font-family:'Inter','Segoe UI',sans-serif;
color:#111827;
}

/* ===== MAIN LAYOUT ===== */

.main-container{
display:flex;
min-height:100vh;
}

/* ===== SIDEBAR ===== */

.sidebar{
width:230px;
height:100vh;
background:#1e293b;
color:white;
padding:22px;
position:fixed;
left:0;
top:0;
display:flex;
flex-direction:column;
}

.sidebar h4{
margin-bottom:28px;
font-weight:600;
letter-spacing:0.4px;
}

/* SIDEBAR LINKS */

.sidebar a{
display:flex;
align-items:center;
gap:10px;
color:#cbd5e1;
padding:12px 14px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
font-size:14px;
transition:all 0.25s ease;
}

.sidebar a i{
width:18px;
text-align:center;
font-size:15px;
}

.sidebar a:hover{
background:#334155;
color:white;
transform:translateX(3px);
}

.sidebar a.text-danger:hover{
background:#ef4444;
color:white;
}

/* ===== CONTENT ===== */

.content{
margin-left:230px;
width:calc(100% - 230px);
padding:28px;
}

/* ===== TOPBAR ===== */

.topbar{
background:white;
padding:14px 20px;
border-radius:10px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 3px 10px rgba(0,0,0,0.06);
margin-bottom:25px;
}

.topbar h5{
margin:0;
font-weight:500;
}

.topbar .badge{
font-size:12px;
padding:6px 10px;
}

/* ===== DASHBOARD CARDS ===== */

.card{
border:none;
border-radius:14px;
box-shadow:0 6px 16px rgba(0,0,0,0.06);
transition:all 0.25s ease;
background:white;
}

.card:hover{
transform:translateY(-5px);
box-shadow:0 10px 22px rgba(0,0,0,0.08);
}

.card h6{
font-size:13px;
color:#6b7280;
margin-bottom:6px;
display:flex;
align-items:center;
gap:6px;
}

.card h6 i{
color:#64748b;
}

.card h3{
font-weight:600;
font-size:26px;
margin:0;
color:#111827;
}

/* ===== STATUS TEXT ===== */

.text-success{
font-weight:600;
}

/* ===== INFO CARD ===== */

.card p{
font-size:14px;
color:#6b7280;
line-height:1.6;
margin:0;
}

/* ===== GRID SPACING ===== */

.row{
gap:10px;
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

<!-- SIDEBAR -->
<div class="sidebar">
<h4>Admin Panel</h4>

<a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>

<a href="users.php"><i class="fa-solid fa-users"></i> Users</a>

<a href="categories.php"><i class="fa-solid fa-layer-group"></i> Categories</a>

<a href="messages.php">
<i class="fa-solid fa-envelope"></i> User Messages
</a>

<a href="logs.php"><i class="fa-solid fa-file-lines"></i> Audit Logs</a>

<a href="logout.php" class="text-danger">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>
</div>


<!-- CONTENT -->
<div class="content">

<div class="topbar">
<h5>Welcome, <?php echo $_SESSION['admin_name']; ?></h5>
<span class="badge bg-success">Admin Access</span>
</div>


<!-- DASHBOARD CARDS -->

<div class="row">

<div class="col-md-3">
<div class="card p-4">
<h6><i class="fa-solid fa-users"></i> Total Users</h6>
<h3><?php echo $total_users; ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-4">
<h6><i class="fa-solid fa-user-check"></i> Active Users</h6>
<h3><?php echo $active_users; ?>3</h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-4">
<h6><i class="fa-solid fa-layer-group"></i> Total Categories</h6>
<h3><?php echo $total_categories; ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-4">
<h6>System Status</h6>
<h3 class="text-success"><i class="fa-solid fa-server"></i>Running</h3>
</div>
</div>

</div>


<!-- INFO SECTION -->

<div class="card p-4 mt-4">
<h5>Platform Overview</h5>
<p class="text-muted">
Admin can manage users and global categories. 
User financial data remains private and inaccessible.
</p>
</div>


</div>
</div>

</body>
</html>