<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch recurring
$result = mysqli_query($conn,"
    SELECT r.*, c.category_name 
    FROM recurring_transactions r
    LEFT JOIN categories c ON r.category_id=c.id
    WHERE r.user_id='$user_id'
    ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Recurring Transactions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
margin-bottom:25px;
}

.page-header h3{
margin:0;
font-weight:600;
}

/* ADD BUTTON */

.btn-add{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 16px;
border-radius:8px;
}

.btn-add:hover{
background:#ffb733;
}

/* CARD */

.table-card{
background:white;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
padding:20px;
}

/* TABLE */

.table thead{
background:#f8fafc;
}

.table th{
font-weight:600;
}

/* BUTTONS */

.btn-warning{
background:#FCA311;
border:none;
color:black;
}

.btn-warning:hover{
background:#ffb733;
}

.btn-info{
background:#0ea5e9;
border:none;
}

.btn-danger{
background:#ef4444;
border:none;
}

/* RESPONSIVE */

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

<div class="page-header">

<h3><i class="fa-solid fa-repeat"></i> Recurring Transactions</h3>

<a href="add.php" class="btn btn-add">
<i class="fa-solid fa-plus"></i> Add Recurring
</a>

</div>

<div class="card table-card">

<table class="table align-middle">

<thead>

<tr>
<th>Type</th>
<th>Amount</th>
<th>Category</th>
<th>Frequency</th>
<th>Next Run</th>
<th>Status</th>
<th>Upcoming</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo ucfirst($row['type']); ?></td>

<td>₹ <?php echo number_format($row['amount'],2); ?></td>

<td><?php echo $row['category_name'] ?? '-'; ?></td>

<td><?php echo ucfirst($row['frequency']); ?></td>

<td><?php echo $row['next_run']; ?></td>

<td>
<?php if($row['status']=='active'){ ?>
<span class="badge bg-success">Active</span>
<?php } else { ?>
<span class="badge bg-secondary">Paused</span>
<?php } ?>
</td>

<td>
<?php
$next = strtotime($row['next_run']);
$future = [];

for($i=0;$i<3;$i++){
    if($row['frequency']=='monthly'){
        $next = strtotime("+1 month",$next);
    }elseif($row['frequency']=='weekly'){
        $next = strtotime("+1 week",$next);
    }else{
        $next = strtotime("+1 year",$next);
    }
    $future[] = date("d M Y",$next);
}
echo implode("<br>",$future);
?>
</td>

<td>

<a href="edit.php?id=<?php echo $row['id']; ?>"
class="btn btn-sm btn-warning">Edit</a>

<a href="toggle.php?id=<?php echo $row['id']; ?>"
class="btn btn-sm btn-info">Toggle</a>

<a href="delete.php?id=<?php echo $row['id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this recurring rule?')">
Delete
</a>

</td>

</tr>

<?php } ?>
</tbody>
</table>

</div>

</div>

</div>

</body>

</html>