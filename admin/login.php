<?php
session_start();
include "../config/database.php";

if(isset($_SESSION['admin_id'])){
    header("Location: dashboard.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){

        $admin = mysqli_fetch_assoc($result);

        if(password_verify($password, $admin['password'])){

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

        log_admin_action($conn, $admin['id'], "Admin logged in");
            header("Location: dashboard.php");
            exit();

        }else{
            $error = "Invalid password";
        }

    }else{
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#14213D,#000000);
font-family:'Inter','Segoe UI',sans-serif;
margin:0;
}

/* LOGIN CARD */

.login-card{
width:420px;
background:#FFFFFF;
border:none;
border-radius:18px;
padding:35px 30px;
box-shadow:0 15px 35px rgba(0,0,0,0.35);
}

/* TITLE */

.login-title{
text-align:center;
font-weight:600;
margin-bottom:25px;
color:#14213D;
}

/* ADMIN BADGE */

.admin-badge{
background:#FCA311;
color:#000;
font-size:12px;
padding:5px 10px;
border-radius:20px;
margin-left:8px;
}

/* INPUT GROUP */

.input-group-text{
background:#f1f5f9;
border:none;
}

/* INPUT */

.form-control{
border-radius:8px;
padding:10px;
}

/* LOGIN BUTTON */

.btn-login{
background:#FCA311;
border:none;
font-weight:600;
color:#000;
padding:10px;
border-radius:8px;
transition:0.25s;
}

.btn-login:hover{
background:#ffb733;
}

/* FOOTER */

.login-footer{
text-align:center;
margin-top:15px;
font-size:13px;
color:#6b7280;
}

</style>

</head>

<body>

<div class="login-card">

<h4 class="login-title">

<i class="fa-solid fa-user-shield"></i>

Admin Login

<span class="admin-badge">Secure</span>

</h4>

<?php if(isset($error)){ ?>

<div class="alert alert-danger text-center">

<i class="fa-solid fa-triangle-exclamation"></i>

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">Email Address</label>

<div class="input-group">

<span class="input-group-text">

<i class="fa-solid fa-envelope"></i>

</span>

<input type="email" name="email" class="form-control" required>

</div>

</div>


<div class="mb-3">

<label class="form-label">Password</label>

<div class="input-group">

<span class="input-group-text">

<i class="fa-solid fa-lock"></i>

</span>

<input type="password" name="password" class="form-control" required>

</div>

</div>


<button type="submit" class="btn btn-login w-100">

<i class="fa-solid fa-right-to-bracket"></i>

Login to Admin Panel

</button>

</form>

<div class="login-footer">

Expense Management System — Admin Access

</div>

</div>

</body>

</html>