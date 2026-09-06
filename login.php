<?php
session_start();
include "config/database.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$query);
$user = mysqli_fetch_assoc($result);

if($user && password_verify($password,$user['password'])){
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
header("Location: dashboard.php");
exit();
}
else{
$error = "Invalid email or password";
}
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login - FinSet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ================= GLOBAL ================= */

body{
margin:0;
height:100vh;
display:flex;
font-family:'Inter','Segoe UI',sans-serif;
background:linear-gradient(135deg,#000000,#14213D);
color:#FFFFFF;
}


/* ================= LEFT HERO ================= */

.hero{
flex:0 0 55%;
display:flex;
align-items:center;
justify-content:flex-start;
padding-left:120px;
}
.hero-content{
max-width:500px;
}

.hero h1{
font-size:42px;
font-weight:700;
margin-bottom:15px;
}

.hero span{
color:#FCA311;
}

.hero p{
color:#E5E5E5;
font-size:16px;
}

/* floating glow */

.hero::before{
content:"";
position:absolute;
width:350px;
height:350px;
background:#FCA311;
filter:blur(150px);
opacity:0.25;
top:-120px;
left:-120px;
}

/* ================= LOGIN AREA ================= */

.login-area{
flex:0 0 45%;
display:flex;
align-items:center;
justify-content:flex-start;
}

/* ================= LOGIN CARD ================= */

.login-card{
width:420px;
padding:45px;
border-radius:18px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(20px);
box-shadow:0 25px 60px rgba(0,0,0,0.4);
}

.login-card h3{
text-align:center;
margin-bottom:25px;
font-weight:600;
}

/* ================= INPUT GROUP ================= */

.input-group-text{
background:#FCA311;
border:none;
color:#000;
}

.form-control{
border:none;
padding:12px;
}

.form-control:focus{
box-shadow:none;
border:1px solid #FCA311;
}

/* ================= BUTTON ================= */

.btn-login{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:12px;
border-radius:10px;
width:100%;
transition:0.3s;
}

.btn-login:hover{
background:#ffb733;
transform:translateY(-2px);
}

/* ================= LINKS ================= */

.link{
color:#FCA311;
text-decoration:none;
font-weight:500;
}

.link:hover{
text-decoration:underline;
}

/* ================= ALERT ================= */

.alert{
border-radius:10px;
}

/* ================= RESPONSIVE ================= */

@media(max-width:900px){

.hero{
display:none;
}

.login-area{
width:100%;
}

}

/* divider */

.divider{
display:flex;
align-items:center;
margin:18px 0;
color:#ccc;
font-size:13px;
}

.divider::before,
.divider::after{
content:"";
flex:1;
height:1px;
background:#555;
}

.divider span{
padding:0 10px;
}

/* google button */

.btn-google{
background:#FFFFFF;
color:#000;
border:none;
width:100%;
padding:11px;
border-radius:10px;
font-weight:500;
transition:0.3s;
}

.btn-google:hover{
background:#f3f3f3;
}

</style>

</head>

<body>


<!-- LEFT HERO -->

<div class="hero">

<div class="hero-content">

<h1>Welcome to <span>FinSet</span></h1>

<p>
Track expenses, manage income and grow your savings with smart financial insights.
</p>

</div>

</div>


<!-- LOGIN AREA -->

<div class="login-area">

<div class="login-card">

<h3>Login</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-envelope"></i>
</span>

<input type="email" name="email" class="form-control" placeholder="Email address" required>

</div>


<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-lock"></i>
</span>

<input type="password" name="password" class="form-control" placeholder="Password" required>

</div>


<button type="submit" class="btn btn-login">
Login
</button>

<div class="divider">
<span>or</span>
</div>

<button type="button" class="btn-google" onclick="window.location.href='google-login.php'">
<i class="fa-brands fa-google"></i> Continue with Google
</button>

</form>


<div class="text-center mt-3">

<a href="register.php" class="link">Create new account</a>

</div>

</div>

</div>

</body>
</html>