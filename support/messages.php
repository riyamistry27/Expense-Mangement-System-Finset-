<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn,"
SELECT * FROM messages
WHERE user_id='$user_id'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>My Messages</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000;
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
flex-direction:column;
align-items:center;
}

/* ===== PAGE HEADER ===== */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.page-header h3{
font-weight:600;
margin:0;
}

/* ===== MESSAGE CONTAINER ===== */

.messages-container{
max-width:900px;
margin:0 auto;
width:100%;
}
/* ===== MESSAGE CARD ===== */

.message-card{
border:none;
border-radius:18px;
background:#FFFFFF;
box-shadow:0 8px 25px rgba(0,0,0,0.08);
padding:22px;
margin-bottom:18px;
transition:0.2s;
}

.message-card:hover{
transform:translateY(-2px);
}

/* ===== HEADER ===== */

.message-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:10px;
}

/* SUBJECT */

.message-subject{
font-weight:600;
font-size:16px;
}

/* ===== STATUS BADGES ===== */

.badge-pending{
background:#FCA311;
color:#000;
padding:6px 10px;
border-radius:6px;
font-size:12px;
}

.badge-replied{
background:#16a34a;
color:#fff;
padding:6px 10px;
border-radius:6px;
font-size:12px;
}

/* ===== MESSAGE BODY ===== */

.message-text{
margin-top:5px;
margin-bottom:10px;
color:#333;
}

/* ===== DATE ===== */

.message-date{
font-size:12px;
color:#6b7280;
}

/* ===== ADMIN REPLY ===== */

.admin-reply{
background:#f8fafc;
border-left:4px solid #FCA311;
padding:14px;
border-radius:8px;
margin-top:12px;
}

/* ===== EMPTY STATE ===== */

.empty-box{
text-align:center;
padding:40px;
background:#FFFFFF;
border-radius:16px;
box-shadow:0 6px 20px rgba(0,0,0,0.08);
}

.empty-box i{
font-size:40px;
color:#FCA311;
margin-bottom:10px;
}

/* ===== RESPONSIVE ===== */

@media(max-width:992px){

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

<h3>
<i class="fa-solid fa-comments"></i>
My Messages
</h3>

</div>


<div class="messages-container">

<?php if(mysqli_num_rows($result)==0){ ?>

<div class="empty-box">

<i class="fa-solid fa-envelope-open-text"></i>

<h5>No Messages Yet</h5>

<p class="text-muted">
You haven't contacted admin yet.
</p>

<a href="contact.php" class="btn btn-warning">
<i class="fa-solid fa-paper-plane"></i>
Contact Admin
</a>

</div>

<?php } ?>


<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="message-card">

<div class="message-header">

<div class="message-subject">
<i class="fa-solid fa-envelope"></i>
<?= htmlspecialchars($row['subject']) ?>
</div>

<div>

<?php if($row['status']=="pending"){ ?>

<span class="badge-pending">
Pending
</span>

<?php } else { ?>

<span class="badge-replied">
Replied
</span>

<?php } ?>

</div>

</div>


<div class="message-text">

<?= htmlspecialchars($row['message']) ?>

</div>


<div class="message-date">

<?= date("d M Y H:i",strtotime($row['created_at'])) ?>

</div>


<?php if(!empty($row['admin_reply'])){ ?>

<div class="admin-reply">

<strong>
<i class="fa-solid fa-user-shield"></i>
Admin Reply
</strong>

<p class="mb-0 mt-1">
<?= htmlspecialchars($row['admin_reply']) ?>
</p>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

</div>

</div>

</body>
</html>