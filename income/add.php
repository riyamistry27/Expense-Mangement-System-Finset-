<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'];

    $query = "INSERT INTO income (user_id, source, amount, income_date)
              VALUES ('$user_id', '$source', '$amount', '$income_date')";

    if (mysqli_query($conn, $query)) {
        $success = "Income added successfully";
    } else {
        $error = "Error adding income";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Income</title>

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

.logo{
margin-bottom:30px;
font-size:20px;
font-weight:600;
color:#FCA311;
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
font-size:15px;
transition:all 0.25s ease;
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
display:flex;
justify-content:center;
}

/* ===== FORM CARD ===== */

.form-card{
background:#FFFFFF;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
padding:30px;
width:450px;
}

.form-card h3{
margin-bottom:20px;
font-weight:600;
}

/* ===== FORM LABEL ===== */

label{
font-weight:500;
margin-bottom:5px;
}

/* ===== INPUT ===== */

.form-control{
border-radius:8px;
border:1px solid #ddd;
padding:10px;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 0.15rem rgba(252,163,17,0.25);
}

/* ===== BUTTONS ===== */

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

.btn-back{
background:#6b7280;
border:none;
color:white;
padding:8px 16px;
border-radius:8px;
}

.btn-back:hover{
background:#4b5563;
}

/* ===== ALERTS ===== */

.alert{
border-radius:8px;
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

    <?php include "../includes/sidebar.php"; ?>

    <div class="content">


        <div class="container mt-4 d-flex justify-content-center">

<div class="form-card">
                <h3>Add Income</h3>

                <?php
                if(isset($success)) echo "<div class='alert alert-success'>$success</div>";
                if(isset($error)) echo "<div class='alert alert-danger'>$error</div>";
                ?>

                <form method="POST">

                    <div class="mb-3">
                        <label>Income Source</label>
                        <input type="text" name="source" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="income_date" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-add">Add Income</button>

<a href="list.php" class="btn btn-back">Back</a>
                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>