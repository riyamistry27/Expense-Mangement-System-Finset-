<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST['goal_name'];
    $target = $_POST['target_amount'];
    $date = $_POST['target_date'];

    mysqli_query($conn,"
        INSERT INTO savings_goals
        (user_id,goal_name,target_amount,target_date)
        VALUES
        ('$user_id','$name','$target','$date')
    ");

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Savings Goal</title>

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
padding:30px;
}

/* FORM CARD */

.form-card{
max-width:520px;
margin:auto;
background:white;
border-radius:16px;
padding:30px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* HEADER */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

/* INPUT ICON */

.input-group-text{
background:#f1f5f9;
border:none;
}

/* BUTTONS */

.btn-create{
background:#22c55e;
border:none;
border-radius:8px;
padding:8px 16px;
}

.btn-create:hover{
background:#16a34a;
}

.btn-back{
border-radius:8px;
}

</style>

</head>

<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="page-header">

<h3>
<i class="fa-solid fa-bullseye"></i>
Create Savings Goal
</h3>

<a href="index.php" class="btn btn-outline-dark btn-back">
<i class="fa-solid fa-arrow-left"></i> Back
</a>

</div>


<div class="form-card">

<form method="POST">

<div class="mb-3">

<label class="form-label">Goal Name</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-flag"></i>
</span>

<input type="text"
name="goal_name"
class="form-control"
placeholder="Example: Buy Laptop"
required>

</div>

</div>


<div class="mb-3">

<label class="form-label">Target Amount</label>

<div class="input-group">

<span class="input-group-text">
₹
</span>

<input type="number"
step="0.01"
name="target_amount"
class="form-control"
placeholder="50000"
required>

</div>

</div>


<div class="mb-3">

<label class="form-label">Target Date</label>

<div class="input-group">

<span class="input-group-text">
<i class="fa-solid fa-calendar"></i>
</span>

<input type="date"
name="target_date"
class="form-control"
required>

</div>

</div>


<div class="d-flex gap-2">

<button class="btn btn-create">
<i class="fa-solid fa-check"></i> Create Goal
</button>

<a href="index.php" class="btn btn-secondary">
Cancel
</a>

</div>

</form>

</div>

</div>

</div>

</body>

</html>