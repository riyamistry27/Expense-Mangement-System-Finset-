<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    exit();
}

$user_id = $_SESSION['user_id'];
$id = intval($_GET['id']);

$recurring = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM recurring_transactions
    WHERE id='$id' AND user_id='$user_id'
"));

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $amount = $_POST['amount'];
    $frequency = $_POST['frequency'];
    $next_run = $_POST['next_run'];
    $description = $_POST['description'];

    mysqli_query($conn,"
        UPDATE recurring_transactions
        SET amount='$amount',
            frequency='$frequency',
            next_run='$next_run',
            description='$description'
        WHERE id='$id' AND user_id='$user_id'
    ");

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Recurring</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
display:flex;
flex-direction:column;
align-items:center;
}

/* PAGE HEADER */

.page-header{
width:700px;
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.page-header h3{
margin:0;
font-weight:600;
}

/* CARD */

.form-card{
background:white;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
padding:35px;
width:700px;
}

/* INPUT */

.form-control{
border-radius:8px;
padding:10px;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 0.15rem rgba(252,163,17,0.25);
}

/* BUTTONS */

.btn-update{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 18px;
border-radius:8px;
}

.btn-update:hover{
background:#ffb733;
}

.btn-cancel{
background:#6b7280;
border:none;
color:white;
padding:8px 18px;
border-radius:8px;
}

.btn-cancel:hover{
background:#4b5563;
}

.btn-back{
background:#6b7280;
border:none;
color:white;
padding:8px 18px;
border-radius:8px;
}

.btn-back:hover{
background:#4b5563;
}

</style>
</head>
<body>

<div class="main-container">

<?php include "../includes/sidebar.php"; ?>

<div class="content">

<div class="page-header">

<h3><i class="fa-solid fa-repeat"></i> Edit Recurring Transaction</h3>

<a href="index.php" class="btn btn-back">
<i class="fa-solid fa-arrow-left"></i> Back
</a>

</div>

<div class="form-card">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">
<label>Amount</label>
<input type="number"
step="0.01"
name="amount"
value="<?php echo $recurring['amount']; ?>"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label>Next Run</label>
<input type="date"
name="next_run"
value="<?php echo $recurring['next_run']; ?>"
class="form-control"
required>
</div>

</div>

<div class="mb-3">
<label>Description</label>
<input type="text"
name="description"
value="<?php echo $recurring['description']; ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Frequency</label>

<select name="frequency" class="form-control">

<option value="monthly"
<?php if($recurring['frequency']=='monthly') echo "selected"; ?>>
Monthly
</option>

<option value="weekly"
<?php if($recurring['frequency']=='weekly') echo "selected"; ?>>
Weekly
</option>

<option value="yearly"
<?php if($recurring['frequency']=='yearly') echo "selected"; ?>>
Yearly
</option>

</select>

</div>

<button class="btn btn-update">
<i class="fa-solid fa-check"></i> Update
</button>

<a href="index.php" class="btn btn-cancel">
Cancel
</a>

</form>

</div>

</div>

</div>

</body>
</html>