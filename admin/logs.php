<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/database.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

/* ================= FETCH LOGS ================= */

$query = "
SELECT admin_logs.*, admins.name 
FROM admin_logs
JOIN admins ON admin_logs.admin_id = admins.id
ORDER BY admin_logs.created_at DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Audit Logs</title>
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
font-weight:600;
}

.topbar .badge{
font-size:13px;
padding:6px 10px;
border-radius:6px;
}

/* ===== CARD ===== */

.card{
border:none;
border-radius:14px;
box-shadow:0 6px 16px rgba(0,0,0,0.06);
background:white;
}

/* ===== TABLE ===== */

.table{
margin-bottom:0;
font-size:14px;
}

.table thead{
background:#f1f5f9;
}

.table th{
font-weight:600;
color:#374151;
border:none;
padding:12px;
}

.table td{
vertical-align:middle;
border-top:1px solid #f1f5f9;
padding:12px;
}

/* TABLE HOVER */

.table-hover tbody tr:hover{
background:#f9fafb;
}

/* ===== ACTION TEXT STYLE ===== */

.table td:nth-child(3){
color:#374151;
font-weight:500;
}

/* ===== EMPTY STATE ===== */

.text-muted{
font-size:14px;
padding:15px;
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

<div class="content">

<div class="topbar">
<h5>Audit Logs</h5>
<span class="badge bg-secondary">
Total: <?php echo mysqli_num_rows($result); ?>
</span>
</div>

<div class="card p-4">

<div class="table-responsive">
<table class="table table-hover">

<thead>
<tr>
<th>ID</th>
<th>Admin</th>
<th>Action</th>
<th>Date & Time</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($result) > 0){ ?>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['action']); ?></td>
<td><?php echo date("d M Y H:i",strtotime($row['created_at'])); ?></td>
</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="4" class="text-center text-muted">
No logs found
</td>
</tr>

<?php } ?>

</tbody>
</table>
</div>

</div>
</div>
</div>

</body>
</html>