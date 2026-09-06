<?php
session_start();
include "config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $birthdate = $_POST['birthdate'];
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];

    $error = "";

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $error = "Mobile number must be exactly 10 digits";
    }

    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob)->y;

    if ($age < 18) {
        $error = "User must be at least 18 years old";
    }

    if (strlen($password) < 6 || strlen($password) > 8) {
        $error = "Password must be between 6 and 8 characters";
    }

    if (empty($error)) {

        $check_query = "SELECT * FROM users WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered";
        }
    }

    if (empty($error)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO users
                        (name, birthdate, email, mobile, password)
                        VALUES
                        ('$name', '$birthdate', '$email', '$mobile', '$hashed_password')";

        if (mysqli_query($conn, $insert_query)) {
            $success = "Registration successful. You can login now.";
        } else {
            $error = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register - FinSet</title>

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

/* ================= HERO ================= */

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
font-size:44px;
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

/* glow */

.hero::before{
content:"";
position:absolute;
width:350px;
height:350px;
background:#FCA311;
filter:blur(160px);
opacity:0.25;
top:-120px;
left:-120px;
}

/* ================= FORM AREA ================= */

.form-area{
flex:0 0 45%;
display:flex;
align-items:center;
justify-content:flex-start;
}

/* ================= CARD ================= */

.register-card{
width:440px;
padding:40px;
border-radius:18px;

background:rgba(255,255,255,0.08);
backdrop-filter:blur(20px);

box-shadow:0 25px 60px rgba(0,0,0,0.4);
}

/* ================= TITLE ================= */

.register-card h3{
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

.btn-register{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:12px;
border-radius:10px;
width:100%;
transition:0.3s;
}

.btn-register:hover{
background:#ffb733;
transform:translateY(-2px);
}

/* ================= GOOGLE BUTTON ================= */

.btn-google{
background:#FFFFFF;
color:#000;
font-weight:500;
border-radius:10px;
padding:10px;
width:100%;
margin-bottom:15px;
border:none;
}

.btn-google:hover{
background:#f3f3f3;
}

/* ================= LINK ================= */

.link{
color:#FCA311;
text-decoration:none;
}

.link:hover{
text-decoration:underline;
}

.alert{
border-radius:10px;
}

</style>

</head>

<body>

<!-- HERO -->

<div class="hero">

<div class="hero-content">

<h1>Join <span>FinSet</span></h1>

<p>
Create your account and start managing your
income, expenses and savings smarter.
</p>

</div>

</div>


<!-- FORM -->

<div class="form-area">

<div class="register-card">

<h3>Create Account</h3>

<?php
if(isset($error) && $error != ""){
echo "<div class='alert alert-danger'>$error</div>";
}
if(isset($success)){
echo "<div class='alert alert-success'>$success</div>";
}
?>

<form method="POST">

<button type="button" class="btn-google" onclick="window.location.href='google-login.php'">
<i class="fa-brands fa-google"></i> Continue with Google
</button>

<hr style="border-color:#555">

<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-user"></i>
</span>

<input type="text" name="name" class="form-control" placeholder="Full Name" required>

</div>


<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-cake-candles"></i>
</span>

<input type="date" name="birthdate" class="form-control" required>

</div>


<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-envelope"></i>
</span>

<input type="email" name="email" class="form-control" placeholder="Email" required>

</div>


<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-phone"></i>
</span>

<input type="text" name="mobile" class="form-control" placeholder="Mobile Number" maxlength="10" required>

</div>


<div class="input-group mb-3">

<span class="input-group-text">
<i class="fa-solid fa-lock"></i>
</span>

<input type="password" name="password" class="form-control" placeholder="Password" required>

</div>

<button type="submit" class="btn btn-register">
Register
</button>

</form>

<div class="text-center mt-3">

<a href="login.php" class="link">
Already have an account? Login
</a>

</div>

</div>

</div>

</body>
</html>