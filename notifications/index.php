<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit();
}

$user_id = $_SESSION['user_id'];


/* MARK NOTIFICATION AS READ */

if(isset($_GET['read'])){

$id = intval($_GET['read']);

mysqli_query($conn,"
UPDATE notifications
SET is_read=1
WHERE id='$id' AND user_id='$user_id'
");

header("Location: index.php");
exit();

}


/* FETCH NOTIFICATIONS */

$result = mysqli_query($conn,"
SELECT * FROM notifications
WHERE user_id='$user_id'
ORDER BY created_at DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<title>Notifications</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000000;
}


/* ===== MAIN LAYOUT ===== */

.main-container{
display:flex;
}


/* ===== CONTENT ===== */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:30px;
display:flex;
justify-content:center;
}


/* ===== PAGE CONTAINER ===== */

.notifications-container{
width:100%;
max-width:850px;
}


/* ===== PAGE HEADER ===== */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.page-header h4{
margin:0;
font-weight:600;
}


/* ===== CARD ===== */

.card{
border:none;
border-radius:16px;
background:#FFFFFF;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
padding:5px;
}


/* ===== NOTIFICATION ITEM ===== */

.notification-item{
display:flex;
justify-content:space-between;
align-items:center;
padding:16px 20px;
border-bottom:1px solid #f1f5f9;
transition:0.25s;
}

.notification-item:last-child{
border-bottom:none;
}

.notification-item:hover{
background:#f8fafc;
}


/* ===== MESSAGE ===== */

.notification-message{
font-size:15px;
font-weight:500;
}

.notification-time{
font-size:12px;
color:#6b7280;
margin-top:3px;
}


/* ===== BUTTON ===== */

.btn-read{
background:#FCA311;
border:none;
color:#000;
font-size:13px;
font-weight:500;
padding:6px 12px;
border-radius:6px;
transition:0.25s;
}

.btn-read:hover{
background:#ffb733;
}


/* ===== BADGE ===== */

.badge-read{
background:#16a34a;
color:white;
padding:6px 10px;
border-radius:6px;
font-size:12px;
}


/* ===== EMPTY STATE ===== */

.empty-state{
text-align:center;
padding:40px 20px;
color:#6b7280;
}

.empty-state i{
font-size:40px;
margin-bottom:10px;
color:#94a3b8;
}

</style>

</head>

<body>


<div class="main-container">

<?php include "../includes/sidebar.php"; ?>


<div class="content">


<div class="notifications-container">


<div class="page-header">

<h4>
<i class="fa-solid fa-bell"></i>
Notifications
</h4>

<a href="../dashboard/index.php" class="btn btn-sm btn-dark">
Back
</a>

</div>



<div class="card">


<?php if(mysqli_num_rows($result)==0){ ?>

<div class="empty-state">

<i class="fa-regular fa-bell-slash"></i>

<p>No notifications available</p>

</div>

<?php } ?>


<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="notification-item">

<div>

<div class="notification-message">

<?= htmlspecialchars($row['message']) ?>

</div>

<div class="notification-time">

<?= date("d M Y H:i",strtotime($row['created_at'])) ?>

</div>

</div>


<div>

<?php if(!$row['is_read']){ ?>

<a href="?read=<?= $row['id'] ?>" class="btn btn-read">

<i class="fa-solid fa-check"></i> Mark Read

</a>

<?php }else{ ?>

<span class="badge badge-read">

<i class="fa-solid fa-check"></i> Read

</span>

<?php } ?>

</div>

</div>

<?php } ?>


</div>

</div>

</div>

</div>

</body>
</html>