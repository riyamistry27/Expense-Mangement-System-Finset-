<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);

$query = "SELECT * FROM income WHERE id='$id'";
$result = mysqli_query($conn, $query);

if(!$result){
    die("Query failed");
}

$row = mysqli_fetch_assoc($result);

if(!$row){
    echo "Income record not found";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'];

    $update = "UPDATE income 
               SET source='$source',
                   amount='$amount',
                   income_date='$income_date'
               WHERE id='$id'";

    mysqli_query($conn, $update);

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Income</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
}

/* ===== LAYOUT ===== */

.main-container{
display:flex;
}

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

/* ===== CONTENT ===== */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:25px;
}

/* ===== CARD ===== */

.form-card{
background:#FFFFFF;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
padding:35px;
width:650px;
}

/* ===== INPUT ===== */

.form-control{
border-radius:8px;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 0.15rem rgba(252,163,17,0.25);
}

/* ===== BUTTONS ===== */

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


<div class="d-flex justify-content-center mt-4">

<div class="form-card">

<h4 class="mb-4">
<i class="fa-solid fa-pen"></i> Edit Income
</h4>

<form method="POST">

<div class="mb-3">
<label>Source</label>
<input 
type="text"
name="source"
value="<?php echo htmlspecialchars($row['source'] ?? ''); ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Amount</label>
<input 
type="number"
name="amount"
value="<?php echo htmlspecialchars($row['amount'] ?? ''); ?>"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Date</label>
<input 
type="date"
name="income_date"
value="<?php echo htmlspecialchars($row['income_date'] ?? ''); ?>"
class="form-control"
required>
</div>

<button class="btn btn-update">
<i class="fa-solid fa-check"></i> Update
</button>

<a href="list.php" class="btn btn-back">
    <i class="fa-solid fa-arrow-left"></i>
Back
</a>

</form>

</div>

</div>

</div>

</div>

</body>
</html>