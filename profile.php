<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// FETCH USER DATA
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);


// UPDATE PROFILE
if(isset($_POST['update_profile'])){

    $name = $_POST['name'];
    $email = $_POST['email'];

    $update = "UPDATE users 
               SET name='$name', email='$email'
               WHERE id='$user_id'";

    mysqli_query($conn, $update);

    $_SESSION['name'] = $name;

    $success = "Profile updated successfully";
}


// CHANGE PASSWORD
if(isset($_POST['change_password'])){

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    if(password_verify($current, $user['password'])){

        $new_hash = password_hash($new, PASSWORD_DEFAULT);

        $update = "UPDATE users 
                   SET password='$new_hash'
                   WHERE id='$user_id'";

        mysqli_query($conn, $update);

        $success = "Password changed successfully";

    }else{

        $error = "Current password is incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>User Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ===== GLOBAL ===== */

body{
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000000;
margin:0;
}

/* ===== HEADER ===== */

.topbar{
background:#14213D;
padding:16px 28px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.topbar h4{
margin:0;
color:#FFFFFF;
font-weight:600;
letter-spacing:0.3px;
}

/* DASHBOARD BUTTON */

.topbar .btn{
background:#FCA311;
border:none;
color:#000;
font-weight:500;
border-radius:8px;
padding:8px 16px;
transition:0.25s;
}

.topbar .btn:hover{
background:#ffb733;
transform:translateY(-1px);
}

/* ===== PROFILE CONTAINER ===== */

.profile-container{
max-width:650px;
margin:auto;
margin-top:40px;
margin-bottom:40px;
}

/* ===== PROFILE CARD ===== */

.profile-card{
border:none;
border-radius:16px;
background:#FFFFFF;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
overflow:hidden;
}

/* ===== PROFILE HEADER ===== */

.profile-header{
background:#14213D;
color:#FFFFFF;
padding:35px 25px;
text-align:center;
}

/* ===== PROFILE ICON ===== */

.profile-icon{
width:85px;
height:85px;
border-radius:50%;
background:#FCA311;
color:#000000;
display:flex;
align-items:center;
justify-content:center;
font-size:34px;
font-weight:600;
margin:auto;
margin-bottom:12px;
box-shadow:0 4px 10px rgba(0,0,0,0.15);
}

/* ===== NAME ===== */

.profile-header h4{
margin:0;
font-weight:600;
}

.profile-header p{
margin:5px 0 0;
font-size:14px;
opacity:0.85;
}

/* ===== CARD BODY ===== */

.card-body{
padding:25px 28px;
}

/* ===== FORM LABEL ===== */

.form-label{
font-weight:500;
font-size:14px;
margin-bottom:5px;
}

/* ===== INPUT ===== */

.form-control{
border-radius:8px;
border:1px solid #d1d5db;
padding:10px 12px;
font-size:14px;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 2px rgba(252,163,17,0.15);
}

/* ===== BUTTONS ===== */

.btn-primary{
background:#14213D;
border:none;
border-radius:8px;
padding:8px 18px;
font-weight:500;
}

.btn-primary:hover{
background:#1f365f;
}

.btn-warning{
background:#FCA311;
border:none;
color:#000000;
border-radius:8px;
padding:8px 18px;
font-weight:500;
}

.btn-warning:hover{
background:#ffb733;
}

/* ===== ALERTS ===== */

.alert{
border-radius:10px;
font-size:14px;
}

/* ===== RESPONSIVE ===== */

@media(max-width:768px){

.profile-container{
padding:0 15px;
}

}

</style>

</head>

<body>


<!-- HEADER -->
<div class="topbar">

<h4>FinSet — Profile Management</h4>

<a href="dashboard.php" class="btn btn-primary">
Back to Dashboard
</a>

</div>



<div class="profile-container">


<?php
if(isset($success)){
echo "<div class='alert alert-success'>$success</div>";
}

if(isset($error)){
echo "<div class='alert alert-danger'>$error</div>";
}
?>


<!-- PROFILE CARD -->

<div class="card profile-card mb-4">

<div class="profile-header">

<div class="profile-icon">
<?php echo strtoupper(substr($user['name'],0,1)); ?>
</div>

<h4><?php echo $user['name']; ?></h4>

<p><?php echo $user['email']; ?></p>

</div>


<div class="card-body">

<h5 class="mb-3">Update Profile</h5>

<form method="POST">

<div class="mb-3">

<label class="form-label">Full Name</label>

<input type="text"
name="name"
class="form-control"
value="<?php echo $user['name']; ?>"
required>

</div>


<div class="mb-3">

<label class="form-label">Email Address</label>

<input type="email"
name="email"
class="form-control"
value="<?php echo $user['email']; ?>"
required>

</div>


<button type="submit"
name="update_profile"
class="btn btn-primary">

Update Profile

</button>

</form>

</div>

</div>



<!-- PASSWORD CARD -->

<div class="card profile-card">

<div class="card-body">

<h5 class="mb-3">Change Password</h5>

<form method="POST">

<div class="mb-3">

<label class="form-label">Current Password</label>

<input type="password"
name="current_password"
class="form-control"
required>

</div>


<div class="mb-3">

<label class="form-label">New Password</label>

<input type="password"
name="new_password"
class="form-control"
required>

</div>


<button type="submit"
name="change_password"
class="btn btn-warning">

Change Password

</button>

</form>

</div>

</div>


</div>


</body>

</html>